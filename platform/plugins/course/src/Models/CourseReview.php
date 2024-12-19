<?php

namespace Botble\Course\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class CourseReview extends BaseModel
{
    protected $table = 'course_reviews';

    protected $fillable = [
        'id',
        'course_id',
        'customer_id',
        'customer_name',
        'customer_email',
        'star',
        'comment',
        'status',
        'images',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'images' => 'array',
        'order_created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (CourseReview $review) {
            if (! $review->images || ! is_array($review->images) || ! count($review->images)) {
                $review->images = null;
            }
        });

        static::updating(function (CourseReview $review) {
            if (! $review->images || ! is_array($review->images) || ! count($review->images)) {
                $review->images = null;
            }
        });

        static::deleting(fn (CourseReview $review) => $review->reply()->delete());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->withDefault();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class)->withDefault();
    }

    public function reply(): HasOne
    {
        return $this->hasOne(CourseReviewReply::class);
    }

    protected function courseName(): Attribute
    {
        return Attribute::get(fn () => $this->course->name);
    }

    protected function userName(): Attribute
    {
        return Attribute::get(fn () => $this->user->name ?: $this->customer_name);
    }

    protected function orderCreatedAt(): Attribute
    {
        return Attribute::get(fn () => $this->user->orders()->first()?->created_at);
    }

    protected function isApproved(): Attribute
    {
        return Attribute::get(fn () => $this->status == BaseStatusEnum::PUBLISHED);
    }

    protected function customerAvatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->user->avatar) {
                return RvMedia::getImageUrl($this->user->avatar, 'thumb');
            }

            try {
                return (new Avatar())->create(Str::ucfirst($this->user->name ?: $this->customer_name))->toBase64();
            } catch (Exception) {
                return RvMedia::getDefaultImage();
            }
        });
    }
}
