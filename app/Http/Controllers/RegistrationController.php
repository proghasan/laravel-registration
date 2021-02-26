<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Repository\RegistrationRepositoryInterface;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    private $registrationRepo;
    public function __construct(RegistrationRepositoryInterface $registrationRepo)
    {
        $this->registrationRepo = $registrationRepo;
    }
    public function save(RegistrationRequest $request)
    {

        return response()->json(['message' => $request->all()], 200);
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

    public function getUpailas(Request $request)
    {
        $upazilas = $this->registrationRepo->getUpazilas($request->all());
        return response()->json(['upazilas' => $upazilas], 200);
    }
}
