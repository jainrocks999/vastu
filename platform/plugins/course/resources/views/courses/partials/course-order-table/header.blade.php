
@if(!isset($model->id) || empty($model->id))
<select class="form-select" name="course_order_table_id" id="course_order_table_id">
    <option value="">{{ trans('plugins/course::course-order.select_none') }}</option>
    @foreach($tables as $value => $label)
        <option value="{{ $value }}" @selected($model->id === $value)>{{ $label }}</option>
    @endforeach
</select>
@else
 <input type="hidden" name="course_order_id" value="{{$model->id}}">
@endif