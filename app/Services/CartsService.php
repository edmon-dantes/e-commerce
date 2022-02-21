<?php

namespace App\Services;

use App\Http\Requests\CartRequest;
use App\Models\Product;
use Darryldecode\Cart\ItemCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class CartsService
{
    public function store(CartRequest $request, $cart, $product_id): ItemCollection
    {
        $product = Product::findOrFail($product_id);

        DB::beginTransaction();
        try {

            $price = $product->price;
            $size = $request->input('data.size');
            $quantity = $request->input('data.quantity', 1);
            $discount = $product->discount;

            if (!!$attribute = $product->attributes()->where(['size' => $size])->first()) {
                $price = $attribute->price;
            }

            $discountCondition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'DISCOUNT',
                'type' => 'discount',
                'value' => $discount * -1,
            ));

            $cart->add(array(
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

        return $cart->get($product_id);
    }

    public function update(CartRequest $request, $cart, $product_id): ItemCollection
    {
        $product = Product::findOrFail($product_id);

        if (!$cart->has($product->id)) {
            throw new NotFoundHttpException('Product does not exist.');
        }

        DB::beginTransaction();
        try {

            $price = $product->price;
            $size = $request->input('data.size');
            $quantity = $request->input('data.quantity', 1);

            if (!!$attribute = $product->attributes()->where(['size' => $size])->first()) {
                $price = $attribute->price;
            }

            $cart->update($product->id, [
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

        return $cart->get($product_id);
    }

    public function destroy($cart, $product_id): ItemCollection
    {
        if (!$cartItem = $cart->get($product_id)) {
            throw new NotFoundHttpException('Product does not exist.');
        }

        DB::beginTransaction();
        try {

            $cart->remove($product_id);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $cartItem;
    }

    public function destroy_all($cart)
    {
        DB::beginTransaction();
        try {

            $cart->clear();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $cart;
    }
}
