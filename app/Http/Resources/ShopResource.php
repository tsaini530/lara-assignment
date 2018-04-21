<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            'shop_name' => $this->shop_name,
            'db_name' => $this->db_name,
            'requests'  =>$this->requests,            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
