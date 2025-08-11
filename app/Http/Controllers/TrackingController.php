<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $phone = $request->input('phone');
        $user = null;

        if ($phone) {
            $user = User::where('phone', $phone)
                        ->with('orders.items.product')
                        ->first();
        }

        return view('frontend.tracking.index', [
            'user' => $user,
            'phone_searched' => $phone, 
        ]);
    }
}