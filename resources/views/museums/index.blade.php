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
                Data Museum
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
                            <td>Nama Museum</td>
                            {{-- <td>Deskripsi Museum</td> --}}
                            <td>Gambar Museum</td>
                            <td>Poin Museum</td>
                            <td style="width:10%">Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($museums as $museum)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $museum->museum_name }}</td>
                                {{-- <td>{!! str_limit($museum->museum_desc, $limit = 50, $end = '...') !!}</td> --}}
                                <td>
                                    @if ($museum->museum_image)
                                        <img src="{{ asset('image/museums/'.$museum->museum_image) }}" width="70px">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $museum->museum_rating ? $museum->museum_rating : "Tidak ada Poin" }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success editbutton" data-id="{{ $museum->id }}"><i class="fa fa-sm fa-edit text-white"></i></button>
                                    <button class="btn btn-sm btn-danger delbutton" data-url="{{ route('museums.delete', $museum->id) }}"><i class="fa fa-sm fa-trash text-white"></i></button>
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
        $(document).ready(function() {
            $('#museum_desc').cleditor();
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
            $.get('/museums/getData/'+data_id,function(data){
                let image = data.museum_image;
                if (image == null) {
                    $("#image_text").fadeIn();
                } else {
                    $("#image").attr("src", '/image/museums/' + image);

                    $("#image").fadeIn();
                }

                $("#id").val(data.id);
                $("#museum_name").val(data.museum_name);
                $("#museum_desc").val(data.museum_desc);
                $("#museum_rating").val(data.museum_rating);
                $("#editModal").modal('show');
            });
        });
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('museums.add') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="museum_name">Nama Museum</label>
                            <input type="text" name="museum_name" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <label for="museum_desc">Deskripsi Museum</label>
                            <textarea name="museum_desc" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="museum_image">Gambar Museum</label>
                            <input type="file" name="museum_image" class="form-control">
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
            <form method="POST" action="{{ route('museums.edit') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <input type="hidden" value="" id="id" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Perubahan Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="museum_name">Nama Museum</label>
                            <input type="text" name="museum_name" id="museum_name" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <label for="museum_desc">Deskripsi Museum</label>
                            <textarea name="museum_desc" id="museum_desc" class="form-control"></textarea>
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
                        <div class="form-group">
                            <label for="rating">Rating Museum</label>
                            <input type="text" name="rating" id="rating" class="form-control" disabled>
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