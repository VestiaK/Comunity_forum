<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Post;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'reputation_points', // tambahkan ini
        'role', // tambahkan kolom role
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Tambahkan relasi komentar milik user ini
    public function comments(): HasMany
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    // Relasi ke komentar yang sudah divote user ini
    public function votedComments()
    {
        return $this->belongsToMany(\App\Models\Comment::class, 'comment_votes')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function hasVotedOnComment($commentId)
    {
        return $this->votedComments()->where('comment_id', $commentId)->exists();
    }
    public function isModerator()
    {
        return $this->role === 'moderator';
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
