<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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
        'warranty_months',
        'warranty_expires_at',
    ];
    protected $casts = [
        'product_price'       => 'decimal:2',
        'subtotal'            => 'decimal:2',
        'warranty_expires_at' => 'date',
    ];
    protected $appends = ['warranty_remaining_text'];
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

    public function getWarrantyRemainingTextAttribute(): string
    {
        if (!$this->warranty_expires_at) return 'Không áp dụng';

        $now = Carbon::now();
        $exp = $this->warranty_expires_at instanceof Carbon ? $this->warranty_expires_at : Carbon::parse($this->warranty_expires_at);

        if ($now->isSameDay($exp)) return 'Hết hạn hôm nay';

        if ($now->greaterThan($exp)) {
            $int = $exp->diff($now);
            $parts = [];
            if ($int->y) $parts[] = $int->y.' năm';
            if ($int->m) $parts[] = $int->m.' tháng';
            if ($int->d) $parts[] = $int->d.' ngày';
            return 'Đã hết hạn ' . implode(' ', $parts) . ' trước';
        }

        $int = $now->diff($exp);
        $parts = [];
        if ($int->y) $parts[] = $int->y.' năm';
        if ($int->m) $parts[] = $int->m.' tháng';
        if ($int->d) $parts[] = $int->d.' ngày';
        return 'Còn ' . implode(' ', $parts);
    }

        // Hạn bảo hành đã format cho view
    public function getWarrantyExpiresAtForViewAttribute(): string
    {
        return $this->warranty_expires_at ? $this->warranty_expires_at->format('d/m/Y') : '—';
    }

        // "X tháng" hoặc "—"
    public function getWarrantyMonthsTextAttribute(): string
    {
        return $this->warranty_months ? ($this->warranty_months . ' tháng') : '—';
    }
}
