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

    // modern style: اعتمدنا guarded فارغ ونستخدم validation بالكنترولر/Requests
    protected $guarded = [];

    protected $casts = [
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
        return $this->status === 'published' && (!is_null($this->published_at) ? $this->published_at->isPast() : true);
    }

    /* =======================
     *  Mutators / Hooks
     * ======================= */
    protected static function booted(): void
    {
        // ولادة slug تلقائياً لو مش مرسل
        static::creating(function (News $news) {
            if (empty($news->slug) && !empty($news->title)) {
                $news->slug = static::uniqueSlug($news->title);
            }
        });

        static::updating(function (News $news) {
            if ($news->isDirty('title') && empty($news->slug)) {
                $news->slug = static::uniqueSlug($news->title);
            }
        });
    }

    protected static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base ?: Str::random(8);

        $i = 1;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }
        return $slug;
    }

    /* =======================
     *  Scopes
     * ======================= */
    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        if (!$term) return $q;
        $term = trim($term);
        return $q->where(function ($w) use ($term) {
            $w->where('title', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%");
        });
    }

    public function scopeStatus(Builder $q, ?string $status): Builder
    {
        if (!$status || !in_array($status, ['draft','published'], true)) return $q;
        return $q->where('status', $status);
    }

    public function scopeFeatured(Builder $q, $flag): Builder
    {
        if (is_null($flag)) return $q;
        return $q->where('featured', (bool)$flag);
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
        $allowed = ['published_at','created_at','updated_at','views','title','id','featured','status'];

        $sort = in_array($sort, $allowed, true) ? $sort : 'published_at';

        return $q->when($sort === 'published_at', fn($qq) =>
        $qq->orderBy('featured','desc')->orderBy('published_at', $dir)
        )->when($sort !== 'published_at', fn($qq) =>
        $qq->orderBy($sort, $dir)
        )->orderBy('id', 'desc');
    }

    /* =======================
     *  Relations (اختياري)
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
