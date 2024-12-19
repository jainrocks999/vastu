<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
<div>
        <!-- Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <header class="modal-header">
                    <h5 class="modal-title" id="add-discountsLabel">ADD ORDER DISCOUNT</h5>
                    <!-- <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">Close</button> -->
                </header>
                <div class="modal-body">
                    <div class="next-form-section">
                        <!-- <div class="next-form-grid">
                            <div class="mb-3 position-relative">
                                <label class="form-label">Discount based on</label>
                                <div class="row">
                                    <div class="col-auto">
                                        <button class="btn btn-active active" value="amount">₹</button>&nbsp; 
                                        <button class="btn btn-active" value="percentage"> % </button>
                                    </div>
                                    <div class="col">
                                        <div class="input-group input-group-flat" id="amountId">
                                            <input class="form-control" id="discount-amt" name="discountAmount">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="next-form-grid">
                            <div class="mb-3 position-relative">
                                <label class="form-label">Coupon Code</label>
                                <input type="hidden" id="course-id" value="{{ @$courseDetails->id }}" name="course_id">
                                <input class="form-control coupon-code-input" id="coupon-code" name="coupon_code">
                            </div>
                            <div id="errorAlt"></div>
                                <!-- <div class="position-relative">
                                    <label class="form-label">Description</label>
                                    <input placeholder="Discount description" id="discount-desc" class="form-control" name="discountDescription">
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="button" class="btn btn-primary ms-auto" id="getDiscountValue">Add discount</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <x-core::table class="table-bordered">
        <x-core::table.header>
         <x-core::table.header.cell>
                {{ trans('plugins/course::course.image') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/course::course.code') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/course::course.title') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell class="text-center">
                {{ trans('plugins/course::course.course_category_id') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/course::course.price') }}
            </x-core::table.header.cell>
        </x-core::table.header>
        <x-core::table.body>
            <x-core::table.body.row>
                <x-core::table.body.cell> <img width="100" src="http://127.0.0.1:8000/storage/{{ @$courseDetails->image }}" alt="">  </x-core::table.body.cell>
                <x-core::table.body.cell>  {{ @$courseDetails->code }} </x-core::table.body.cell>
                <x-core::table.body.cell> {{ @$courseDetails->title }} </x-core::table.body.cell>
                <x-core::table.body.cell> {{ @$courseDetails->category->name }} </x-core::table.body.cell>
                <x-core::table.body.cell> {{ @$courseDetails->price }} </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3"> </x-core::table.body.cell>
                <x-core::table.body.cell> {{ trans('plugins/course::course-order.sub_amt') }} </x-core::table.body.cell>
                <x-core::table.body.cell >
                    <span id="amount" data-amount="{{ @$courseDetails->price ? $courseDetails->price : 0.00 }}">{{ @$courseDetails->price ? $courseDetails->price : 0.00 }} </span>
                    <input type="hidden" name="sub_amount" value="{{ @$courseDetails->price ? $courseDetails->price : 0.00 }}">
                </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3"> </x-core::table.body.cell>
                <x-core::table.body.cell> {{ trans('plugins/course::course-order.tax_amt') }}  
                @if(isset($courseDetails->tax))
                 : &nbsp; {{ @$courseDetails->tax ? $courseDetails->tax : '' }}
                 {{ @$courseDetails->tax_amount ? $courseDetails->tax_amount : 0.00 }} 
                 {{ @$courseDetails->tax == 'amount' ? '₹' : '%' }} 
                @endif
            </x-core::table.body.cell>

            <?php
               $price = $courseDetails->price;
               $tax = $courseDetails->tax;
               $tax_type = $courseDetails->tax_type;
               $tax_amt = $courseDetails->tax_amount;
               if (isset($tax_type) && !empty($tax_amt) && $tax_type == 'amount') {
                   $tax_amount = $tax_amt;
                   $total = $price + $tax_amount;
               } else  if (isset($tax_type) && !empty($tax_amt) && $tax_type == 'percentage') {
                   $tax_amount = ($price * $tax_amt) / 100;
                   $total = $price + $tax_amount;
               }else{
                    $tax_amount = 0;
                    $total = $price;
               }
            ?>
            <x-core::table.body.cell> 
                    <span id="taxRate" data-taxamt="{{ @$tax_amount }}"> {{ $tax_amount  }} </span>
                    <input type="hidden" name="tax_amount" value="{{ $tax_amount }}">
                </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3"> </x-core::table.body.cell>
                <x-core::table.body.cell>
                    <a href="javascript:void(0)"><button id="openModal" type="button" class="btn btn-outline-primary btn-sm mb-1" data-toggle="modal" data-target="#showModal">{{ trans('plugins/course::course-order.discount') }}</button></a>
                 </x-core::table.body.cell>
                <x-core::table.body.cell>
                    <span id="discountAmt" data-discountamt="{{ @$courseDetails->discount ? $courseDetails->discount : 0.00 }}">{{ @$courseDetails->discount ? @$courseDetails->discount : 0.00  }} </span>
                </x-core::table.body.cell>
                    <input type="hidden" name="discount_amt" id="disAmt" value="{{ @$courseDetails->discount_amt ? $courseDetails->discount_amt : 0.00 }}">
                </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3"> </x-core::table.body.cell>
                <x-core::table.body.cell><h4> {{ trans('plugins/course::course-order.total') }} </h4> </x-core::table.body.cell>
                <x-core::table.body.cell> <span id="total" data-total="{{ @$total }}"> {{ @$total }} </span></x-core::table.body.cell>
                <input type="hidden" name="total" id="totalAmt" value="{{ @$total }}">
            </x-core::table.body.row>
        </x-core::table.body>
    </x-core::table>    
</div>
