@extends('layouts.ecommerce')
@section('title')
    <title>MAG Ecomerce Belanja Online Murah</title>
@endsection
@section('content')
{{-- home banner area --}}
    <section class="home_banner_area">
        <div class="overlay"></div>
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content row">
                    <div class="offset-lg-2 col-lg-8">
                        <h3>Fashion For
                            <br>Upcoming Winter
                        </h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, mollitia aspernatur vitae libero inventore nemo officia amet, fugit deserunt possimus, corrupti perspiciatis non eius repellendus distinctio repellat dicta eos pariatur!</p>
                        <a href="#" class="white_bg_btn">View Collection</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- end home banner area --}}

{{-- hot deals area --}}
<section class="hot_deals_area section_gap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="hot_deal_box">
                    <img src="{{asset('ecommerce/img/product/hot_deals/deal1.jpg')}}" alt="" class="img-fluid">
                    <div class="content">
                        <h2>Hot Deals of this Mont</h2>
                        <p>Shop Now!</p>
                    </div>
                    <a href="#" class="hot_deal_link"></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hot_deal_box">
                    <img src="{{asset('ecommerce/img/product/hot_deals/deal1.jpg')}}" alt="" class="img-fluid">
                    <div class="content">
                        <h2>Hot Deals of this Mont</h2>
                        <p>Shop Now!</p>
                    </div>
                    <a href="#" class="hot_deal_link"></a>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- end hot deals area --}}

{{-- feature product area --}}
<section class="feature_product_area section_gap">
    <div class="main_box">
        <div class="container-fluid">
            <div class="row">
                <div class="main_title">
                    <h2>Product Terbaru</h2>
                    <p>Tampilan trendi dengan kumpulan produk kekinian kami.</p>
                </div>
            </div>
            <div class="row">
                {{-- looping data product  --}}
                @forelse ($products as $row)
                    <div class="col col1">
                        <div class="f_p_item">
                            <div class="f_p_img">
                                {{-- menampilkan image dari folder public/storage/product --}}
                                <img width="200px" height="200px" src="{{asset('storage/products/'.$row->image)}}" alt="{{$row->image}}">
                                <div class="p_icon">
                                    <a href="{{url('/product/'.$row->slug)}}">
                                    <i class="lnr lnr-cart"></i>
                                    </a>
                                </div>
                            </div>
                            {{-- ketika product diklik show  --}}
                            <a href="{{url('/product/'.$row->slug)}}">
                                <h4>{{$row->name}}</h4>
                            </a>
                            {{-- tampilan harga product --}}
                            <h5>Rp {{number_format($row->price)}}</h5>
                        </div>
                    </div>
                @empty

                @endforelse
            </div>
            <div class="row">
                    {{$products->links()}}
            </div>
        </div>
    </div>
</section>
{{-- end feature product area --}}
@endsection
