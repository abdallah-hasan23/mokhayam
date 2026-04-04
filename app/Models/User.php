<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','role','avatar','bio',
        'job_title','is_active','show_name','show_avatar',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
        'show_name'         => 'boolean',
        'show_avatar'       => 'boolean',
    ];

    public function articles() { return $this->hasMany(Article::class); }

    public function isAdmin():  bool { return $this->role === 'admin'; }
    public function isEditor(): bool { return in_array($this->role, ['admin','editor']); }
    public function isWriter(): bool { return $this->role === 'writer'; }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'  => 'مدير',
            'editor' => 'محرر',
            'writer' => 'كاتب',
            default  => $this->role,
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'admin'  => '#b8902a',
            'editor' => '#2a7ab8',
            'writer' => '#2ab87a',
            default  => '#888',
        };
    }

    public function getAvatarInitialAttribute(): string
    {
        return mb_substr($this->name, 0, 1);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->show_name ? $this->name : 'مجهول الهوية';
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->show_avatar) return null;
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function getTotalViewsAttribute(): int
    {
        return $this->articles()->sum('views');
    }
}
