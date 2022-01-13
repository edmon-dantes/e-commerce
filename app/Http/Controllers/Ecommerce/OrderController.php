<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\Ecommerce\OrderRequest;
use App\Http\Resources\Ecommerce\OrderCollection;
use App\Http\Resources\Ecommerce\OrderResource;
use App\Models\Ecommerce\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items'])
            ->search($request->input('_search'))
            ->sort($request->input('_sort'))
            ->paginate($request->input('_size'));

        return (new OrderCollection($orders))->additional([
            'meta' => [
                'collections' => (object)[]
            ]
        ]);

        /*
        $search = $request->input('search');
        $sort = $request->input('sort');
        $size = (int)$request->input('size');

        $orders = Order::whereHas('items.shop', function ($q) use ($request) {
            if (!!$user = $request->input('_user')) {
                $q->where('user_id', $user);
            }
        })->search($search)->sort($sort)->paginate($size);
        */
    }

    public function create()
    {
        $order = new Order;

        if (auth()->check() && !!$user = auth()->user()) {
            $order->shipping_firstname = $user->name;
            $order->shipping_lastname = $user->lastname;
            $order->shipping_email = $user->email;
            $order->shipping_phone = $user->phone;
            $order->shipping_country = '';
            $order->shipping_address = '';
            $order->shipping_city = '';
            $order->shipping_state = '';
            $order->shipping_zipcode = '';
        }

        return $this->responseOrder($order);
    }

    public function store(BaseFormRequest $baseFormRequest)
    {
        $request = $baseFormRequest->convertRequest(OrderRequest::class);

        DB::beginTransaction();
        try {

            $order = Order::create($request->validated());

            $cart = (new CartController)->getCart();
            $cartContent = $cart->getContent();

            foreach ($cartContent as $item) {
                $order->items()->attach($item->id, ['name' => $item->name, 'price' => $item->price, 'quantity' => $item->quantity]);
            }

            if ($request->input('payment_method') === 'paypal') {
                $attributes['paypal_checkout'] = (new PaypalController)->create($order);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        if ($request->input('payment_method') === 'paypal') {
            return (new PaypalController)->create($order);
        }

        return $this->responseOrder($order, array('message' => 'Order Paided'));
    }

    /*
    public function show(Order $order)
    {
        return $this->responseOrder($order);

        // $item = $order->items()->whereHas('shop', function ($q) use ($request) {
        //     if (!!$user = $request->input('_user')) {
        //         $q->where('user_id', $user);
        //     }
        // })->get();

        // dd($item);
    }

    public function edit(Order $order)
    {
        return $this->responseOrder($order);
    }
    */

    public function update(BaseFormRequest $baseFormRequest, Order $order)
    {
        $request = $baseFormRequest->convertRequest(OrderRequest::class);

        DB::beginTransaction();
        try {
            $order->update($request->validated());

            $cart = (new CartController)->getCart();
            $cartContent = $cart->getContent();

            $items = [];
            foreach ($cartContent as $item) {
                $items[$item->id] = ['name' => $item->name, 'price' => $item->price, 'quantity' => $item->quantity];
            }

            $order->items()->sync($items);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        if ($request->input('payment_method') === 'paypal') {
            return (new PaypalController)->create($order);
        }

        return $this->responseOrder($order, array('message' => 'Order Paided'));
    }

    /*
    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {
            $order->items()->detach();
            $order->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responseOrder($order, ['message' => 'Successfully deleted.']);
    }
    */

    public function responseOrder($order, $attributes = null)
    {
        $paginate = $order->search(request()->input('search'))->paginate(request()->input('size'));

        $attributes = array_merge((array) $attributes, [
            'current_page' => $paginate->currentPage(),
            'last_page' => $paginate->lastPage(),
            'per_page' => $paginate->perPage(),
            'total' => $paginate->total(),
        ]);

        return (new OrderResource($order->load(['items'])))->additional(['meta' => $attributes]);
    }

    /*
    public function markDelivered(SubOrder $order)
    {
        // $item = $order->items()->whereHas('shop', function ($q) {
        //     $q->where('user_id', auth()->id());
        // })->update(['delivered_at' => now()]);

        $order->status = 'completed';
        $order->save();
    }
    */
}
