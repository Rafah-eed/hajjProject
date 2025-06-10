<?php

namespace App\Http\Controllers;

//use App\Http\Requests\OfficeValidationRequest;
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

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validated();


        $office = Office::create([$validatedData]);

        if ( is_null($office))

            return $this->sendResponse(false,  "error in create" ,204);

        return $this->sendResponse($office, "office has been created");

    }

//    public function update(Request $request): JsonResponse
//    {
//        $validatedData = $request->validated();
//
//        try {
//          $this->update($validatedData);
//        } catch (\Exception $e) {
//            Log::error('Error updating office: ' . $e->getMessage());
//        }
//
//        return $this->sendResponse($office, "office has been updated");
//    }

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

        $randomCode = rand(10000, 99999);

        // Create a new employee record
        $employee = Employee::create([
            'user_id' => $user_id,
            'office_id' => $office_id,
            'position_name' => $request->position_name,
            'salary' => $request->salary,
            'employee_code' => $randomCode,
        ]);

        return $this->sendResponse($employee, "New employee added to office successfully");
    }
    }


