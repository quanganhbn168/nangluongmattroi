<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;

    // Tên bảng nếu bạn giữ là 'order_details'
    // protected $table = 'order_details'; 

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'subtotal',
        'warranty_expires_at',
    ];

    /**
     * Lấy thông tin sản phẩm của mục này.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Lấy thông tin đơn hàng chứa mục này.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
