@extends('layouts.main')
@section('content')
    <div class="card shadow mb-4">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if(session('failed'))
            <div class="alert alert-danger">
                {{ session('failed') }}
            </div>
        @endif
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Data User
            </h6>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right ml-auto" data-toggle="modal" data-target="#addModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Nama Lengkap</td>
                            <td>Email</td>
                            <td>Role</td>
                            <td>Nomor Hp</td>
                            <td>Alamat</td>
                            <td>Tanggal Lahir</td>
                            <td>Gambar</td>
                            <td style="width:10%">Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->role_desc }}</td>
                                <td>{{ $user->no_hp }}</td>
                                <td>{{ $user->alamat }}</td>
                                <td>{{ $user->date_birth }}</td>
                                <td>
                                    @if ($user->image)
                                        <img src="{{ asset('image/users/'.$user->image) }}" width="70px">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success editbutton" data-id="{{ $user->id }}"><i class="fa fa-sm fa-edit text-white"></i></button>
                                    @if ($user->id === \Auth::user()->id)
                                        <button class="btn btn-sm btn-danger delbutton" data-url="{{ route('users.delete', $user->id) }}" disabled><i class="fa fa-sm fa-trash text-white"></i></button>
                                    @else
                                        <button class="btn btn-sm btn-danger delbutton" data-url="{{ route('users.delete', $user->id) }}"><i class="fa fa-sm fa-trash text-white"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
        $("table").DataTable({
            "ordering" : false
            });
        });
        $(document).on("click",".delbutton",function(){
        let url = $(this).attr('data-url');
        let confirmation = confirm("Yakin ingin menghapus data ini ?");
            if(confirmation){
                window.location.href = url;
            }else{

            }
        });
        $(document).on("click",".editbutton",function(){
            let data_id = $(this).attr('data-id');
            $("#image").fadeOut();
            $("#image_text").fadeOut();
            $.get('/users/getData/'+data_id,function(data){
                let image = data.image;
                if (image == null) {
                    $("#image_text").fadeIn();
                } else {
                    $("#image").attr("src", '/image/users/' + image);

                    $("#image").fadeIn();
                }

                $("#id").val(data.id);
                $("#full_name").val(data.full_name);
                $("#email,#email_old").val(data.email);
                $("#password").val(data.password);
                $("#date_birth").val(data.date_birth);
                $("#no_hp").val(data.no_hp);
                $("#alamat").val(data.alamat);
                $("#role_id").val(data.role_id);
                $("#editModal").modal('show');
            });
        });
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('users.add') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="full_name">Nama Lengkap</label>
                                    <input type="text" name="full_name" class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="image">Foto</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="date_birth">Ulang Tahun</label>
                                    <input type="date" name="date_birth" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">Nomor Hp</label>
                                    <input type="text" name="no_hp" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role_id" class="form-control" required>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->role_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('users.edit') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <input type="hidden" value="" id="id" name="id_old">
                    <input type="hidden" value="" id="password" name="password_old">
                    <input type="hidden" value="" id="email_old" name="email_old">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Perubahan Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="full_name">Nama Lengkap</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" name="new_password" class="form-control">
                                    <span style="font-size: 10px;">Kosongkan jika tidak ingin mengubah password</span>
                                </div>
                                <div class="form-group">
                                    <label for="image">Foto</label>
                                    <hr>
                                    <div style="text-align:center">
                                        <p id="image_text" >No Image</p>
                                        <img id="image" src="" width="125px">
                                    </div>
                                    <hr>
                                    <input type="file" name="image" class="form-control">
                                    <span style="font-size: 10px;">Kosongkan jika tidak ingin mengubah Foto</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="date_birth">Ulang Tahun</label>
                                    <input type="date" name="date_birth" id="date_birth" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">Nomor Hp</label>
                                    <input type="text" name="no_hp" id="no_hp" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role_id" id="role_id" class="form-control" required>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->role_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection