<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;


class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'category_id',
        'body',
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
        
    public function scopeFilter( $query, array $filters)
    {
        $query->when(
            $filters['search'] ?? false, 
            fn ($query, $search) =>
            $query->where('name', 'like', '%' . $search . '%')

        );
        $query->when(
            $filters['category'] ?? false, 
            fn ($query, $category) =>
            $query->whereHas('category', fn ($query) =>
            $query->where('slug', $category))
        );
        $query->when(
            $filters['pencipta'] ?? false, 
            fn ($query, $pencipta) =>
            $query->whereHas('pencipta', fn ($query) =>
            $query->where('username', $pencipta))
        );

    }
}
