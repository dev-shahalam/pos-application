<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInvoice extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','invoice_id','user_id','qty','sale_price'];

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }

}
