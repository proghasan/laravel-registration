<?php
namespace App\Repository;

use App\Models\District;
use App\Models\Division;
use App\Models\Education;
use App\Models\Registration;
use App\Models\Training;
use App\Models\Upazila;
use Illuminate\Support\Facades\DB;
use stdClass;

class RegistrationRepository implements RegistrationRepositoryInterface
{

    public function save(array $request)
    {
        $res = new stdClass;
        try{
            DB::transaction(function () use($request) {
                $photo = null;
                if(isset($request['photo'])){
                    $photo =  time().'.'.$request['photo']->getClientOriginalName();
                    request()->photo->move(public_path('photo'), $photo);
                }
                $cv = null;
                if(isset($request['cv'])){
                    $cv =  time().'.'.$request['cv']->getClientOriginalName();
                    request()->cv->move(public_path('cv'), $cv);
                }
                //registration 
                $registration = new Registration();
                $registration->name = $request['name'];
                $registration->email = $request['email'];
                $registration->division_id = $request['division_id'];
                $registration->district_id = $request['district_id'];
                $registration->upazila_id = $request['upazila_id'];
                $registration->address_details = $request['address_details'];
                $registration->training = $request['training'];
                $registration->photo = $photo;
                $registration->cv = $cv;
                $registration->languages = json_encode($request['languages']);
                $registration->save();

                // training 
                if($request['training'] == 'Yes') {
                    foreach($request['trainings'] as $training){
                        $trainingObj = new Training();
                        $trainingObj->registration_id = $registration->id;
                        $trainingObj->name = $training['name'];
                        $trainingObj->description = $training['description'];
                        $trainingObj->save();
                    }
                }

                // education
                foreach($request['educations'] as $education){
                    $educationObj = new Education();
                    $educationObj->registration_id = $registration->id;
                    $educationObj->exam_name = $education['exam_name'];
                    $educationObj->board_name = $education['board_name'];
                    $educationObj->university_name = $education['university_name'];
                    $educationObj->result = $education['result'];
                    $educationObj->save();
                }
            });
            $res->message = "Registration successfully";
            $res->status = 201;
        }catch(\Exception $e){
            $res->message = $e->getMessage();
            $res->status = 422;
        }
        return $res;
    }

    public function update(array $request)
    {
        $res = new stdClass;
        try{
            DB::transaction(function () use($request) {
                //registration 
                $registration = Registration::find($request['id']);
                
                $photo = null;
                if(isset($request['photo']) && $request['photo'] != null && $request['photo'] != ""){
                    $photo =  time().'.'.$request['photo']->getClientOriginalName();
                    request()->photo->move(public_path('photo'), $photo);

                    $registration->photo = $photo;
                }
                $cv = null;
                if(isset($request['cv']) && $request['cv'] != null && $request['cv'] != ""){
                    $cv =  time().'.'.$request['cv']->getClientOriginalName();
                    request()->cv->move(public_path('cv'), $cv);
                    $registration->cv = $cv;
                }
                
                $registration->name = $request['name'];
                $registration->email = $request['email'];
                $registration->division_id = $request['division_id'];
                $registration->district_id = $request['district_id'];
                $registration->upazila_id = $request['upazila_id'];
                $registration->address_details = $request['address_details'];
                $registration->training = $request['training'];
                $registration->languages = json_encode($request['languages']);
                $registration->save();

                // training 
                // delete to delete if have training before
                if($request['training'] == 'Yes') {
                    foreach($request['trainings'] as $training){
                        $trainingObj = new Training();
                        $trainingObj->registration_id = $registration->id;
                        $trainingObj->name = $training['name'];
                        $trainingObj->description = $training['description'];
                        $trainingObj->save();
                    }
                }else{
                    Training::where('registration_id', $request['id'])->delete();
                }

                // education
                foreach($request['educations'] as $education){
                    $educationObj = Education::findOrNew($education['id']);
                    $educationObj->registration_id = $registration->id;
                    $educationObj->exam_name = $education['exam_name'];
                    $educationObj->board_name = $education['board_name'];
                    $educationObj->university_name = $education['university_name'];
                    $educationObj->result = $education['result'];
                    $educationObj->save();
                }
            });
            $res->message = "Registration update successfully";
            $res->status = 200;
        }catch(\Exception $e){
            $res->message = $e->getMessage();
            $res->status = 422;
        }
        return $res;
    }

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
        $query = Upazila::select("id","name","district_id");
            if(isset($request['districtId']) && $request['districtId'] != "" && $request['districtId'] != null){
                $query->where('district_id', $request['districtId']);
            }
        $upazilas = $query->get();
        return $upazilas;
    }

    public function getApplications($request)
    {
        $res = new stdClass;
        try{
            $query = Registration::with(['educations','trainings','division', 'district', 'upazila']);

            if(isset($request['name']) && $request['name'] != "" && $request['name'] != null) {
                $query->where('name', 'LIKE', '%'.$request['name'].'%');
            }

            if(isset($request['email']) && $request['email'] != "" && $request['email'] != null) {
                $query->where('email', 'LIKE', '%'.$request['email'].'%');
            }

            if(isset($request['divisionId']) && $request['divisionId'] != "" && $request['divisionId'] != null){
                $query->where('division_id', $request['divisionId']);
            }

            if(isset($request['districtId']) && $request['districtId'] != "" && $request['districtId'] != null){
                $query->where('district_id', $request['districtId']);
            }

            if(isset($request['upazilaId']) && $request['upazilaId'] != "" && $request['upazilaId'] != null){
                $query->where('upazila_id', $request['upazilaId']);
            }

            if(isset($request['registrationId']) && $request['registrationId'] != "" && $request['registrationId'] != null){
                $query->where('id', $request['registrationId']);
            }

            $res->data = $query->paginate(3);
            $res->status = 200;

        }catch(\Exception $e){
            $res->data = [];
            $res->status = 422;
        }

        return $res;
    }

    public function getApplication($request)
    {
        $res = new stdClass;
        try{
            $query = Registration::with(['educations', 'trainings']);
            if(isset($request['registrationId']) && $request['registrationId'] != "" && $request['registrationId'] != null){
                $query->where('id', $request['registrationId']);
            }

            $res->data = $query->first();
            $res->status = 200;

        }catch(\Exception $e){
            $res->data = [];
            $res->status = 422;
        }

        return $res;
    }

    public function deleteEducation($request)
    {
        $res = new stdClass;
        try{
            Education::find($request['educationId'])->delete();
            $res->success = true;
            $res->status = 200;
        }catch(\Exception $e){
            $res->success = false;
            $res->status = 422;
        }

        return $res;   
    }

    public function deleteTraining($request)
    {
        $res = new stdClass;
        try{
            Training::find($request['trainingId'])->delete();
            $res->success = true;
            $res->status = 200;
        }catch(\Exception $e){
            $res->success = false;
            $res->status = 422;
        }

        return $res;   
    }

}