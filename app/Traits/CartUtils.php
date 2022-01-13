<?php

namespace App\Traits;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait CartUtils
{
    protected function _store($cart, $product, $quantity)
    {
        $cart->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->sale_price,
            'quantity' => $quantity,
            'attributes' => array(),
            'conditions' => $this->addConditionsItem($cart, $product),
            'associatedModel' => $product,
        ));
        return $this->getProduct($cart, $product);
    }

    protected function _update($cart, $product, $quantity)
    {
        if (!$cartItem = $cart->has($product->id)) {
            throw new NotFoundHttpException('Product does not exist.');
        }

        $conditions = $this->addConditionsItem($cart, $product);
        foreach ($conditions as $condition) {
            $cart->addItemCondition($product->id, $condition);
        }
        $cart->update($product->id, [
            'name' => $product->name,
            'price' => $product->sale_price,
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
            'associatedModel' => $product
        ]);
        return $this->getProduct($cart, $product);
    }

    protected function _destroy($cart, $product)
    {
        if (!$cartItem = $this->getProduct($cart, $product)) {
            throw new NotFoundHttpException('Product does not exist.');
        }

        $cart->remove($product->id);

        return $cartItem;
    }

    protected function addConditionsItem($cart, $product)
    {
        $conditions = [];
        $cart->clearItemConditions($product->id);

        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'DISCOUNT',
            'type' => 'discount',
            'value' => sprintf('%+d', $product->discount) . '%',
        ));
        array_push($conditions, $condition);
        return $conditions;
    }

    protected function getProduct($cart, $product)
    {
        return $cart->get($product->id);
    }
}
