<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOnCMS extends Model
{
    use HasFactory, FormatDates;
    protected $connection = 'mysql_cms';
    protected $table = 'add_ons';

    protected $fillable = [
        "user_id",
        "title",
        "price",
        "periode",
        "status",
        "description",
    ];
}
