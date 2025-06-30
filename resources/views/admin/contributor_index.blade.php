@php
    $pagename = 'manage contributor';
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
        <div class="table-responsive">
            <table id="contributor" class="table table-striped table-bordered">
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
@endsection

@section('page-js')
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $(document).ready(function () {
            contributor_table = $('table#contributor').DataTable({
                responsive: true, // Aktifkan responsif
                serverMethod: 'GET',
                ajax: {
                    url: "{{ route('contributor.index.json') }}",
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
                            return data === true 
                                ? '<button type="button" class="btn btn-danger deactivate btn-sm edit-status-btn">Deactivate</button>' 
                                : '<button type="button" class="btn btn-success activate btn-sm edit-status-btn">Activate</button>';
                        }
                    },
                ],
                fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('td:eq(0)', nRow).html(iDisplayIndexFull + 1);
                }
            });

            $('table#contributor tbody').on('click', '.edit-status-btn', function() {
                var button = $(this);
                var id = contributor_table.row($(this).parents('tr')).data().id;
                var active = button.hasClass('deactivate') ? false : true;

                editContributorStatus(id, active, button);
            });

            function editContributorStatus(id, active, this_elem) {
                var url = "{{ route('contributor.edit_activation.backend', ['user_id' => 'user_id']) }}".replace('user_id', id);
                var data = {
                    _token: '{{ csrf_token() }}',
                    active: active ? 1 : 0
                };

                $.ajax({
                    type: "PATCH",
                    url: url,
                    data: data,
                    success: function(json) {
                        if (json.success === false || typeof json.success === 'undefined') {
                            fireToast("Failed updating data!\n" + json.message, 'danger');
                        }
                    },
                    dataType: "json",
                    beforeSend: function() {
                        button.prop('disabled', true).text(active ? 'Activating..' : 'Deactivating..');
                    },
                    complete: function() {
                        contributor_table.ajax.reload(null, false);
                    }
                }).fail(function() {
                    fireToast('Failed updating data!', 'danger');
                });
            }
        });
    </script>
@endsection
