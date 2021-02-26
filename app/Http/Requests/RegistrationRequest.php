<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|max:255",
            "email" => "required|max:255",
            "district_id" => "required|max:11",
            "division_id" => "required|max:11",
            "upazila_id" => "required|max:11",
            "address_details" => "required|max:255",
            "training" => "required",
            json_decode("languages") => "required|array",
            json_decode("educations") => "required|array",
            json_decode("educations.*.exam_name") => "required|max:255",
            json_decode("educations.*.university_name") => "required|max:255",
            json_decode("educations.*.board_name") => "required|max:255",
            json_decode("educations.*.result") => "required",
            "photo" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "cv" => "required|file|mimes:doc,pdf,docx|max:2048",
            "training" => "required",
            json_decode("trainings.*.name") => 'required_if:training,Yes',
            json_decode("trainings.*.description") => 'required_if:training,Yes'
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->all()], 422)); 
    }
}
