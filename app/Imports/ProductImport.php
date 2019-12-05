<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductImport implements WithStartRow, WithChunkReading
{
    /**
     * @param Collection $collection
     */
    use Importable;

    //jadi kita batas data yang akan digunakan mulai dari baris kedua ,karena baris pertama digunakan sebagai heading agar memudahkan orang yang mengisi  data pada file excel
    public function startRow(): int
    {
        return 2;
    }

    //kemudian  kita gunakan chunksize untuk mengontrol penggunakan memory dengan membatasi load data dalam sekali proses
    public function chunkSize(): int
    {
        return 100;
    }
}
