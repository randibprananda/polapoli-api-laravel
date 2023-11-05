<?php

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Models\TimRelawan;
use Illuminate\Http\Request;

class TimController extends Controller
{
    public function getAll()
    {
        $data = TimRelawan::with('orderTim','orderTim.orderTimAddon', 'users', 'detailTimRelawan.pm')->orderBy('updated_at', 'desc')->get();
        return response()->json([
            'message' => 'List All Tim Relawan',
            'data' => $data
        ])->setStatusCode(200);
    }
}
