@extends('layouts.base')

@section('judul', 'Management User')
@section('judul_konten', 'Halaman Management User')


@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">
        <div class="row g-5 g-xl-8">
            <div class="col-xxl-4">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body mb-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="#add-user" data-bs-toggle="modal" class="btn btn-lg btn-dark text-white"><i
                                        class="fas fa-plus"></i> Add User</a>
                            </div>
                            <div class="col-sm-12">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>NO.</th>
                                            <th>USERNAME</th>
                                            <th>NAME</th>
                                            <th>TYPE AKUN</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr class="text-center">
                                                <td scope="row">{{ $loop->iteration }}</td>
                                                <td>{{ $item->username }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @if ($item->auth_group == 1)
                                                        Super Admin
                                                    @elseif($item->auth_group == 2)
                                                        Admin
                                                    @else
                                                        User Gudang
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-info"
                                                        onclick="showUuser('{{ $item->id }}')"><i
                                                            class="fas fa-edit"></i> </a>
                                                    <a href="" class="btn btn-sm btn-danger"><i
                                                            class="fas fa-trash-alt"></i> </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('cycle-count/superadmin/post_user') }}" method="post" id="post_user">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control mt-4" name="username" id=""
                                        aria-describedby="helpId" placeholder="Masukan Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control mt-4" name="name" id=""
                                        aria-describedby="helpId" placeholder="Masukan Nama" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control mt-4" name="password" id=""
                                        aria-describedby="helpId" placeholder="Masukan Password" required>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <select class="form-control mt-4" name="auth_group" id="" required>
                                        <option value="" disabled selected>TYPE AKUN</option>
                                        <option value="1">Super Admin</option>
                                        <option value="2">Admin</option>
                                        <option value="3">User Gudang</option>
                                        <option value="4">Manager</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-lg btnSave"><i class="fas fa-save"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="show-user" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('cycle-count/superadmin/updateUser') }}" method="post" id="updateUser">
                    @csrf
                    <input type="hidden" name="id_user" class="idUserValue">
                    <div class="modal-body">
                        <div class="row appendUser">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-lg btnUpdate"><i class="fas fa-save"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function showUuser(id) {
            $.ajax({
                url: "{{ url('cycle-count/superadmin/showUser') }}/" + id,
                type: "GET",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function(response) {
                    $('#show-user').modal('show');
                    $('.idUserValue').val(response.data.id);
                    $('.appendUser').html("");
                    $('.appendUser').append(`
                      <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control mt-4" name="username" id=""
                                        aria-describedby="helpId" placeholder="Masukan Username" required value=${response.data.username}>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control mt-4" name="name" id=""
                                        aria-describedby="helpId" placeholder="Masukan Nama" required value="${response.data.name}">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <select class="form-control mt-4" name="auth_group" id="" required>
                                        <option value="${response.data.auth_group}" selected>${response.data.group_name}</option>
                                        <option value="1">Super Admin</option>
                                        <option value="2">Admin</option>
                                        <option value="3">User Gudang</option>
                                        <option value="4">Manager</option>
                                    </select>
                                </div>
                                <hr>
                                <a href="{{ url('cycle-count/superadmin/resetPassword/${response.data.id}') }}" class="btn btn-lg btn-dark mt-4"> Reset Password</a>
                            </div>
                    `);
                }
            });
        }

        $('#post_user').on('submit', function() {
            $('.btnSave').html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            $('.btnSave').attr('disabled', true);
        });

        $('#updateUser').on('submit', function() {
            $('.btnUpdate').html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            $('.btnUpdate').attr('disabled', true);
        });
    </script>
@endsection
