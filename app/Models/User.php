<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'must_change_password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin()
    {
        return $this->role && in_array($this->role->name, ['Administrateur', 'Coordinateur', 'Admin', 'Agent JICA']);
    }

    public function hasRole(string $roleName): bool
    {
        if (!$this->role) {
            return false;
        }
        return strtolower($this->role->name) === strtolower($roleName);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function candidateProfile()
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function candidateResources()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function readMessages()
    {
        return $this->belongsToMany(Message::class)->withPivot('read_at')->withTimestamps();
    }

    public function profileUpdateRequests()
    {
        return $this->hasMany(ProfileUpdateRequest::class);
    }
}
