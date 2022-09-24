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
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function getImageUrlAttribute()
    {
        // return Storage::url('images/posts/' . $this->image);
        return Storage::url($this->image_path);
    }

    //     if (!Storage::putFileAs('images/posts', $file, $post->image)) {
    public function getImagePathAttribute()
    {
        return 'images/posts/' . $this->image;
    }
}
