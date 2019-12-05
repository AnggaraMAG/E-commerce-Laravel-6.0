<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\ProductImport; //import class productimport yang akan menghandle file excel
use Illuminate\Support\Str;
use App\Product;
use File;

class ProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filename;
    protected $category;

    /**
     * Create a new job instance.
     *
     *
     * @return void
     */
    public function __construct($category, $filename)
    {
        $this->category = $category;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //kemudian kita handle gunakan productimport yang merupakan class yang akan dibuat selanjutnya
        //import data excel tadi yang sudah disimpan distorage kemudian convert menjadi array
        $files = (new ProductImport)->toArray(storage_path('app/public/uploads/' . $this->filename));

        //kemudian looping datanya
        foreach ($files[0] as $row) {
            //if (row[4])   != '' filter_var($row[4]), FILTER_VALIDATE_URL)){
            //formatting urlnya  untuk mengambil file-namenya beserta extension
            //jadi pastikan pada template mass uploadnya nanti pada bagian url
            //harus diakhiri dengan nama fileyang lengkap dengan extention

            $explodeURL = explode('/', $row[4]);
            $explodeExtension = explode('.', end($explodeURL));
            $filename = time() . Str::random(6) . '.' . end($explodeExtension);

            //download gambar tersebut dari URL
            file_put_contents(storage_path('app/public/products') . '/' . $filename, file_get_contents($row[4]));

            //kemudian simpan datanya didatabase

            Product::create([
                'name' => $row[0],
                'slug' => $row[0],
                'category_id' => $this->category,
                'description' => $row[1],
                'price' => $row[2],
                'weight' => $row[3],
                'image' => $filename,
                'status' => true,
            ]);
            //}
        }
        //jika prosesnya sudah selesai maka file yang distorage akan dihapus
        // File::delete(storage_path('app/public/uploads/' . $this->filename));
    }
}
