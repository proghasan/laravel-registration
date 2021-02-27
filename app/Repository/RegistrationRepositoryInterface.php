<?php
namespace App\Repository;

interface RegistrationRepositoryInterface
{
    public function save(array $request);
    public function get($request);
    public function getDivisions();
    public function getDistricts($request);
    public function getUpazilas($request);
}