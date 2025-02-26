<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['code', 'discount'];
    
    public function isValid()
    {
        $now = now();
        return $this->valid_from <= $now && $this->valid_to >= $now;
    }
    public function bills()
    {
        return $this->hasMany(Bill::class, 'voucher', 'code');
    }
}
