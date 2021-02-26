<?php
namespace App\Repository;

use App\Models\District;
use App\Models\Division;
use App\Models\Upazila;

class RegistrationRepository implements RegistrationRepositoryInterface
{

    public function getDivisions()
    {
        $divisions = Division::select("id","name")->get();
        return $divisions;
    }
    public function getDistricts($request)
    {
        $query = District::select("id","name","division_id");
            if(isset($request['divisionId']) && $request['divisionId'] != "" && $request['divisionId'] != null){
                $query->where('division_id', $request['divisionId']);
            }
        $districts = $query->get();
        return $districts;
    }
    public function getUpazilas($request)
    {
        $query  = Upazila::select("id","name","district_id");
            if(isset($request['districtId']) && $request['districtId'] != "" && $request['districtId'] != null){
                $query->where('district_id', $request['districtId']);
            }
        $upazilas = $query->get();
        return $upazilas;
    }

}