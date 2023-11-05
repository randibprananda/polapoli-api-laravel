<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTimAddon extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'order_tim_id',
        'title',
        'price',
        'periode',
        'description',
    ];

    public function orderTim()
    {
        return $this->belongsTo(OrderTim::class, 'order_tim_id');
    }
}
