<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use App\Repository\RegistrationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    private $registrationRepo;
    public function __construct(RegistrationRepositoryInterface $registrationRepo)
    {
        $this->registrationRepo = $registrationRepo;
    }
    public function save(Request $request)
    {
        $data = $request->all();
        $data['educations'] = json_decode($request->educations, true);
        $data['languages'] = json_decode($request->languages, true);
        $data['trainings'] = json_decode($request->trainings, true);
        $request->replace($data);
        
        $validator = \Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => "required|max:255",
            "district_id" => "required|max:11",
            "division_id" => "required|max:11",
            "upazila_id" => "required|max:11",
            "address_details" => "required|max:255",
            "training" => "required",
            "languages" => "required|array",
            "educations" => "required|array",
            "educations.*.exam_name" => "required|max:255",
            "educations.*.university_name" => "required|max:255",
            "educations.*.board_name" => "required|max:255",
            "educations.*.result" => "required",
            "photo" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "cv" => "required|file|mimes:doc,pdf,docx|max:2048",
            "training" => "required",
            "trainings.*.name" => 'required_if:training,Yes',
            "trainings.*.description" => 'required_if:training,Yes'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()], 422);
        }

        $response = $this->registrationRepo->save($request->all());
        return response()->json(['message' => $response->message], $response->status);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $data['educations'] = json_decode($request->educations, true);
        $data['languages'] = json_decode($request->languages, true);
        $data['trainings'] = json_decode($request->trainings, true);
        $request->replace($data);
        
        $validator = \Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => "required|max:255",
            "district_id" => "required|max:11",
            "division_id" => "required|max:11",
            "upazila_id" => "required|max:11",
            "address_details" => "required|max:255",
            "training" => "required",
            "languages" => "required|array",
            "educations" => "required|array",
            "educations.*.exam_name" => "required|max:255",
            "educations.*.university_name" => "required|max:255",
            "educations.*.board_name" => "required|max:255",
            "educations.*.result" => "required",
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "cv" => "nullable|file|mimes:doc,pdf,docx|max:2048",
            "training" => "required",
            "trainings.*.name" => 'required_if:training,Yes',
            "trainings.*.description" => 'required_if:training,Yes'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()], 422);
        }

        $response = $this->registrationRepo->update($request->all());
        return response()->json(['message' => $response->message], $response->status);
    }

    public function deleteEducation(Request $request)
    {
        $response = $this->registrationRepo->deleteEducation($request->all());
        return response()->json(['status' => $response->success], $response->status);
    }

    public function deleteTraining(Request $request)
    {
        $response = $this->registrationRepo->deleteTraining($request->all());
        return response()->json(['status' => $response->success], $response->status);
    }

    public function getApplications(Request $request)
    {
        $response = $this->registrationRepo->getApplications($request->all());
        return response()->json(['applications' => $response->data], $response->status);
    }

    public function getApplication(Request $request)
    {
        $response = $this->registrationRepo->getApplication($request->all());
        return response()->json(['application' => $response->data], $response->status);
    }

    public function getDivisions()
    {
        $divisions = $this->registrationRepo->getDivisions();
        return response()->json(['divisions' => $divisions], 200);
    }

    public function getDistricts(Request $request)
    {
        $districts = $this->registrationRepo->getDistricts($request->all());
        return response()->json(['districts' => $districts], 200);
    }

    public function getUpazilas(Request $request)
    {
        $upazilas = $this->registrationRepo->getUpazilas($request->all());
        return response()->json(['upazilas' => $upazilas], 200);
    }
}
