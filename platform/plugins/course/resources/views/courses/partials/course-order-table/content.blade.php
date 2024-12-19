<!-- <p class="text-secondary mb-0 p-3">
    {{ trans('plugins/course::course-order.course_description') }}
</p> -->

<div class="course-order-table"></div>
<script>
    $('.course-order-table').html('');
    $('.product-course-order-table p').hide();

    $(() => {
    $(document).on('change', '#course_order_table_id', function (e) {
        var $this = $(this);
        var $form = $this.closest('form');
        var $table = $this.val();
        // If a table is selected, make an AJAX request
        if ($table) {
            $.ajax({
                url: '{{ $getTableUrl }}',
                method: 'GET',
                data: {
                    table: $table,
                    ...(typeof $model !== 'undefined' ? { product: '{{ $model->getKey() }}' } : {}),
                },
                success: function (response) {
                    if (response.data) {
                        $form.find('.course-order-table').html(response.data);
                        $('.product-course-order-table p').show();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching table data:', error); // Debugging output
                },
            });
        } else {
            // Reset the specification table and show placeholder text
            $form.find('.course-order-table').html('');
            $('.product-course-order-table p').show();
        }
    });

    // Trigger the change event on page load
    $('#course_order_table_id').trigger('change');

    var course_order_id = "{{$model->id}}";
    if(course_order_id != ''){
        $(document).ready(function () {
            tableSetData();
        });
    }
    function tableSetData(){
      var course_order_id = "{{$model->id}}";
   
      $.ajax({
            url: "{{route('course_order.get-course-order-details', " + course_order_id + ")}}",
            method: 'GET',
            data: {
                course_order_id:course_order_id,
            },
            success: function (response) {
                if (response.data) {
                    $('.course-order-table').html(response.data);
                    $('.product-course-order-table p').show();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching table data:', error); // Debugging output
            },
        });
    }

    //Discount value calculate
    $(document).on('click', '#getDiscountValue', function () {
        const couponCode = $('#coupon-code').val();  
        const courseId = $('#course-id').val(); 
        $('#errorAlt').html('');
        $('#discountAmt').text('');
        $.ajax({
                url: '{{ route("course_order.discount-coupon-code") }}',
                method: 'GET',
                data: {
                    coupon_code: couponCode,
                    course_id: courseId,
                },
                success: function (response) {
                    if(response.status == 400){
                        var html = '<span style="color:red;">'+response.msg+'</span>'
                        $('#errorAlt').html(html);
                    }else{
                        var amount = parseFloat($('#total').attr('data-total')) || 0;
                        var discountRate = parseFloat(response.data.discount_amount);
                        var newAmount = amount - discountRate;
                        $('#discountAmt').text(discountRate);
                        $('#disAmt').val(discountRate);
                        $('#total').text(newAmount);
                        $('#totalAmt').val(newAmount);
                        $('#totaledit').val(newAmount);
                        var html = '<span style="color:green;">'+response.msg+'</span>'
                        $('#errorAlt').html(html);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching table data:', error); // Debugging output
                },
            });
    });

    
    //Discount value set in input box
    $(document).on('click', '.btn.btn-active', function () {
        const value = $(this).val();
        $('.btn.btn-active').removeClass('active');
        $(this).addClass('active');
        $('#amountId').html('');
        if (value === "amount") {
            var htmText = '<input type="text" class="form-control" id="discount-amt" name="discountAmount"><span class="input-group-text">₹</span>';
            $('#amountId').html(htmText);
        } else if(value === "percentage") {
            var htmText = '<input type="number" max="8" class="form-control" id="discount-amt" name="discountAmount"><span class="input-group-text">%</span>';
            $('#amountId').html(htmText);
        } 
    });

    });
</script>
