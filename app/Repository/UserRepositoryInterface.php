<?php
namespace App\Repository;

use App\Models\User;

interface UserRepositoryInterface
{
    public function login($request);
    public function save(array $request);

}