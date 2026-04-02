<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title','slug','excerpt','content','featured_image',
        'user_id','category_id','status','meta_title',
        'meta_description','views','published_at',
    ];
    protected $casts = ['published_at'=>'datetime','views'=>'integer'];

    protected static function boot() {
        parent::boot();
        static::creating(function($a) {
            if (empty($a->slug)) $a->slug = Str::slug($a->title);
            if ($a->status === 'published' && !$a->published_at) $a->published_at = now();
        });
    }

    public function user()            { return $this->belongsTo(User::class); }
    public function category()        { return $this->belongsTo(Category::class); }
    public function tags()            { return $this->belongsToMany(Tag::class); }
    public function comments()        { return $this->hasMany(Comment::class); }
    public function approvedComments(){ return $this->hasMany(Comment::class)->where('status','approved'); }

    public function scopePublished($q) { return $q->where('status','published'); }
    public function scopeDraft($q)     { return $q->where('status','draft'); }
    public function scopeReview($q)    { return $q->where('status','review'); }
    public function scopePopular($q)   { return $q->orderByDesc('views'); }
    public function scopeLatest($q)    { return $q->orderByDesc('published_at'); }

    public function getFeaturedImageUrlAttribute():string {
        return $this->featured_image
            ? asset('storage/'.$this->featured_image)
            : '';
    }
    public function getReadingTimeAttribute():string {
        $words = str_word_count(strip_tags($this->content));
        return max(1, ceil($words/200)).' دقيقة للقراءة';
    }
    public function getStatusLabelAttribute():string {
        return match($this->status){'published'=>'منشور','draft'=>'مسودة','review'=>'مراجعة','rejected'=>'مرفوض',default=>$this->status};
    }
    public function getStatusClassAttribute():string {
        return match($this->status){'published'=>'pill-green','draft'=>'pill-gray','review'=>'pill-blue','rejected'=>'pill-red',default=>'pill-gray'};
    }
    public function incrementViews():void { $this->increment('views'); }
}
