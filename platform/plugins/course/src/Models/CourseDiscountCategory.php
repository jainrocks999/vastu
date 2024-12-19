<?php

namespace Botble\Course\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseCategory extends BaseModel
{
    protected $table = 'course_discount_course_category';

    protected $fillable = [
        'id',
        'course_discount_id',
        'course_category_id'
    ];  

    public function courses(): HasMany
    {
        return $this->hasMany(CourseCategory::class, 'course_category_id');
    }
    
    public function course_discount(): HasMany
    {
        return $this->hasMany(CourseDiscount::class, 'discount_id');
    }
}
