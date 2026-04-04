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

    protected $casts = [
        'published_at' => 'datetime',
        'views'        => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title, '-');
                // Ensure uniqueness
                $count = static::where('slug', 'LIKE', $article->slug . '%')->count();
                if ($count > 0) $article->slug .= '-' . ($count + 1);
            }
        });
    }

    // Relationships
    public function user()        { return $this->belongsTo(User::class); }
    public function category()    { return $this->belongsTo(Category::class); }
    public function versions()    { return $this->hasMany(ArticleVersion::class)->latest(); }
    public function pendingVersion() { return $this->hasOne(ArticleVersion::class)->where('status','pending')->latest(); }

    // Scopes
    public function scopePublished($q) { return $q->where('status','published'); }
    public function scopeDraft($q)     { return $q->where('status','draft'); }
    public function scopePending($q)   { return $q->where('status','pending'); }
    public function scopeRejected($q)  { return $q->where('status','rejected'); }
    public function scopePopular($q)   { return $q->orderBy('views','desc'); }
    public function scopeLatest($q)    { return $q->orderBy('published_at','desc'); }

    // Accessors
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image
            ? asset('storage/' . $this->featured_image)
            : null;
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = max(1, round($words / 200));
        return $minutes . ' دقيقة للقراءة';
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft'     => 'مسودة',
            'pending'   => 'بانتظار الموافقة',
            'published' => 'منشور',
            'rejected'  => 'مرفوض',
            default     => $this->status,
        };
    }

    public function getStatusClassAttribute()
    {
        return match($this->status) {
            'draft'     => 'pill-gray',
            'pending'   => 'pill-blue',
            'published' => 'pill-green',
            'rejected'  => 'pill-red',
            default     => 'pill-gray',
        };
    }

    public function getFormattedDateAttribute()
    {
        $date = $this->published_at ?? $this->created_at;
        return $this->toArabicDate($date);
    }

    public static function toArabicDate($date)
    {
        if (!$date) return '';
        $days    = ['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
        $months  = ['','يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
        $day     = $days[$date->dayOfWeek];
        $month   = $months[(int)$date->format('n')];
        $dayNum  = static::toArabicNumerals($date->format('j'));
        $year    = static::toArabicNumerals($date->format('Y'));
        return "{$day}، {$dayNum} {$month} {$year}";
    }

    public static function toArabicNumerals($number)
    {
        $eastern = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        return str_replace(range(0,9), $eastern, (string)$number);
    }

    public function canBeEditedBy(User $user): bool
    {
        // Admin can always edit
        if ($user->isAdmin()) return true;
        // Owner can edit only if draft or rejected
        if ($this->user_id === $user->id) {
            return in_array($this->status, ['draft', 'rejected']);
        }
        return false;
    }

    public function needsVersionFor(User $user): bool
    {
        // When published article is edited by non-admin → create version
        return $this->status === 'published' && !$user->isAdmin();
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
