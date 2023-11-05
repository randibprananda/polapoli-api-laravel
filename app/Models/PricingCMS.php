<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingCMS extends Model
{
    use HasFactory, FormatDates;
    protected $connection = 'mysql_cms';
    protected $table = 'pricings';

    protected $fillable = [
        "title",
        "price",
        "duration",
        "feature",
        "status",
        "user_id",
    ];
}
