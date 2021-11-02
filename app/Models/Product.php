<?php

namespace App\Models;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $fillable = ['id','name', 'price'];

    public function  getPriceEurAttribute()
    {
        return (new CurrencyService)->convert($this->price, 'usd', 'eur');
    }
}
