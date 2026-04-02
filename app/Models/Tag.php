<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model {
    protected $fillable = ['name','slug'];
    protected static function boot() {
        parent::boot();
        static::creating(fn($t) => $t->slug = $t->slug ?: Str::slug($t->name));
    }
    public function articles() { return $this->belongsToMany(Article::class); }
}
