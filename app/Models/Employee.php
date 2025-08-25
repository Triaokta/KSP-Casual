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
    
    protected $table = 'employees';
    protected $fillable = [
        'employee_id',
        'name',
        'address',
        'nik_ktp',
        'npwp',
        'no_rek',
        'bank_id',
        'nama_bank',
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

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    
    /**
     * Get the status histories for the employee.
     */
    public function statusHistories()
    {
        return $this->hasMany(EmployeeStatusHistory::class);
    }
    
    /**
     * Hitung berapa kali status aktif berubah
     */
    public function getStatusChangeCountAttribute()
    {
        return $this->statusHistories()->count();
    }
    
    /**
     * Ambil riwayat perubahan status terakhir
     */
    public function getLastStatusChangeAttribute()
    {
        return $this->statusHistories()->latest()->first();
    }
    
    /**
     * Relasi dengan absensi
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}