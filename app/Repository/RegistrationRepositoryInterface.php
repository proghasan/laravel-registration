<?php
namespace App\Repository;

interface RegistrationRepositoryInterface
{
    public function save(array $request);
    public function update(array $request);
    public function deleteEducation($request);
    public function deleteTraining($request);
    public function getApplications($request);
    public function getApplication($request);
    public function getDivisions();
    public function getDistricts($request);
    public function getUpazilas($request);
}