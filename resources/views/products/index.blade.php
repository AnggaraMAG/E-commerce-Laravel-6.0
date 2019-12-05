@extends('layouts.admin')
@section('title')
    <title>Manajemen Products</title>
@endsection
@section('content')
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    List Product
                                    <a href="{{route('product.create')}}" class="btn btn-primary btn-sm float-right">Tambah</a>
                                    <a href="{{route('product.bulk')}}" class="btn btn-danger btn-sm float-right">Mass Upload</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                {{-- flash session --}}
                                @if (session('success'))
                                    <div class="alert alert-success">{{session('success')}}</div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger">{{session('error')}}</div>
                                @endif
                                {{-- end flash session --}}

                                <form action="{{route('product.index')}}" method="GET">
                                    <div class="input-group mb-3 col-md-3 float-right">
                                        <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{request()->q}}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-reddit">Cari</button>
                                        </div>
                                    </div>
                                </form>

                                {{-- table penampilkan products --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Harga</th>
                                                <th>Created_At</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($product as $row)
                                                <tr>
                                                    <td>
                                                        {{-- gambar dari folder public/storage/products --}}
                                                        <img src="{{asset('storage/products/' .$row->image)}}" alt="{{$row->name}}" width="100" height="100">
                                                    </td>
                                                    <td>
                                                        <strong>{{$row->name}}</strong><br>
                                                        <label for="">Kategori : <span class="badge badge-info">{{$row->category->name}}</span></label>
                                                        <label for="">Berat : <span class="badge badge-info">{{$row->weight}} gr</span></label>
                                                    </td>
                                                    <td> Rp. {{number_format($row->price)}}</td>
                                                    <td>{{$row->created_at->format('d-m-Y')}}</td>
                                                    <td>{!!$row->status_label!!}</td>
                                                    <td>
                                                        <form action="{{route('product.destroy',$row->id)}}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="{{route('product.edit',$row->id)}}" class="btn btn-warning btn-sm">Edit</a>
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
                                    {{-- pagination --}}
                                    <div>
                                        {!!$product->links()!!}
                                    </div>
                                    {{-- end paginate --}}
                                </div>
                                {{-- end products --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
