<?php

namespace Botble\Course\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCourse extends BaseModel
{
    protected $table = 'ec_discount_courses';

    protected $fillable = [
        'discount_id',
        'course_id',
    ];

    public function courses(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }
}
