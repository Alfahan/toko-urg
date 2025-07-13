<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostTransactionDetail extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'cost_transaction_id',
        'name_cost',
        'price_per_qty',
        'qty',
        'price'
    ];

    /**
     * transaction
     *
     * @return void
     */
    public function cost_transaction()
    {
        return $this->belongsTo(CostTransaction::class);
    }

}
