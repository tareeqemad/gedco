<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news';
    protected $guarded = [];

    // دعم JSON + أنواع
    protected $casts = [
        'tags'         => 'array',        // مهم جدًا للوسوم
        'featured'     => 'boolean',
        'views'        => 'integer',
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'cover_url',
        'pdf_url',
        'is_published',
    ];

    /* =======================
     *  Accessors
     * ======================= */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_path ? Storage::disk('public')->url($this->cover_path) : null;
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path ? Storage::disk('public')->url($this->pdf_path) : null;
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published'
            && ($this->published_at?->isPast() ?? true);
    }

    public function excerpt(int $limit = 120): string
    {
        $text = $this->excerpt ?? strip_tags($this->body ?? '');
        return Str::limit(trim(preg_replace('/\s+/', ' ', $text)), $limit);
    }

    /* =======================
     *  Slug Generation
     * ======================= */
    protected static function booted(): void
    {
        static::creating(fn($news) => $news->generateSlugIfEmpty());
        static::updating(fn($news) => $news->generateSlugIfEmpty());
    }

    public function generateSlugIfEmpty(): void
    {
        if (empty($this->slug) && !empty($this->title)) {
            $this->slug = static::uniqueSlug($this->title, $this->id);
        }
    }

    protected static function uniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base = Str::slug($title) ?: 'news-' . time();
        $slug = $base;
        $i = 1;

        while (static::withTrashed()->where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /* =======================
     *  Scopes
     * ======================= */
    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        if (!$term = trim($term ?? '')) return $q;

        return $q->where(function (Builder $w) use ($term) {
            $w->where('title', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%");
        });
    }

    public function scopeStatus(Builder $q, ?string $status): Builder
    {
        return $status && in_array($status, ['draft', 'published']) ? $q->where('status', $status) : $q;
    }

    public function scopeFeatured(Builder $q, $flag): Builder
    {
        return is_null($flag) ? $q : $q->where('featured', (bool)$flag);
    }

    public function scopeHasTags(Builder $q, array $tags): Builder
    {
        if (empty($tags)) return $q;

        foreach ($tags as $tag) {
            $q->whereJsonContains('tags', $tag);
        }
        return $q;
    }

    public function scopeBetweenDates(Builder $q, ?string $from, ?string $to): Builder
    {
        if ($from) $q->whereDate('published_at', '>=', $from);
        if ($to)   $q->whereDate('published_at', '<=', $to);
        return $q;
    }

    public function scopeSortSmart(Builder $q, string $sort = 'published_at', string $dir = 'desc'): Builder
    {
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';
        $allowed = ['published_at', 'created_at', 'updated_at', 'views', 'title', 'id', 'featured'];

        $sort = in_array($sort, $allowed) ? $sort : 'published_at';

        return $q
            ->when($sort === 'published_at', fn($qq) => $qq->orderBy('featured', 'desc')->orderBy('published_at', $dir))
            ->when($sort !== 'published_at', fn($qq) => $qq->orderBy($sort, $dir))
            ->orderBy('id', 'desc');
    }

    /* =======================
     *  Relations
     * ======================= */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
