<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransactionDetail extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'purchase_transaction_id',
        'product_id',
        'price_per_qty',
        'qty',
        'price'
    ];

    /**
     * transaction
     *
     * @return void
     */
    public function purchase_transaction()
    {
        return $this->belongsTo(PurchaseTransaction::class);
    }

    /**
     * product
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
