<?php namespace App\Models;
// ============================================================
// User Model  →  app/Models/User.php
// ============================================================
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $fillable = ['name','email','password','role','avatar','bio','job_title','is_active'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime','is_active'=>'boolean'];

    public function articles() { return $this->hasMany(Article::class); }

    public function isAdmin():bool  { return $this->role === 'admin'; }
    public function isEditor():bool { return in_array($this->role,['admin','editor']); }

    public function getRoleLabelAttribute():string {
        return match($this->role){'admin'=>'مدير النظام','editor'=>'محرر','writer'=>'كاتب',default=>'مستخدم'};
    }
    public function getRoleColorAttribute():string {
        return match($this->role){'admin'=>'#b02a2a','editor'=>'#b8902a',default=>'#1a5fa8'};
    }
    public function getAvatarInitialAttribute():string {
        return mb_substr($this->name,0,1);
    }
    public function getTotalViewsAttribute():int {
        return $this->articles()->sum('views');
    }
}
