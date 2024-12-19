<x-core::form.textarea
    name="message"
    :placeholder="trans('plugins/course::review.write_your_reply')"
    :value="old('message', $reply ? $reply->message : '')"
/>
