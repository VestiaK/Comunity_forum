<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;

class Post extends Model
{
    protected $fillable = [
    'name',
    'slug',
    'category_id',
    'body',
    'user_id'
    ];
    protected $with = ['asker', 'category'];

    public function asker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
