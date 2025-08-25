<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatusHistory extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'is_active',
        'changed_by',
        'notes',
    ];
    
    /**
     * Get the employee that owns the status history.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
