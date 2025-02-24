<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicDetailseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'vendor details' => [
                'id'    => $this->vendor->id,
                'name'  => $this->vendor->name,
                'phone' => $this->vendor->phone,
                'emira' => $this->emira,
                'area' => $this->area,
            ],
            'order details' => [
                'id'    => $this->id,
                'cobon_number' => $this->cobon_number,
                'order_fees' => $this->order_fees,
                'delivery_fees' => $this->delivery_fees,
                'total' => $this->total,
                'notes' => $this->notes,
                'status' => $this->status,
                'image' => $this->image,
            ],
        ];
    }
}
