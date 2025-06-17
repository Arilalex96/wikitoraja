@php
    $pagename = 'profile';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('page-css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .min-h-80vh {
            min-height: 80vh !important;
        }
    </style>
@endsection
@section('content')
    <div class="container py-5 min-h-80vh">
        <div class="card p-4 col-12 col-md-8 col-lg-6 mx-auto">
            <div class="card-body">
                <h3 class="card-title mb-4">Profil Saya</h3>
                <div class="user-photo mb-4 text-center">
                    <img src="{{ asset('/uploaded-image/profile-photo/'.$user->image) }}" style="max-width: 35%; height: auto;" alt="" class="img-thumbnail profile-photo">
                    <input type="file" class="form-control mt-2 profile-photo-input" name="image" style="width: 100%;" accept="image/png, image/jpeg">
                </div>
                <div class="user-info mb-4">
                <p><b>Nama:</b> {{ $user->name }}</p>
                    <p><b>Email:</b> {{ $user->email }}</p>
                    <p><b>Peran:</b> {{ $user->role }}</p>
                </div>
                <h5>Ubah Kata Sandi:</h5>
                <form id="change_password">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="new_password" name="password" required
                            placeholder="Masukkan kata sandi baru Anda">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="password" required
                            placeholder="Masukkan kata sandi saat ini">
                    </div>
                    <button type="submit" class="btn btn-primary change-password">Ubah</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $('form#change_password').on('submit', function(e){
            e.preventDefault();
            var this_elem = $(this);
            var submit_button = $('button.change-password');

            var new_password_elmnt = $('#new_password');
            var current_password_elmnt = $('#current_password');

            var new_password = new_password_elmnt.val();
            var current_password = current_password_elmnt.val();

            if(new_password === current_password){
                fireToast('New password cannnot same with current password. Try something else!', 'warning');
                return false;
            }

            var data = {
                _token: "{{ csrf_token() }}",
                new_password: new_password,
                current_password: current_password,
            };

            var url = "{{ route('user.edit_password.backend') }}";
            $.ajax({
                type: "PATCH",
                url: url,
                data: data,
                success: success,
                dataType: "json",
                beforeSend: function(){
                    submit_button.prop('disabled', true).text('Loading..');
                    this_elem.find('.alert').remove();
                },
                complete: function(){
                    submit_button.prop('disabled', false).text('Change');
                }
            }).fail(function(){
                fireToast('Failed updating data!', 'danger');
            });

            function success(json){
                if(json.success === true){
                    fireToast('Password changed successfully', 'success');
                    clearFormInput(this_elem);
                }else if(json.success === false || typeof json.success === 'undefined'){
                    if(json.data.error === 'wrong_current_password'){
                        var alert_elmnt = `
                            <div class="alert alert-danger">
                                `+ json.message +`
                            </div>
                        `;
                        current_password_elmnt.parent('div').after(alert_elmnt);
                    }else if(json.data.error === 'same_password'){
                        var alert_elmnt = `
                            <div class="alert alert-danger">
                                `+ json.message +`
                            </div>
                        `;
                        new_password_elmnt.parent('div').after(alert_elmnt);
                    }else{
                        var alert_elmnt = `
                            <div class="alert alert-danger">
                                Failed to update data. Try again..
                            </div>
                        `;
                        current_password.parent('div').after(alert_elmnt);
                    }
                }
            }

            function clearFormInput(form){
                form.find('input').val('');
            }
        });

        $('.profile-photo-input').on('change', function(){
            var this_elem = $(this);
            this_elem.prop('disabled', true);

            var image = this_elem[0].files[0];
            var csrf = "{{ csrf_token() }}";

            var formData = new FormData();
            formData.append('image', image);
            formData.append('_token', csrf);
            $.ajax({
                type: "POST",
                url: "{{ route('user.edit_profile_photo.backend') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: success,
                dataType: "json",
                beforeSend: function(){
                    this_elem.prop('disabled', true);
                    fireToast('Uploading profile photo..', 'warning');
                },
                complete: function(){
                    this_elem.prop('disabled', false);
                }
            }).fail(function(){
                fireToast('Failed updating profile photo', 'danger');
                clearImageInput();
            });

            function success(json){
                if(json.success === true){
                    fireToast('Profile photo updated successfully', 'success');
                    clearImageInput();
                    setNewImage(json.data.image);
                }else if(json.success === false || typeof json.success === 'undefined'){
                    fireToast("Failed updating profile photo\n" + json.message, 'danger');
                    clearImageInput();
                }
            }

            function clearImageInput(){
                $('.profile-photo-input').val('');
            }

            function setNewImage(image_src){
                $('img.profile-photo').attr('src', '/uploaded-image/profile-photo/'+image_src);
            }
        });

        @include('components.toast')
    </script>
@endsection
