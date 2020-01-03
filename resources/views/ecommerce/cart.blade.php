@extends('layouts.ecommerce')
@section('title')
    <title>Keranjang Belanja - MAG Ecommerce</title>
@endsection

@section('content')
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content text-center">
                    <h2>Keranjang Belanja</h2>
                    <div class="page_link">
                        <a href="{{url('/')}}">Home</a>
                        <a href="{{route('front.list.cart')}}">Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <form action="{{route('front.update.cart')}}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- looping data dari variable carts --}}
                                @forelse ($carts as $row)
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="{{asset('storage/products/'.$row['product_image'])}}" alt="{{$row['product_name']}}" width="100px" height="100px">
                                                </div>
                                                <div class="media-body">
                                                    <p>{{$row['product_name']}}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>Rp {{number_format($row['product_price'])}}</h5>
                                        </td>
                                        <td>
                                            <div class="product_count">
                                                {{-- perhatikan bagian ini namenya kita beri array agar bisa menyimpan lebih dari satu data --}}
                                            <input type="text" name="qty[]" id="sst{{$row['product_id']}}" maxlength="12" value="{{$row['qty']}}" title="Quantity" class="input-text-qty">
                                            <input type="hidden" name="product_id[]" value="{{$row['product_id']}}" class="form-control">
                                            <button onclick="var result = document.getElementById('sst{{ $row['product_id'] }}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
										 class="increase items-count" type="button">
											<i class="lnr lnr-chevron-up"></i>
										</button>
										<button onclick="var result = document.getElementById('sst{{ $row['product_id'] }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
										 class="reduced items-count" type="button">
											<i class="lnr lnr-chevron-down"></i>
										</button>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>Rp {{number_format($row['product_price'] * $row['qty'])}}</h5>
                                        </td>
                                        <td>
                                            <button href="#" type="submit" class="btn btn-danger btn-sm">X</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Tidak ada Belanjaan!</td>
                                    </tr>
                                @endforelse
                                <tr class="bottom_button">
                                    <td>
                                        <button class="gray_btn">Update Cart</button>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </form>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Sub Total</h5>
                                </td>
                                <td>
                                    <h5>Rp {{number_format($subtotal)}}</h5>
                                </td>
                            </tr>
                                <tr class="shipping_area">
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <h5>Shipping</h5>
                                    </td>
                                    <td>
                                        <div class="shipping_box">
                                            <ul class="list">
                                                <li>
                                                    <a href="#">Flat Rate : $5.00</a>
                                                </li>
                                                <li>
                                                    <a href="#">Free Shipping</a>
                                                </li>
                                                <li>
                                                    <a href="#">Flat Rate : $10.00</a>
                                                </li>
                                                <li>
                                                    <a href="#">Local Delivery: $2.00</a>
                                                </li>
                                            </ul>
                                            <h6>Calculate Shipping
                                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                                            </h6>
                                            <select class="shipping_select">
                                                <option value="1">Sumatera Utara</option>
                                                <option value="2">Sumatera Barat</option>
                                                <option value="4">Sumatera Selatan</option>
                                            </select>
                                            <select class="shipping_select">
                                                <option value="1">Select a State</option>
                                                <option value="2">Select a State</option>
                                                <option value="4">Select a State</option>
                                            </select>
                                            <input type="text" placeholder="Postcode/Zipcode">
                                            <a class="gray_btn">Update Detail</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="out_button_area">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div class="checkout_btn_inner">
                                            <a class="gray_btn float-right" href="">Continue Shipping</a>
                                            <a class="main_btn float-right" href="{{route('front.checkout')}}">Proceed to Checkout</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </section>
@endsection
