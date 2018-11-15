<?php
namespace ShopProject\Models\DataCollection;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email',
        'password',
        'nickname',
        'type'
    ];
}
