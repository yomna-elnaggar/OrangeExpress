<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'driver_id',
        'status',
        'cobon_number',
        'receiver',
        'receiver_phone',
        'emira',
        'area',
        'order_fees',
        'delivery_fees',
        'total',
        'notes',
        'date',
        'image',
    ];

    protected $casts = [
        'date' => 'date',
        'order_fees' => 'double',
        'delivery_fees' => 'double',
        'total' => 'double',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id')
            ->where(function ($query) {
                $query->where('role', 'vendor');
            });
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id')
            ->where(function ($query) {
                $query->where('role', 'driver');
            });
    }
}
