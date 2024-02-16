<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class couponController extends Controller
{
    public function index()
    {
        return view('coupon');
    }
    public function store(Request $request)
    {
        $coupon = $request->coupon;
        $coupon = \App\Models\coupons::where('code', $coupon)->first();
        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code. Please try again.');
        }
        session()->put('coupon', [
            'code' => $coupon->code,
            'value' => $coupon->value,
            'type' => $coupon->type,
            'percentage_off' => $coupon->percentage_off,
        ]);
        return back()->with('success', 'Coupon has been applied.');
    }
    public function discount($total)
    {
        if (!session()->has('coupon')) {
            return $total;
        }
        $coupon = session()->get('coupon');
        if ($coupon['type'] == 'fixed') {
            return $total - $coupon['value'];
        } elseif ($coupon['type'] == 'percentage') {
            return $total - ($total * ($coupon['percentage_off'] / 100));
        }
        else {
            return $total - ($total * ($coupon['percentage_off'] / 100));
        }
    }
    public function destroy()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon has been removed.');
    }
}
