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


class Trainer extends BaseModel
{
    protected $table = 'trainer';

    protected $fillable = [
      'id',
      'title',
      'subtitle',
      'description',
      'status',
      'image',
      'created_at',
      'updated_at',
    ];

    protected $casts = [
        
      'status' => BaseStatusEnum::class,
    ];

    

}
