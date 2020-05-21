<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    // Link relation Like With Post
    public function post()
    {
        $this->belongsTo(Post::class);
    }
    
    // Link relation like with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
