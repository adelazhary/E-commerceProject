<?php
use app\services\CartService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

function addToCart(int $productId): array
{
    // get data from session (this equals Session::get(), use empty array as default)
    $shoppingCart = session('shoppingCart', []);

    if (isset($shoppingCart[$productId]))
    {
        // product is already in shopping cart, increment the amount
        $shoppingCart[$productId]['amount'] += 1;
    }
    else
    {
        // fetch the product and add 1 to the shopping cart
        $product = Product::findOrFail($productId);
        $shoppingCart[$productId] = [
            'productId' => $productId,
            'amount'    => 1,
            'price'     => $product->price->getAmount(),
            'name'      => $product->name,
            'discount'  => $product->discount
        ];
    }

    // update the session data (this equals Session::put() )
    session(['shoppingCart' => $shoppingCart]);
    return $shoppingCart;
}

