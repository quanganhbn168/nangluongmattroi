<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'technician_id',
        'status',
        'installation_date',
        'note',
        'total_price',
    ];

    /**
     * Lấy thông tin khách hàng của đơn hàng này.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lấy thông tin người thợ được gán cho đơn hàng này.
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Lấy tất cả các sản phẩm trong đơn hàng này.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}