<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\Config as ConfigEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'registration_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'jabatan_id',
        'department_id',
        'division_id',         // Added
        'directorate_id',
        'superior_id',
        'nik',
        'address',
        'profile_picture',
        'jabatan_full',        // Added
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: jabatan (position/rank)
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Relationship: department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship: division
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relationship: directorate
     */
    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    /**
     * Relationship: superior (atasan langsung)
     */
    public function superior()
    {
        return $this->belongsTo(User::class, 'superior_id');
    }

    /**
     * Relationship: subordinates
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'superior_id');
    }

    public function signatureAndParaf()
    {
        return $this->hasOne(SignatureAndParaf::class, 'registration_id', 'registration_id');
    }


    /**
     * Accessor: profile picture
     */
    public function profilePicture(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
                $value ?: 'https://ui-avatars.com/api/?background=6D67E4&color=fff&name=' . urlencode($this->name)
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRole($query, Role $role)
    {
        return $query->where('role', $role->status());
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $find) {
            return $query
                ->where('name', 'LIKE', $find . '%')
                ->orWhere('phone', $find)
                ->orWhere('email', $find);
        });
    }

    public function scopeRender($query, $search)
    {
        return $query
            ->search($search)
            ->role(Role::STAFF)
            ->paginate(Config::getValueByCode(ConfigEnum::PAGE_SIZE))
            ->appends(['search' => $search]);
    }
}
