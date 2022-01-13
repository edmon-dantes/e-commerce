<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class CartController extends Controller
{
    const MODEL_WITH = [];

    protected $cart;

    public function __construct()
    {
        $instance = match (request()->input('instance')) {
            'cart' => 'cartstorage',
            'wishlist' => 'wishliststorage',
            'compare' => 'comparestorage',
            default => 'cartstorage'
        };

        $token = (request()->query('token') ? request()->query('token') : 'null');
        $this->cart = app($instance)->session($token);

    }

    public function index(Request $request)
    {
        $additional = ['collections' => []];

        return (new CartCollection($this->cart))->additional($additional);
    }

    public function store(CartRequest $request)
    {
        $product = Product::findOrFail($request->input('data.product.id'));

        DB::beginTransaction();
        try {
            $price = $product->price;
            $size = $request->input('data.size');
            $quantity = $request->input('data.quantity');
            $discount = $product->discount;
            if (!!$attribute = $product->attributes()->where(['size' => $size])->first()) {
                $price = $attribute->price;
            }

            $discountCondition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'DISCOUNT',
                'type' => 'discount',
                'value' => $discount * -1,
            ));

            $this->cart->add(array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => array(['size' => $size]),
                'associatedModel' => $product,
                'conditions' => [$discountCondition]
            ));

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new CartResource($this->cart->get($product->id)))->additional($additional);
    }

    public function update(CartRequest $request, $cart_item)
    {
        $product = Product::findOrFail($cart_item);

        if (!$this->cart->has($product->id)) {
            throw new NotFoundHttpException('Product does not exist.');
        }

        DB::beginTransaction();
        try {

            $price = $product->price;
            $size = $request->input('size');
            $quantity = $request->input('quantity');
            if (!!$attribute = $product->attributes()->where(['size' => $size])->first()) {
                $price = $attribute->price;
            }

            $this->cart->update($product->id, [
                'name' => $product->name,
                'price' => $price,
                'quantity' => ['relative' => false, 'value' => $quantity],
                'attributes' => array(['size' => $size]),
                'associatedModel' => $product
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new CartResource($this->cart->get($product->id)))->additional($additional);
    }

    public function destroy(CartRequest $request, $cart_item)
    {
        if (!$cart_product = $this->cart->get($cart_item)) {
            throw new NotFoundHttpException('Product does not exist.');
        }

        DB::beginTransaction();
        try {

            $this->cart->remove($cart_item);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new CartResource($cart_product))->additional($additional);
    }
}
