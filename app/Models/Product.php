<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_of_measurement_id',
        'barcode',
        'title',
        'description',
        'buy_price',
        'sell_price',
        'stock',
        'stock_minimal'
    ];

    /**
     * unit_of_measurement
     *
     * @return void
     */
    public function unit_of_measurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }


    /**
     * purchase_transaction_details
     *
     * @return void
     */
    public function purchase_transaction_details()
    {
        return $this->hasMany(PurchaseTransactionDetail::class);
    }

    /**
     * transaction_details
     *
     * @return void
     */
    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

}
