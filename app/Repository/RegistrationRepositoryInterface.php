<?php
namespace App\Repository;

interface RegistrationRepositoryInterface
{
    public function getDivisions();
    public function getDistricts($request);
    public function getUpazilas($request);
}