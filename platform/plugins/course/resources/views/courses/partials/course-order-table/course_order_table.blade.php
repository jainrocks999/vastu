<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
    <div>
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
<div class="row">
<div class="container">
  <h4>ORDER NO :  {{ @$courseDetails->code }}</h4>
  <p>STUDENT NAME: {{ @$customers->name }}</p>            
  <p>EMAIL ID: {{ @$customers->email }}</p>            
  <p>MOBILE NO: {{ @$customers->phone }}</p>            
  <!-- <table class="table">
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
      </tr>
    </tbody>
  </table> -->
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
                    <span id="amount" data-amount="{{ @$courseOrderDetails->amount ? $courseOrderDetails->amount : 0.00 }}">{{ @$courseOrderDetails->amount ? $courseOrderDetails->amount : 0.00 }} </span>
                    <input type="hidden" name="sub_amount" value="{{ @$courseOrderDetails->amount ? $courseOrderDetails->amount : 0.00 }}">
                </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3"> </x-core::table.body.cell>
                <x-core::table.body.cell> {{ trans('plugins/course::course-order.tax_amount') }} </x-core::table.body.cell>
                <x-core::table.body.cell> 
                    <span id="taxRate" data-taxamt="{{ @$courseOrderDetails->tax_amount ? $courseOrderDetails->tax_amount : 0.00 }}"> {{ @$courseOrderDetails->tax_amount ? @$courseOrderDetails->tax_amount : 0.00  }} </span>
                    <input type="hidden" name="tax_amount" value="{{ @$courseOrderDetails->tax_amount ? $courseOrderDetails->tax_amount : 0.00 }}">
                </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3"> </x-core::table.body.cell>
                <x-core::table.body.cell>
                    <a href="javascript:void(0)"><button id="openModal" type="button" class="btn btn-outline-primary btn-sm mb-1" data-toggle="modal" data-target="#showModal">{{ trans('plugins/course::course-order.discount') }}</button></a>
                 </x-core::table.body.cell>
                <x-core::table.body.cell>
                    <span id="discountAmt" data-discountamt="{{ @$courseOrderDetails->discount_amount ? $courseOrderDetails->discount_amount : 0.00 }}">{{ @$courseOrderDetails->discount_amount ? @$courseOrderDetails->discount_amount : 0.00  }} </span>
                </x-core::table.body.cell>
                    <input type="hidden" name="discount_amt" id="disAmt" value="{{ @$courseOrderDetails->discount_amount ? $courseOrderDetails->discount_amount : 0.00 }}">
                </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3"> </x-core::table.body.cell>
                <x-core::table.body.cell><h4> {{ trans('plugins/course::course-order.total') }} </h4> </x-core::table.body.cell>
                <x-core::table.body.cell> <span id="total" data-total="{{ @$courseOrderDetails->total ? $courseOrderDetails->total : 0.00 }}"> {{ @$courseOrderDetails->total ? $courseOrderDetails->total : 0.00 }} </span></x-core::table.body.cell>
                <input type="hidden" id="totaledit" name="total" value="{{ @$courseOrderDetails->total ? $courseOrderDetails->total : 0.00 }}">

            </x-core::table.body.row>
        </x-core::table.body>
    </x-core::table>    
</div>
