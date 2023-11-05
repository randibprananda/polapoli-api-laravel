<?php

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Models\OrderTim;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getAll()
    {
        $data = OrderTim::with('timRelawan','orderTimAddon', 'user')->orderBy('updated_at', 'desc')->get();
        return response()->json([
            'message' => 'List All Order',
            'data' => $data
        ])->setStatusCode(200);
    }
}
