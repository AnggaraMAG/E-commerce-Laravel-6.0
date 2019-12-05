@extends('layouts.admin')
@section('title')
    <title>Mass Upload</title>
@endsection
@section('content')
        <main class="main">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Product</li>
            </ol>
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <form action="{{route('product.saveBulk')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        @if (session('succes'))
                                            <div class="alert alert-success">{{session('success')}}</div>
                                        @endif

                                        <div class="form-group">
                                            <label for="category_id">Kategori</label>
                                            <select name="category_id" id="" class="form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($category as $row)
                                                    <option value="{{$row->id}}" {{old('category_id') == $row->id?'selected':''}}>{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">{{$errors->first('category_id')}}</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="file">File Excel</label>
                                            <input type="file" class="form-control" name="file" value="{{old('file')}}" required>
                                            <p class="text-danger">{{$errors->first('file')}}</p>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
@endsection
