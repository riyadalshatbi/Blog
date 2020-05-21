<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body','post_id'];
    
    // Link Relation Comment With Post
    public function post()
    {
        return $this->belongsTo(Post::Class);
    }
    
    // Link Relation Comment With User
    public function user()
    {
        return $this->belongsTo(User::Class);
    }
}
