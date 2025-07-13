<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'unit_of_measurement_id' => (int) $row['unit_of_measurement_id'],
            'barcode' => $row['barcode'],
            'title' => $row['title'],
            'buy_price' => (int) $row['buy_price'],
            'sell_price' => (int) $row['sell_price'],
            'stock' => (int) $row['stock'],
            'stock_minimal' => (int) $row['stock_minimal']
        ]);
    }

    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'barcode' => 'unique:products,barcode',
        ];
    }
}
