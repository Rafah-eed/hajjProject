<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfficeStoreRequest;
use App\Models\Guide;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Office;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class OfficeController extends BaseController
{

    public function index(): JsonResponse
    {
        $offices = Office::all();

        if ( is_null($offices))
            return $this->sendResponse(false,  "No data available");

        return $this->sendResponse($offices, "Offices has been retrieved");

    }

    public function officeById($office_id): JsonResponse
    {
        $office = Office::find($office_id);

        if ( is_null($office))
            return $this->sendResponse(false,  "No office with sent  ID");

        return $this->sendResponse($office, "office has been retrieved");

    }



    public function store(OfficeStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if (empty($validatedData['name'])) {
                return $this->sendResponse(false, "Name is required.");
            }

            // Generate random email
            $email = Str::lower(str_replace(' ', '_', $validatedData['name']) . '@example.com');

            // Generate random password
            $password = Str::random(10);

            $validatedData['office_email'] = $email;
            $validatedData['office_password'] = $password;

            $office = new Office();
            $office->fill($validatedData);
            $office->save();

            return $this->sendResponse($office, "Office has been created successfully with auto-generated email and password");
        } catch (Exception $e) {
            Log::error('Error creating office: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while creating the office");
        }
    }


    public function update(Request $request, int $office_id): JsonResponse
    {
        try {
            $office = $this->findOfficeById($office_id);
            if (!$office) {
                return $this->sendResponse(false, "Office not found.");
            }

            $validatedData = $request->validate([
                'address' => 'nullable|string|max:255',
                'name' => 'nullable|string|max:255',
                'license_number' => 'nullable|numeric',
                'office_email' => ['required', 'email', 'max:255'],
                'office_password' => ['nullable', 'min:8'],
            ]);


            $updates = [];
            foreach ($validatedData as $key => $value) {
                if (!is_null($value)) {
                    $updates[$key] = $value;
                }
            }

            if (!empty($updates)) {
                $office->update($updates);
            } else {
                return $this->sendResponse($office, "No changes were made to the office.");
            }

            return $this->sendResponse($office, "Office has been updated successfully");
        } catch (Exception $e) {
            Log::error('Error updating office: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while updating the office");
        }
    }

    private function findOfficeById($id)
    {
        return Office::findOrFail($id);
    }

    public function destroy(int $office_id): JsonResponse
    {
        $office = Office::findOrFail($office_id);

        $office->delete();

        return $this->sendResponse($office, "office has been deleted");
    }

    public function addEmployeeToOffice(Request $request): JsonResponse
    {
        $office_id = $request->input('office_id');
        $user_id = $request->input('user_id');

        $office = Office::findOrFail($office_id);
        $user = User::findOrFail($user_id);

        // Validate office credentials
        if (!$this->validateOfficeCredentials($request)) {
            return $this->sendResponse(false, "Invalid office credentials", 401);
        }

        // Create a new employee record
        $employee = Employee::create([
            'user_id' => $user_id,
            'office_id' => $office_id,
            'position_name' => $request->position_name,
            'salary' => $request->salary,
        ]);

        return $this->sendResponse($employee, "New employee added to office successfully");
    }

    private function validateOfficeCredentials(Request $request): bool
    {
        $validatedData = $request->validate([
            'office_email' => ['required', 'email', 'max:255'],
            'office_password' => ['required', 'min:8'],
        ]);

        $officeCredentials = Office::where('office_email', $validatedData['office_email'])
            ->where('office_password', $validatedData['office_password'])
            ->first();

        return !is_null($officeCredentials);
    }

    public function getEmployeesOfOffice(int $office_id): JsonResponse
    {
        $office = Office::with('employees')->findOrFail($office_id);
        $employees = $office->employees;

        return $this->sendResponse($employees, "Employees retrieved successfully");
    }

public function getAllGuidesForOffice(int $office_id): JsonResponse
{
    try {
        // Check if there are any guides for the office
        $count = Guide::where('office_id', $office_id)->count();

        if ($count === 0) {
            return response()->json(['message' => 'No guides found for this office'], 404);
        }

           $guides = Guide::query()
            ->join('users', 'guides.user_id', '=', 'users.id')
            ->where('guides.office_id', $office_id)
            ->select(
                DB::raw('DISTINCT users.id as user_id'),
                DB::raw('guides.id as guide_id'),
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name")
            )
            ->orderBy('users.id', 'asc')
            ->orderBy('guides.id', 'asc')
            ->groupBy('users.id', 'guides.id', 'full_name')
            ->get();


        return $this->sendResponse($guides, "All guides for the office have been retrieved successfully");

    } catch (\Exception $e) {
        Log::error('Error fetching guide data', ['error' => $e->getMessage()]);
        return $this->sendError('Error fetching guide data', $e->getMessage());
    }
}
    }