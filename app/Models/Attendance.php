<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'notes',
        'photo_in',
        'photo_out',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];
    
    protected $appends = ['time_in_formatted', 'time_out_formatted'];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTimeInFormattedAttribute()
    {
        return $this->time_in ? $this->time_in->format('H:i') : '-';
    }
    
    public function getTimeOutFormattedAttribute()
    {
        return $this->time_out ? $this->time_out->format('H:i') : '-';
    }
}
