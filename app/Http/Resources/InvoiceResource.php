<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'    => $this->id,
            'vedor_id'  => $this->vendor_id,
            'vendor_name'  => $this->vendor->name ?? 'not found',
            'cobon_number' => $this->cobon_number,
            'receiver' => $this->receiver,
            'receiver_phone' => $this->receiver_phone,
            'emira' => $this->emira,
            'area' => $this->area,
            'order_fees' => $this->order_fees,
            'delivery_fees' => $this->delivery_fees,
            'total' => $this->total,
            'notes' => $this->notes,
            'date' => $this->date->format('Y-m-d'),
            // 'image' => $this->image,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver->name ?? 'not found',
            'status' => $this->status,
        ];
    }
}
