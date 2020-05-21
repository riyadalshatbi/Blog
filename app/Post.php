<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title','body'];
    
    // Link relation comments with post
    public function comments()
    {
        return $this->hasMany(Comment::Class)->orderBy('created_at');
    }
    
    // Link relation category with post
    public function category()
    {
        return $this->belongsTo(Category::Class);
    }
    
    // Link relation likes with post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
}
