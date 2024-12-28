<?php

namespace Mamun\ShopPreOrder\Http\Resources;


class PreOrderResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'product_id' => $this->product_id,
            'product' => $this->product?->name,
            'created_at' => $this->created_at,
        ];
    }
}
