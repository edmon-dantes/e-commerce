<?php

namespace App\Http\Resources\Ecommerce;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'number' => $this->number,
            'user' => new UserResource($this->whenLoaded('user')),
            'status' => $this->status,
            'grand_total' => $this->grand_total,
            'item_count' => $this->item_count,
            'is_paid' => $this->is_paid,
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
            'shipping_firstname' => $this->shipping_firstname,
            'shipping_lastname' => $this->shipping_lastname,
            'shipping_email' => $this->shipping_email,
            'shipping_phone' => $this->shipping_phone,
            'shipping_country' => $this->shipping_country,
            'shipping_address' => $this->shipping_address,
            'shipping_state' => $this->shipping_state,
            'shipping_zipcode' => $this->shipping_zipcode,
            'billing_firstname' => $this->billing_firstname,
            'billing_lastname' => $this->billing_lastname,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
            'billing_country' => $this->billing_country,
            'billing_address' => $this->billing_address,
            'billing_state' => $this->billing_state,
            'billing_zipcode' => $this->billing_zipcode,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];

        if (!!$this->id) {
            $result = array_merge($result, [
                'created_at' => $this->created_at->format('d-m-Y'),
                'updated_at' => $this->updated_at->format('d-m-Y'),
            ]);
        }

        return $result;
    }
}
