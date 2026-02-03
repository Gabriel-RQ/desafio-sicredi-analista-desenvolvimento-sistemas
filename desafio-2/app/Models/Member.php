<?php

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

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

    protected static function newFactory()
    {
        return MemberFactory::new();
    }
}
