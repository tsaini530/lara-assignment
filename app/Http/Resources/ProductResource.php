<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'category' => $this->category,
            'product' => $this->product,
            'discount'  =>$this->discount,
            'offered_price'=> $this->price,
            'original_price'=> 100*$this->price/(100-$this->discount),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
