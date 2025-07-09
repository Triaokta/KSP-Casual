<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Definisikan relasi: Satu Departemen bisa memiliki banyak Karyawan.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}