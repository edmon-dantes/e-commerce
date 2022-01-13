<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\CartCollection;
use App\Http\Resources\Ecommerce\CartResource;
use App\Models\Ecommerce\Coupon;
use App\Models\Product;
use App\Traits\CartUtils;
use App\Traits\JwtUtils;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    use CartUtils, JwtUtils;

    protected $cart;
    protected $instanceName = 'cartstorage';

    public function __construct()
    {
        $this->middleware(['jwt.invited']);

        $this->cart = app($this->instanceName)->session($this->getPayloadId());
    }

    public function index()
    {
        $attributes = ['collections' => (object)[]];
        return (new CartCollection($this->cart->getContent()))->additional(['meta' => $attributes]);
    }

    public function store(Request $request, Product $product)
    {
        $cartItem = $this->_store($this->cart, $product, 1);
        $this->compareWishlist($product);
        return $this->responseCart($cartItem, ['message' => 'Successfully added.']);
    }

    public function update(Request $request, Product $product)
    {
        $cartItem = $this->_update($this->cart, $product, $request->input('quantity'));
        $this->compareWishlist($product);
        return $this->responseCart($cartItem, ['message' => 'Successfully updated.']);
    }

    public function destroy(Product $product)
    {
        $cartItem = $this->_destroy($this->cart, $product);
        return $this->responseCart($cartItem, ['message' => 'Successfully deleted.']);
    }

    protected function responseCart($cartItem, $attributes = null)
    {
        $cartContent = $this->cart->getContent();
        $attributes = array_merge((array) $attributes, [
            'current_page' => 1,
            'last_page' => 1,
            'per_page' => $cartContent->count(),
            'total' => $cartContent->count(),
            'cart_total_quantity' => $this->cart->getTotalQuantity(),
            'cart_sub_total' => $this->cart->getSubTotal(),
            'cart_grand_total' => $this->cart->getTotal(),
        ]);
        return (new CartResource($cartItem))->additional(['meta' => $attributes]);
    }

    protected function compareWishlist($product)
    {
        $wishlist = app('wishliststorage')->session($this->getPayloadId());
        if (!$wishlistItem = $this->getProduct($wishlist, $product)) {
            return;
        }
        $this->_destroy($wishlist, $product);
        return $wishlistItem;
    }

    public function applyCoupon(Request $request)
    {
        if (!$coupon = Coupon::where('code', $request->input('coupon_code'))->first()) {
            throw new NotFoundHttpException('Sorry! Coupon does not exist');
        }

        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => $coupon->name,
            'type' => $coupon->type,
            'target' => 'total', // this condition will be applied to cart's subtotal when  getSubTotal() is called.
            'value' => $coupon->value
        ));

        $this->cart->clearCartConditions();
        $this->cart->condition($condition);
        $cartCondition = $this->cart->getCondition($coupon->name);
        $cartCollection = $this->cart->getContent();

        $conditions = [];
        foreach ($this->cart->getConditions() as $condition) {
            array_push($conditions, [
                'target' => $condition->getTarget(),
                'name' => $condition->getName(),
                'type' => $condition->getType(),
                'value' => $condition->getValue(),
                'order' => $condition->getOrder(),
                'attributes' => $condition->getAttributes(),
                'calculatedValue' => $condition->getCalculatedValue($this->cart->getSubTotal()),
            ]);
        }

        return $this->responseCart($cartCollection, [
            'conditions' => $conditions,
        ]);
    }

    public function clear()
    {
        $this->cart->clear();
        return $this->index();
    }

    public function checkout() {
        $this->refresh();
        return $this->index();
    }

    public function refresh()
    {
        $cartContent = $this->cart->getContent();
        $this->cart->clear();

        foreach ($cartContent as $item) {
            if (!!$product = Product::find($item->associatedModel->id)) {
                $this->_store($this->cart, $product, $item->quantity);
            }
        }

        return $this->cart;
    }

    public function getCart()
    {
        return $this->cart;
    }
}
