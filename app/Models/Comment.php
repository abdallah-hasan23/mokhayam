<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

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
