<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CreateValidatorUserRequest;
use App\Http\Requests\Admin\EditUserActivationRequest;
use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Requests\All\EditPasswordRequest;
use App\Http\Requests\All\EditProfilePhotoRequest;
use App\Services\UserService;
use App\Http\Requests\User\StoreUserContributorRegistrationRequest;
use App\Http\Resources\Admin\ContributorResource;
use App\Http\Resources\Admin\ValidatorResource;
use App\Services\ContributorService;
use App\Services\ValidatorService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:index contributor', 
            only: ['IndexContributorView', 'IndexContributorJson']),

            new Middleware('permission:index validator', 
            only: ['IndexValidatorView', 'IndexValidatorJson']),

            new Middleware('permission:get validator', 
            only: ['getValidator']),

            new Middleware('permission:create validator', 
            only: ['createValidator']),

            new Middleware('permission:delete validator', 
            only: ['deleteValidator']),

            new Middleware('permission:edit non admin user', 
            only: ['edit', 'editUserActivation']),

            //profile
            new Middleware('permission:view own profile', 
            only: ['profileView']),

            new Middleware('permission:edit own password', 
            only: ['editPasswordBackend']),

        ];
    }

    public function indexContributorView()
    {
        return view('admin.contributor_index');
    }

    public function indexContributorJson(ContributorService $contributor_service)
    {
        $data = $contributor_service->index();
        return ContributorResource::collection($data);
    }

    /**
     * Manual Registration by Contributor
     */
    public function createContributor(UserService $user_service, StoreUserContributorRegistrationRequest $store_user_contributor_registration_request)
    {
        $data = $store_user_contributor_registration_request->validated();
        $data['active'] = true;

        if($user_service->create($data, 'contributor')){
            return redirect()->route('login')->with('registration_success', 'Registration success! Now you can login');
        }

        return back()->with('system_error', 'System Error. Try Again')->withInput();
    }

    public function indexValidatorView()
    {
        return view('admin.validator_index');
    }

    public function indexValidatorJson(ValidatorService $validator_service)
    {
        $data = $validator_service->index();
        return ContributorResource::collection($data);
    }

    public function edit($user_id, UserService $user_service, EditUserRequest $edit_user_request){
        $data = $edit_user_request->validated();
        $update =  $user_service->edit($user_id, $data);
        if($update == 1){
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'data' => new ValidatorResource($user_service->get($user_id)),
            ], $status = 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'User update failed!',
        ], $status = 500);
    }

    public function editUserActivation($user_id, UserService $user_service, EditUserActivationRequest $edit_user_activation_request){
        $data = $edit_user_activation_request->validated();
        $update =  $user_service->editActivation($user_id, $data['active']);
        if($update == 1){
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'data' => new ContributorResource($user_service->get($user_id)),
            ], $status = 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'User update failed!',
        ], $status = 500);
    }

    public function createValidator(
        UserService $user_service, 
        CreateValidatorUserRequest $create_validator_user_request
    ){
        $data = $create_validator_user_request->validated();
        $data['active'] = true;
        $validator = $user_service->create($data, 'validator');
        if($validator){
            return response()->json([
                'success' => true,
                'message' => 'User with role "Validator" created successfully!',
                'data' => new ValidatorResource($validator),
            ], $status = 201);
        }
    }

    public function getValidator($user_id, UserService $user_service){
        $validator = $user_service->get($user_id);
        if($validator){
            return response()->json([
                'success' => true,
                'data' => new ValidatorResource($validator),
            ], $status = 200);
        }
    }

    public function deleteValidator($user_id, UserService $user_service){
        $validator = $user_service->delete($user_id);
        if($validator){
            return response()->json([
                'success' => true,
                'message' => 'User with role "Validator" deleted sucessfully'
            ], $status = 200);
        }
    }

    public function profileView(){
        $user = auth()->user()->getProfileData();
        return view('all.profile', ['user'=> $user]);
    }

    public function editPasswordBackend(
        EditPasswordRequest $edit_password_request, 
        UserService $user_service
    ){
        $data = $edit_password_request->validated();

        if(!$user_service->isCurrentPasswordValid($data)){
            return response()->json([
                'success' => false,
                'data' => [
                    'error' => 'wrong_current_password'
                ],
                'message' => 'Current password is wrong'
            ], $status = 200);
        }

        if(!$user_service->isNewPasswordDifferent($data)){
            return response()->json([
                'success' => false,
                'data' => [
                    'error' => 'same_password'
                ],
                'message' => 'New password cannnot same with current password'
            ], $status = 200);
        }

        $user_service->editPassword($data);
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ], $status = 200);
    }

    public function editProfilePhotoBackend(
        EditProfilePhotoRequest $edit_profile_photo_request,
        UserService $user_service,
    ){
        if(!auth()->check()){
            abort(403);
        };

        $data = $edit_profile_photo_request->validated();
        $file_name = $user_service->editProfilePhoto($data);

        return response()->json([
            'success' => true,
            'data' => [
                'image' => $file_name,
            ],
            'message' => 'Profile photo updated successfully'
        ], $status = 200);
    }
}
