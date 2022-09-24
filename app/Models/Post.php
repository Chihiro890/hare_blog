<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
// Strageをuse文で読み込む

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
    ];

    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function image_url()
    // {
    //     return Storage::url('images/posts/' . $this->image);
    // }

    public function getImageUrlAttribute()
    {
        return Storage::url('images/posts/' . $this->image);
    }
}

//     if (!Storage::putFileAs('images/posts', $file, $post->image)) {
