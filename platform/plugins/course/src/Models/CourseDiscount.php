<?php

namespace Botble\Course\Models;

use Botble\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use DB;

class CourseDiscount extends BaseModel
{
    protected $table = 'course_discounts';

    protected $fillable = [
        'id',
        'title',
        'code',
        'start_date',
        'end_date',
        'quantity',
        'total_used',
        'value',
        'type',
        'can_use_with_promotion',
        'can_use_with_flash_sale',
        'type_option',
        'target',
        'min_order_price',
        'discount_on',
        'course_quantity',
        'apply_via_url',
        'display_at_checkout',
        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'can_use_with_promotion' => 'bool',
        'can_use_with_flash_sale' => 'bool',
        'apply_via_url' => 'bool',
        'display_at_checkout' => 'bool',
    ];

    protected static function booted(): void
    {
        static::deleted(function (CourseDiscount $discount) {
            // $discount->courseCollections()->detach();
            $discount->courseCategories()->detach();
            $discount->customers()->detach();
            $discount->courses()->detach();
            $discount->usedByCustomers()->detach();
        });
    }

    public function isExpired(): bool
    {
        return $this->end_date && strtotime($this->end_date) < strtotime(Carbon::now()->toDateTimeString());
    }

    // public function courseCollections(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         CourseCollection::class,
    //         'discount_course_collections',
    //         'discount_id',
    //         'course_collection_id'
    //     );
    // }

    public function courseCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            CourseCategory::class,
            'course_discount_course_category',
            'course_discount_id',
            'course_category_id'
        );
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'discount_customers', 'discount_id', 'customer_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'discount_courses', 'discount_id', 'course_id');
    }

    public function courseVariants(): BelongsToMany
    {
        return $this
            ->courses()
            ->where('is_variation', true);
    }

    public function usedByCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'course_customer_used_coupons');
    }

    protected function leftQuantity(): Attribute
    {
        return Attribute::get(fn () => $this->quantity - $this->total_used);
    }

    public function scopeActive(Builder $query): void
    {
        $query
            ->where('start_date', '<=', Carbon::now())
            ->where(
                fn (Builder $query) => $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now()->toDateTimeString())
            );
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where(
            fn (Builder $query) => $query
                ->whereNull('quantity')
                ->orWhereColumn('quantity', '>', 'total_used')
        );
    }
}
