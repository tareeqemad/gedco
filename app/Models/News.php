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

    // modern: نعتمد التحقق في الـ FormRequest/Controller
    protected $guarded = [];

    protected $casts = [
        'featured'     => 'boolean',
        'views'        => 'integer',
        'published_at' => 'datetime',
    ];

    // عشان الـ JSON والـ Blade يقدروا يستدعوا قيم جاهزة
    protected $appends = [
        'cover_url',
        'pdf_url',
        'is_published',
    ];

    /* =======================
     *  Accessors
     * ======================= */

    // URL صورة الغلاف من الـ disk (public أو حسب ضبطك)
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_path) return null;

        $disk = config('filesystems.default', 'public');
        try {
            return Storage::disk($disk)->url($this->cover_path);
        } catch (\Throwable $e) {
            // fallback للـ public
            return Storage::disk('public')->url($this->cover_path);
        }
    }

    // URL ملف الـ PDF
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) return null;

        $disk = config('filesystems.default', 'public');
        try {
            return Storage::disk($disk)->url($this->pdf_path);
        } catch (\Throwable $e) {
            return Storage::disk('public')->url($this->pdf_path);
        }
    }

    // هل الخبر منشور (status + تاريخ النشر)
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published'
            && (is_null($this->published_at) ? true : $this->published_at->isPast());
    }

    /**
     * ملخّص مقتضب (بديل أنيق لو ما عندك accessor جاهز)
     */
    public function excerpt(int $limit = 120): string
    {
        $base = $this->excerpt ?: ($this->body ? strip_tags($this->body) : '');
        return Str::limit(trim(preg_replace('/\s+/', ' ', $base)), $limit);
    }

    // (اختياري) ميثود للألفة مع الكود القديم
    public function coverUrl(): ?string
    {
        return $this->cover_url;
    }

    public function pdfUrl(): ?string
    {
        return $this->pdf_url;
    }

    /* =======================
     *  Mutators / Hooks
     * ======================= */
    protected static function booted(): void
    {
        // توليد slug تلقائيًا لو مفقود
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
            $slug = $base . '-' . $i;
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

        return $q->where(function (Builder $w) use ($term) {
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

        return $q
            ->when($sort === 'published_at', fn($qq) =>
            $qq->orderBy('featured','desc')->orderBy('published_at', $dir)
            )
            ->when($sort !== 'published_at', fn($qq) =>
            $qq->orderBy($sort, $dir)
            )
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

    /* =======================
     *  Route Key (اختياري)
     * ======================= */
    // لو حاب URLs بالـ slug بدل id، فعّل السطر أدناه،
    // وتأكد إن جميع السجلات فيها slug:
    //
    // public function getRouteKeyName(): string
    // {
    //     return 'slug';
    // }
}
