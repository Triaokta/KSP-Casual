<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Bank extends Model
{
    protected $fillable = ['code', 'name'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
