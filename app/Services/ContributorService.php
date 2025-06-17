<?php

namespace App\Services;

use App\Models\User;

class ContributorService {
    public function index(){
        return User::role('contributor')
        ->select(
            'id', 
            'name', 
            'email', 
            'active', 
            'created_at'
        )->get();
    }
}