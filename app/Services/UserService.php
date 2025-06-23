<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService {
    public function index() {
        return User::all();
    }

    public function get($id) {
        return User::find($id);
    }

    //$param = [name, email, password, role]
    public function create(array $column, $role) {
        return User::create($column)->assignRole($role);
    }

    public function edit($id, array $data) {
        if(array_key_exists('password', $data)){
            $data['password'] = Hash::make($data['password']);
        }
        return User::where('id', $id)->update($data);
    }
    
    public function editActivation($user_id, $active){
        return User::where('id', $user_id)->update([
            'active' => $active
        ]);
    }

    public function delete($id) {
        return User::where('id', $id)->delete();
    }

    public function isNewPasswordDifferent($data){
        if($data['current_password'] == $data['new_password'])
            return false;
        else
            return true;
    }

    public function isCurrentPasswordValid($data){
        return Hash::check($data['current_password'], auth()->user()->password);
    }

    public function editPassword($data){
        auth()->user()->update([
            'password' => Hash::make($data['new_password']),
        ]);
    }

    public function editProfilePhoto($data){
        $user = auth()->user();
        $new_filename = $this->storeFile($data['image']);
        if($user->profile_photo != 'mockup-profile.jpg'){
            $this->deleteFile($user->image);
        }
        $data['image'] = $new_filename;
        $user->update($data);

        return $new_filename;
    }

    public function storeFile($file){
        $directory = 'public/uploads/images/profile-photo';
        $filename = uniqid().".".$file->getClientOriginalExtension();
        Storage::putFileAs($directory, $file, $filename);
        return $filename;
    }

    public function deleteFile($filename){
        if(!$filename){
            return false;
        }

        $directory = 'public/uploads/images/profile-photo';
        if(Storage::exists($directory.$filename)){
            Storage::delete($directory.$filename);
        }
    }


}