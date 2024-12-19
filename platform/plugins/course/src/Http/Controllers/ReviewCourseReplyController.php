<?php

namespace Botble\Course\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Course\Http\Requests\ReviewReplyRequest;
use Botble\Course\Models\CourseReview;
use Botble\Course\Models\CourseReviewReply;

class ReviewCourseReplyController extends BaseController
{
    public function store(CourseReview $review, ReviewReplyRequest $request)
    {
        $userId = $request->user()->id;

        if (
            CourseReviewReply::query()
                ->where('course_review_id', $review->getKey())
                ->where('user_id', $userId)
                ->exists()
        ) {
            return $this
                ->httpResponse()
                ->setMessage(trans('plugins/course::review.already_replied'))
                ->setError();
        }

        $review->reply()->create([
            'user_id' => $userId,
            'message' => $request->input('message'),
        ]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/course::review.replied_success_message'));
    }

    public function update(CourseReview $review, CourseReviewReply $reply, ReviewReplyRequest $request)
    {
        // if ($reply->review()->isNot($review)) {
        //     abort(404);
        // }

        $reply->update([
            'message' => $request->input('message'),
        ]);

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseReview $review, CourseReviewReply $reply)
    {
        return DeleteResourceAction::make($reply);
    }
}
