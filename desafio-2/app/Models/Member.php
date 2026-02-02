<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $with = [
        'address',
    ];

    protected $fillable = [
        'cpf',
        'name',
        'phone',
        'email',
        'address_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'address_id',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
