<?php

namespace Botble\Course\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseReviewReply extends BaseModel
{
    protected $table = 'course_review_replies';

    protected $fillable = [
        'course_review_id',
        'review_id',
        'user_id',
        'message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(CourseReview::class);
    }
}
