<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfficeStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Office;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;


class OfficeController extends BaseController
{

    public function index(): JsonResponse
    {
        $offices = Office::all();

        if ( is_null($offices))
            return $this->sendResponse(false,  "No data available" ,204);

        return $this->sendResponse($offices, "Offices has been retrieved");

    }

    public function officeById($office_id): JsonResponse
    {
        $office = Office::find($office_id);

        if ( is_null($office))
            return $this->sendResponse(false,  "No office with sent  ID" ,204);

        return $this->sendResponse($office, "office has been retrieved");

    }

    public function store(OfficeStoreRequest $request): JsonResponse

    {
        try {
        $validatedData = $request->validated();

        if (empty($validatedData['name'])) {
            return $this->sendResponse(false, "Name is required.", 400);
        }

        $office = new Office();
        $office->fill($validatedData);
        $office->save();

        return $this->sendResponse($office, "Office has been created successfully");
    } catch (\Exception $e) {
        Log::error('Error creating office: ' . $e->getMessage());
        return $this->sendResponse(false, "An error occurred while creating the office", 500);
    }

    }

    public function update(Request $request, int $office_id): JsonResponse
    {

        try {
            $office = $this->findOfficeById($office_id);
            if (!$office) {
                return $this->sendResponse(false, "Office not found.", 404);
            }

            // Handle partial updates
            $updates = [];
            if ($request['address']) {
                $updates['address'] = $request['address'];
            }
            if ($request['name']) {
                $updates['name'] = $request['name'];
            }
            if ($request['license_number']) {
                $updates['license_number'] = $request['license_number'];
            }

            if (!empty($updates)) {
                $office->update($updates);
            } else {
                return $this->sendResponse($office, "No changes were made to the office.");
            }

            return $this->sendResponse($office, "Office has been updated successfully");
        } catch (\Exception $e) {
            Log::error('Error updating office: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while updating the office", 500);
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

        // $randomCode = rand(10000, 99999);

        // Create a new employee record
        $employee = Employee::create([
            'user_id' => $user_id,
            'office_id' => $office_id,
            'position_name' => $request->position_name,
            'salary' => $request->salary,
            // 'employee_code' => $randomCode,
        ]);

        return $this->sendResponse($employee, "New employee added to office successfully");
    }
    }