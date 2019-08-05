<?php

namespace Lab19\Cart\Module\Users;

use Illuminate\Database\Eloquent\Model;
use Lab19\Cart\Module\Orders\Cart;

class Session extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cart_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The cart relationships
     *
     * @var array
     */
    public function cart(){
        return $this->hasOne(Cart::class);
    }

}
