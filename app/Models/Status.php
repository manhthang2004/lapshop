<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'status';

    protected $fillable = [
        'status_name',
    ];

    // Các mối quan hệ khác nếu có
    public function bills()
    {
        return $this->hasMany(Bill::class, 'id_status');
    }
}
