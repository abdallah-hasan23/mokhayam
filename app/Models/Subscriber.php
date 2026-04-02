<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model {
    protected $fillable = ['email','source','is_active','unsubscribe_token'];
    protected $casts    = ['is_active'=>'boolean'];
    protected static function boot() {
        parent::boot();
        static::creating(fn($s) => $s->unsubscribe_token = $s->unsubscribe_token ?: Str::random(40));
    }
    public function scopeActive($q)    { return $q->where('is_active',true); }
    public function scopeThisMonth($q) { return $q->whereMonth('created_at',now()->month); }
}
