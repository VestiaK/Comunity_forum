<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
    'name',
    'slug',
    'category_id',
    'body',
    'user_id'
    ];
    protected $with = ['asker', 'category'];

    // Relasi ke user yang membuat post (asker)
    public function asker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Relasi ke kategori post
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    // Relasi ke komentar yang dimiliki post ini
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
