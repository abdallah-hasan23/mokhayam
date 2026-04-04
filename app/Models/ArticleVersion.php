<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleVersion extends Model
{
    protected $fillable = [
        'article_id','title','excerpt','content',
        'featured_image','meta_title','meta_description',
        'status','submitted_by',
    ];

    public function article()   { return $this->belongsTo(Article::class); }
    public function submitter() { return $this->belongsTo(User::class, 'submitted_by'); }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'  => 'بانتظار الموافقة',
            'approved' => 'مقبولة',
            'rejected' => 'مرفوضة',
            default    => $this->status,
        };
    }

    public function getFormattedDateAttribute()
    {
        return Article::toArabicDate($this->created_at);
    }
}
