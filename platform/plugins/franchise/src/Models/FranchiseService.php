<?php

namespace Botble\Franchise\Models;

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


class FranchiseService extends BaseModel
{
    protected $table = 'franchise_services_tbl';

    protected $fillable = [
        'id',
        'services_name',
        'home_page_layout',
        'logo',
        'draw_menu',
        'premium_services',
        'short_description',
        'services_desc',
        'services_price',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'select_status' => BaseStatusEnum::class,
    ];

   
  
}
