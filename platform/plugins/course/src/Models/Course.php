<?php

namespace Botble\Course\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Media\Facades\RvMedia;
use Botble\Theme\Supports\Youtube;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Course extends BaseModel
{
    protected $table = 'course';

    protected $fillable = [
      'id',
      'title',
      'code',
      'label1',
      'description1',
      'label2',
      'description2',
      'label3',
      'description3',
      'label4',
      'description4',
      'image',
      'video',
      'docs',
      'type',
      'price',
      'student_price',
      'franchise_price',
      'sale_price',
      'instructor',
      'start_date',
      'end_date',
      'language',
      'prerequisites',
      'enrollment_limit',
      'enrollment_type',
      'certification',
      'discounts',
      'tax',
      'tax_type',
      'tax_amount',
      'tags',
      'keywords',
      'course_category_id',
      'status',
    ];
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'question' => SafeContent::class,
        'answer' => SafeContent::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id')->withDefault();
    }

    protected function images(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): array {
                try {
                    if ($value === '[null]') {
                        return [];
                    }

                    $images = json_decode((string) $value, true);

                    if (is_array($images)) {
                        $images = array_filter($images);
                    }

                    return $images ?: [];
                } catch (Exception) {
                    return [];
                }
            }
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                $firstImage = Arr::first($this->images) ?: null;

                if ($this->is_variation) {
                    return $firstImage;
                }

                return $value ?: $firstImage;
            }
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class, 'course_id')->wherePublished();
    }

}
