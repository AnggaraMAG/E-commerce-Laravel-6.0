<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Province;
use App\City;
use App\District;
use DB;
use App\Customer;
use App\Order;
use App\OrderDetail;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getCarts()
    {
        $carts = json_decode(request()->cookie('mag-carts'), true);
        $carts = $carts != '' ? $carts : [];
        return $carts;
    }

    public function addToCart(Request $request)
    {
        //validasi yang dikirim
        $this->validate($request, [
            'product_id' => 'required|exists:products,id', //memastikan product idnya ada
            'qty' => 'required|integer',
        ]);

        //ambil data cart
        $carts = $this->getCarts();

        //cek jika carts tidak null dan product_id ada didalam array carts
        if ($carts && array_key_exists($request->product_id, $carts)) {
            //maka update qty-nya berdasarkan product_id yang dijadikan key array
            $carts[$request->product_id]['qty'] += $request->qty;
        } else {
            //selain itu ,buat query untuk mengambil product berdasarkan product_id
            $product = Product::findOrFail($request->product_id);
            //tambahkan data baru dengan menjadi product_id sebagai key dari array carts
            $carts[$request->product_id] = [
                'qty' => $request->qty,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => $product->image,
            ];
        }
        //buat cookienya dengan nama mag-carts
        //jangan lupa untuk diencode kembali,dan limitnya 2880menit atau 48 jam
        $cookie = cookie('mag-carts', json_encode($carts), 2880);
        //store ke browser untuk disimpan
        return redirect()->back()->cookie($cookie);
    }

    public function listCart()
    {
        //mengambil data cookie
        $carts = $this->getCarts();
        //ubah array menjadi collection,kemudian gunakan method sum untuk menghitung subtotal
        $subtotal = collect($carts)->sum(function ($q) {
            return $q['qty'] * $q['product_price']; //subtotal terdiri dari qty * price
        });
        //load view cart cart.blade.php dan passing data carts dan subtotal
        return view('ecommerce.cart', compact('carts', 'subtotal'));
    }

    public function updateCart(Request $request)
    {
        //ambil data dari cookie
        $carts = $this->getCarts();
        //kemudian looping data product_id karena namenya array pada view sebelumnya  maka data yanng diterima adalah array agar bisa dilooping
        foreach ($request->product_id as $key => $row) {
            //dicheck jika qty dengan key yang sama dengan product_id = 0
            if ($request->qty[$key] == 0) {
                //maka data tersebut dihapus dari array
                unset($carts[$row]);
            } else {
                //selain itu maka akan diperbarui
                $carts[$row]['qty'] = $request->qty[$key];
            }
        }
        //set kembali cookienya seperti sebelumnya
        $cookie = cookie('mag-carts', json_encode($carts), 2880);
        //dan store ke browser
        return redirect()->back()->cookie($cookie);
    }



    public function checkout()
    {
        //query untuk mengambil data provinsi
        $provinces = Province::orderBy('created_at', 'DESC')->get();

        $carts = $this->getCarts(); //mengambil data cart
        //menghitung sub total dari keranjang belanja (cart)
        $subtotal = collect($carts)->sum(function ($q) {
            return $q['qty'] * $q['product_price'];
        });
        //meload view checkout.blade.php dan passing data province , carts dan subtotal
        return view('ecommerce.checkout', compact('provinces', 'carts', 'subtotal'));
    }

    public function getCity()
    {
        $cities = City::where('province_id', request()->province_id)->get();
        return response()->json(['status' => 'success', 'data' => $cities]);
    }
    public function getDistrict()
    {
        $districts = District::where('city_id', request()->city_id)->get();
        return response()->json(['status' => 'success', 'data' => $districts]);
    }

    public function processCheckout(Request $request)
    {
        //
        $this->validate($request, [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required',
            'email' => 'required|email',
            'customer_address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
        ]);

        //inisiasi database transaction
        //database transaction berfungsi untuk memastikan semua proses sukses untuk kemudian  dicommit agar data benar-benar disimpan ,jika terjadi error maka kita rollback agar datanya selaras
        DB::beginTransaction();
        try {
            //check data customer berdasarkan email
            $customer = Customer::where('email', $request->email)->first();
            //jika dia tidak login dan data customernya ada
            if (!auth()->check() && $customer) {
                //maka redirect dan tampilkan intruksi untuk login
                return redirect()->back()->with(['error' => 'Silahkan login terlebih dahulu']);
            }

            //ambil data keranjang
            $carts = $this->getCarts();
            //hitung subtotal belanjaan
            $subtotal = collect($carts)->sum(function($q){
                return $q['qty'] * $q['product_price'];
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
