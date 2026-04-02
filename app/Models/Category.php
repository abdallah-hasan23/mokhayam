<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model {
    protected $fillable = ['name','slug','description','color','order'];
    protected static function boot() {
        parent::boot();
        static::creating(fn($c) => $c->slug = $c->slug ?: Str::slug($c->name));
    }
    public function articles()          { return $this->hasMany(Article::class); }
    public function publishedArticles() { return $this->hasMany(Article::class)->where('status','published'); }
    public function getTotalViewsAttribute():int { return $this->articles()->sum('views'); }
}
