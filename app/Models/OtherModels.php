<?php namespace App\Models;
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

class Tag extends Model {
    protected $fillable = ['name','slug'];
    protected static function boot() {
        parent::boot();
        static::creating(fn($t) => $t->slug = $t->slug ?: Str::slug($t->name));
    }
    public function articles() { return $this->belongsToMany(Article::class); }
}

class Comment extends Model {
    protected $fillable = ['article_id','author_name','author_email','body','status'];
    public function article() { return $this->belongsTo(Article::class); }
    public function scopePending($q)  { return $q->where('status','pending'); }
    public function scopeApproved($q) { return $q->where('status','approved'); }
    public function getStatusLabelAttribute():string {
        return match($this->status){'pending'=>'بانتظار الموافقة','approved'=>'معتمد','rejected'=>'مرفوض',default=>$this->status};
    }
    public function getStatusClassAttribute():string {
        return match($this->status){'pending'=>'pill-blue','approved'=>'pill-green','rejected'=>'pill-red',default=>'pill-gray'};
    }
}

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
