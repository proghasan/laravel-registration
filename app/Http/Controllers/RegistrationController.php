<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function save(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "training" => "required",
            "trainings.*.name" => 'required_if:training,Yes',
            "trainings.*.description" => 'required_if:training,Yes'
        ]);

        if($validation->fails()){
            return response()->json(['message' => $validation->errors()->all()], 422);
        }

        return response()->json($request->trainings);
    }
}
