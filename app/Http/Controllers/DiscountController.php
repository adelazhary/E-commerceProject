<?php

namespace App\Http\Controllers;

use App\Models\discount;
use App\Http\Requests\StorediscountRequest;
use App\Http\Requests\UpdatediscountRequest;

class DiscountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('discounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorediscountRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatediscountRequest $request, discount $discount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(discount $discount)
    {
        //
    }
}
