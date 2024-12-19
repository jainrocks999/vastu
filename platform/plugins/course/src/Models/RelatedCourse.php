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

class RelatedCourse extends BaseModel
{
    protected $table = 'course_related_relations';

    protected $fillable = [
      'id',
      'from_course_id',
      'to_course_id',
      'created_at',
      'updated_at',
    ];

}
