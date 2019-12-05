@extends('layouts.admin')

@section('title')
    <title>Manajemen Category</title>
@endsection

@section('content')
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Kategori</li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    {{-- handle form input  new kategori --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Kategori Baru</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{route('category.store')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Kategori</label>
                                    <input type="text" name="name" class="form-control" required>
                                    <p class="text-danger">{{$errors->first('name')}}</p>
                                </div>
                                <div class="form-group">
                                    <label for="parent_id">Kategori</label>
                                    <select name="parent_id" id="" class="form-control">
                                        <option value="">None</option>
                                        @foreach ($parent as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{$errors->first('name')}}</p>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm">Tambah</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- end new category --}}

                    {{-- handle table list category --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">List Kategory</h4>
                            </div>
                            <div class="card-body">
                                {{-- ketika session sukses tampilkan pesan berhasil  --}}
                                @if (session('success'))
                                <div class="alert alert-success">{{session('success')}}</div>
                                @endif
                                {{-- ketika session gagal tampilkan pesan error --}}
                                @if (session('error'))
                                <div class="alert alert-danger">{{session('error')}}</div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kategory</th>
                                                <th>Parent</th>
                                                <th>Created</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <tr>

                                            @forelse ($category as $val)
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$val->name}}</td>
                                            <td>{{$val->parent?$val->parent->name:'-'}}</td>
                                            <td>{{$val->created_at->format('d-m-y')}}</td>
                                            <td>
                                                <form action="{{route('category.destroy',$val->id)}}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{route('category.edit',$val->id)}}" class="btn btn-warning btn-sm">Edit</a>
                                                        <button class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada Data</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    {!!$category->links()!!}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end handle table list --}}
                </div>
            </div>
        </div>
    </main>
@endsection
