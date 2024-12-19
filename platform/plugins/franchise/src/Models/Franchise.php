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


class Franchise extends BaseModel
{
    protected $table = 'franchise_tbl';

    protected $fillable = [
        'id',
        'customer_id',
        'service_id',
        'franchise_name',
        'gender',
        'current_location',
        'country',
        'state',
        'city',
        'pincode',
        'certificate',
        'certificate_docs',
        'experience_field',
        'experience_of_year',
        'application_req_date',
        'application_status',
        'specializations',
        'language',
        'level',
        'charges',
        'logo',
        'short_description',
        'content',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }

    // public function category(): BelongsTo
    // {
    //     return $this->belongsTo(CourseCategory::class, 'course_category_id')->withDefault();
    // }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->avatar) {
                return RvMedia::getImageUrl($this->avatar, 'thumb');
            }

            try {
                return (new Avatar())->create(Str::ucfirst($this->name))->toBase64();
            } catch (Exception) {
                return RvMedia::getDefaultImage();
            }
        });
    }

    protected function uploadFolder(): Attribute
    {
        return Attribute::get(function () {
            $folder = $this->id ? 'customers/' . $this->id : 'customers';

            return apply_filters('ecommerce_customer_upload_folder', $folder, $this);
        });
    }
}
