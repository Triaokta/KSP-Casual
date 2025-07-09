<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Tentukan kolom mana saja yang boleh diisi secara massal.
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'name',
        'address',
        'nik_ktp',
        'npwp',
        'no_kk',
        'department_id',
        'is_active',
    ];

    /**
     * Definisikan relasi: Satu Karyawan dimiliki oleh satu Departemen.
     * Ini memungkinkan kita untuk memanggil $employee->department->name
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}