@extends('layouts.ecommerce')
@section('title')
    <title>Jual Product Fashion - MAG</title>
@endsection
@section('content')
    {{-- home banner area --}}
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content text-center">
                    <h2>Jual Produk Fashion</h2>
                    <div class="page_link">
                        <a href="{{route('front.index')}}">Home</a>
                        <a href="{{route('front.product')}}">Product</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- end home banner area --}}

    {{-- category product area --}}
    <section class="cat_product_area section_gap">
        <div class="container-fluid">
            <div class="row flex-row-reserve">
                <div class="col-lg-9">
                    <div class="product_top_bar">
                        <div class="left_dorp">
                            <select class="sorting">
                                <option value="1">Default Sorting</option>
                                <option value="2">Default Sorting2</option>
                                <option value="4">Default Sorting4</option>
                            </select>
                            <select class="show">
                                <option value="4">Show 12</option>
                                <option value="4">Show 14</option>
                                <option value="4">Show 16</option>
                            </select>
                        </div>
                        <div class="right_page ml-auto">
                            {{$products->links()}}
                        </div>
                    </div>
                    <div class="latest_product_inner row">
                        {{-- proses looping data product sama dengan yang ada dihalaman home --}}
                        @forelse ($products as $row)
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="f_p_item">
                                    <div class="f_p_img">
                                        <img class="img-fluid" src="{{asset('storage/products/'.$row->image)}}" alt="{{$row->name}}">
                                        <div class="p_icon">
                                            <a href="{{url('/product/'.$row->slug)}}">
                                            <i class="lnr lnr-cart"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <a href="{{url('/product/'.$row->slug)}}">
                                    <h4>{{$row->name}}</h4>
                                    </a>
                                    <h5>Rp {{number_format($row->price)}}</h5>
                                </div>
                            </div>
                        @empty
                        <div class="col-md-12">
                            <h3 class="text-center">Tidak ada Product</h3>
                        </div>
                        @endforelse
                        {{-- end proses looping product --}}
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="left_sidebar_area">
                        <aside class="left_widgets cat_widgets">
                            <div class="l_w_title">
                                <h3>Kategori Produk</h3>
                            </div>
                            <div class="widget_inner">
                                <ul class="list">
                                    {{-- proses looping data kategori --}}
                                    @foreach ($categories as $category)
                                        <li>
                                            {{-- jika childnya ada,maka kategori ini akan mengexpand data daribawahnya --}}
                                            <strong><a href="{{url('/category/'.$category->slug)}}">{{$category->name}}</a></strong>
                                            {{-- proses looping data child kategori --}}
                                            @foreach ($category->child as $child)
                                                <ul class="list" style="display:block">
                                                    <li>
                                                        <a href="{{url('/category/'.$child->slug)}}">{{$child->name}}</a>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
            <div class="row">
                {{$products->links()}}
            </div>
        </div>
    </section>
    {{-- end category product area --}}
@endsection
