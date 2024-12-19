<?php

namespace Botble\Course\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Media\Facades\RvMedia;
use Botble\Theme\Supports\Youtube;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CourseOrder extends BaseModel
{
    protected $table = 'course_orders';
    protected $fillable = [
            'id',
            'code',
            'user_id',
            'user_name',
            'course_id',
            'course_title',
            'course_description',
            'course_category',
            'status',
            'amount',
            'tax_amount',
            'coupon_code',
            'discount_description',
            'discount_amount',
            'sub_total',
            'total',
            'payment_method',
            'payment_status',
            'payment_transaction_id',
            'start_date',
            'end_date',
            'created_at',
            'updated_at',
        ];
    // protected $casts = [
    //     'status' => BaseStatusEnum::class,
    //     'question' => SafeContent::class,
    //     'answer' => SafeContent::class,
    // ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id')->withDefault();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id')->withDefault();
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

}
