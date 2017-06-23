<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class VPE extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product1', 'product2'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'verpakkingseenheden';
}