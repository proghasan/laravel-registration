<?php
namespace App\Repository;
interface UserRepositoryInterface
{
    public function login($request);
    public function save(array $request);

}