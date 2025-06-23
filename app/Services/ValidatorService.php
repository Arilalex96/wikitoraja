<?php

namespace App\Services;

use App\Models\User;

class ValidatorService {
    public function index(){
        return User::role('validator')
        ->select(
            'id',
            'name',
            'email',
            'active',
            'created_at'
        )->get();
    }
}