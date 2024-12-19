<?php

namespace Botble\Course\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountStudent extends BaseModel
{
    protected $table = 'course_discount_student';

    protected $fillable = [
        'discount_id ',
        'student_id ',
    ];

    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }
}
