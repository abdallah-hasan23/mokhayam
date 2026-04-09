<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['name','location','email','story','status','show_on_home'];

    protected $casts = ['show_on_home' => 'boolean'];

    public function scopeApproved($q) { return $q->where('status','approved'); }
    public function scopeForHome($q)  { return $q->where('status','approved')->where('show_on_home', true); }
}
