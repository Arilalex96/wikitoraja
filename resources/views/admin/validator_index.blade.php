@php
    $pagename = 'manage validator';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('page-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .min-h-80vh {
            min-height: 80vh !important;
        }

        .action-button-wrapper {
            display: flex;
            gap: 5px;
        }

        /* make table font smaller */
        div#article_wrapper {
            font-size: 0.95em;
        }

        /* Responsif untuk tabel */
        @media (max-width: 768px) {
            table {
                font-size: 0.8em; /* Ukuran font lebih kecil pada perangkat kecil */
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-5 min-h-80vh">
        <button type="button" class="btn btn-sm btn-primary mb-2 create-validator-btn">Add New Validator</button>
        <div class="table-responsive">
            <table id="validator" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aktivasi</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal Create Validator -->
    <div class="modal fade create-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Validator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createValidatorForm">
                    <div class="modal-body px-4">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="createName" name="name" required placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="createEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="createEmail" name="email" required placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <label for="createPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="createPassword" name="password" required placeholder="Enter your password">
                        </div>
                        <div class="mb-3">
                            <label for="createActivation" class="form-label">Activation</label>
                            <select class="form-select" id="createActivation" name="active" required>
                                <option value="1">Active</option>
                                <option value="0">Not Active</option>
                            </select>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('system_error'))
                            <div class="alert alert-danger">
                                {{ session('system_error') }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-create">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Validator -->
    <div class="modal fade edit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Validator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editValidatorForm">
                    <div class="modal-body px-4">
                        @csrf
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required placeholder="Enter your name" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required placeholder="Enter your email" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="password" placeholder="Enter your password" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editActivation" class="form-label">Activation</label>
                            <select class="form-select" id="editActivation" name="active" required disabled>
                                <option value="1">Active</option>
                                <option value="0">Not Active</option>
                            </select>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('system_error'))
                            <div class="alert alert-danger">
                                {{ session('system_error') }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirm Delete -->
    <div class="modal fade confirm-delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary btn-yes">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>    
    <script>
        var create_modal = new bootstrap.Modal($('.create-modal'), {});
        var edit_modal = new bootstrap.Modal($('.edit-modal'), {});
        var confirm_delete_modal = new bootstrap.Modal($('.confirm-delete-modal'), {});

        $(document).ready(function () {
            validator_table = $('table#validator').DataTable({
                responsive: true, // Aktifkan responsif
                serverMethod: 'GET',
                ajax: {
                    url: "{{ route('validator.index.json') }}",
                },
                columns: [
                    { data: null },
                ], 
                columnDefs: [
                    { 
                        targets: 1,
                        data: "name",
                    },
                    { 
                        targets: 2,
                        data: "email",
                    },
                    { 
                        targets: 3,
                        data: "active",
                        render: function (data) {
                            return data === true 
                                ? '<span class="badge rounded-pill bg-success">Active</span>' 
                                : '<span class="badge rounded-pill bg-danger">Not Active</span>';
                        }
                    },
                    { 
                        targets: 4,
                        data: "created_at",
                    },
                    {
                        targets: 5,
                        data: "active",
                        render: function (data) {
                            return '<div class="action-button-wrapper">' +
                                '<button type="button" class="btn btn-warning btn-sm edit-validator-btn">Edit</button>' +
                                '<button type="button" class="btn btn-danger btn-sm delete-validator-btn">Delete</button>' +
                                '</div>';
                        }
                    },
                ],
                fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('td:eq(0)', nRow).html(iDisplayIndexFull + 1);
                }
            });

            $('.create-validator-btn').on('click', function(){
                create_modal.show();
            });

            $('#createValidatorForm').submit(function(e){
                e.preventDefault();
                createValidator();
            });

            $('.edit-modal').find('#editValidatorForm').submit(function(e){
                e.preventDefault();
                editValidator(old_validator_data.id);
            });

            $('table#validator tbody').on('click', '.edit-validator-btn', function(){
                var id = validator_table.row($(this).parents('tr')).data().id;
                edit_modal.show();
                fillEditForm(id);
            });

            $('table#validator tbody').on('click', '.delete-validator-btn', function(){
                var name = validator_table.row($(this).parents('tr')).data().name;
                var id = validator_table.row($(this).parents('tr')).data().id;
                var content = '<p>Are you sure to delete validator with name <i>' + name + '</i>?</p>';
                $('.confirm-delete-modal').find('.modal-body').html(content);
                confirm_delete_modal.show();

                $('.confirm-delete-modal').find('.modal-footer button').click(function(e){
                    if($(e.target).hasClass('btn-yes')){
                        deleteValidator(id);
                    }
                    $(this).off('click');
                });
            });
        });

        function createValidator(){
            var form = $('#createValidatorForm');
            var csrf = form.find('input[name="_token"]').val();
            var name = form.find('input[name="name"]').val();
            var email = form.find('input[name="email"]').val();
            var password = form.find('input[name="password"]').val();
            var active = form.find('select[name="active"]').val();

            if(name === '' || email === '' || password === '' || active === ''){
                return false;
            }

            active = parseInt(active);

            let data = {
                _token: csrf,
                name: name,
                email: email,
                password: password,
                active: active
            };

            $.ajax({
                type: "POST",
                url: "{{ route('validator.create.backend') }}",
                data: data,
                success: success,
                dataType: "json",
                beforeSend: function(){
                    form.find('button[type="submit"]').prop('disabled', true).text('Saving..');
                },
                complete: function(){
                    form.find('button[type="submit"]').prop('disabled', false).text('Submit');
                }
            }).fail(function(){
                fireToast('Failed adding new data!', 'danger');
            });

            function success(json){
                if(json.success == true){
                    clearFormInput(form);
                    create_modal.hide();
                    validator_table.ajax.reload(null, false);
                    fireToast("New validator user created successfully!", 'success');
                } else {
                    fireToast("Failed adding new data!\n" + json.message, 'danger');
                }
            }
        }

        var old_validator_data = {};

        function editValidator(id){
            var form = $('#editValidatorForm');
            var csrf = form.find('input[name="_token"]').val();
            var name = form.find('input[name="name"]').val();
            var email = form.find('input[name="email"]').val();
            var password = form.find('input[name="password"]').val();
            var active = parseInt(form.find('select[name="active"]').val());

            var data = {};
            if(name !== old_validator_data.name){
                data.name = name;
            }

            if(email !== old_validator_data.email){
                data.email = email;
            }

            if(active !== old_validator_data.active){
                data.active = active;
            }

            if(password !== ''){
                data.password = password;
            }

            if(Object.keys(data).length === 0){
                fireToast('Cannot update anything. No changes on data!', 'warning');
                return false;
            }

            data._token = csrf;

            var url = "{{ route('validator.edit.backend', ['user_id'=> 'user_id']) }}".replace("user_id", id);

            $.ajax({
                type: "PATCH",
                url: url,
                data: data,
                success: success,
                dataType: "json",
                beforeSend: function(){
                    form.find('button[type="submit"]').prop('disabled', true).text('Saving..');
                },
                complete: function(){
                    form.find('button[type="submit"]').prop('disabled', false).text('Update');
                }
            }).fail(function(){
                fireToast('Failed updating data!', 'danger');
            });

            function success(json){
                if(json.success == true){
                    edit_modal.hide();
                    clearFormInput(form);
                    validator_table.ajax.reload(null, false);
                    fireToast("Validator user updated successfully!", 'success');
                } else {
                    fireToast("Failed updating data!\n" + json.message, 'danger');
                }
            }
        }

        function fillEditForm(id){
            var form = $('#editValidatorForm');
            let url = "{{ route('validator.get.json',['user_id' => 'user_id']) }}".replace("user_id", id);

            $.ajax({
                type: "GET",
                url: url,
                success: success,
                dataType: "json",
            }).fail(function(){
                fireToast('Failed to get data!', 'danger');
            });

            function success(json){
                if(json.success == true){
                    enableInput();
                    fillForm(json.data);
                    old_validator_data = json.data;
                    delete old_validator_data.created_at;
                } else {
                    fireToast("Failed to get data!\n" + json.message, 'danger');
                }
            }

            function fillForm(data){
                form.find('input[name="name"]').val(data.name);
                form.find('input[name="email"]').val(data.email);
                form.find('select[name="active"]').val(data.active ? 1 : 0);
            }

            function enableInput(){
                form.find('input[name="name"]').prop('disabled', false);
                form.find('input[name="email"]').prop('disabled', false);
                form.find('input[name="password"]').prop('disabled', false);
                form.find('select[name="active"]').prop('disabled', false);
            }
        }

        function clearFormInput(form){
            form.find('input[name="name"]').val('');
            form.find('input[name="email"]').val('');
            form.find('input[name="password"]').val('');
            form.find('select[name="active"]').val('1');
        }

        function deleteValidator(id){
            var url = "{{ route('validator.delete.backend', ['user_id'=>'user_id']) }}".replace('user_id', id);
            var data = {
                _token: '{{ csrf_token() }}'
            };
            $.ajax({
                type: "DELETE",
                url: url,
                data: data,
                dataType: "json",
                success: success,
                beforeSend: function(){
                    $('.confirm-delete-modal').find('.modal-footer button').prop('hidden', true);
                    $('.confirm-delete-modal').find('.modal-body').html('<p>Deleting validator. Please wait..</p>');
                    $('.confirm-delete-modal').find('.modal-title').text('Info');
                },
                complete: function(){
                    confirm_delete_modal.hide();
                    $('.confirm-delete-modal').find('.modal-footer button').prop('hidden', false);
                    $('.confirm-delete-modal').find('.modal-title').text('Confirmation');
                }
            }).fail(function(){
                fireToast("Failed deleting data!", 'danger');
            });

            function success(json){
                if(json.success === true){
                    validator_table.ajax.reload(null, false);
                    fireToast("Validator user deleted successfully!", 'success');
                } else {
                    fireToast("Failed deleting data!\n" + json.message, 'danger');
                }
            }
        }

        @include('components.toast')
    </script>
@endsection
