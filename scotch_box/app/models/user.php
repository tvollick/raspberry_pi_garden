<?php

namespace App\Models;

class User extends \Illuminate\Database\Eloquent\Model
{
  protected $table = 'users';

  // writeable?
  protected $fillable = [
    'email',
    'name',
    'phone',
    'password'
  ];
}
