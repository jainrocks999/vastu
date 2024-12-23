@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
<div class="container-fluid">
    <div class="row" id="franchise-section-wrapper">
        <!-- Franchise Details Section -->
        <div class="col-md-9 ">
                <x-core::card>
                    <x-core::card.header>
                       <strong>{{ trans('Application Request Date : ')  }}</strong> 
                       {{ \Carbon\Carbon::parse($franchise->application_req_date)->format('d/m/Y') }}
                    </x-core::card.header>
                <x-core::card.body>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                        <!-- for 1 row -->
                            <tbody>
                                <tr>
                                    <td class="col-md-4">
                                       <strong>{{ trans('plugins/franchise::franchise.name') }}</strong><br>
                                       <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->franchise_name))
                                                {{ $franchise->franchise_name }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                    <strong>{{ trans('Franchise Email') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($customerdatas) && !empty($customerdatas['email']))
                                                {{ $customerdatas['email'] }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.phone') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                        @if(isset($customerdatas) && !empty($customerdatas['phone']))
                                                {{ $customerdatas['phone'] }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif
                                        </div>    
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                       <strong>{{ trans('plugins/franchise::franchise.dob') }}</strong><br>
                                       <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($customerdatas) && !empty($customerdatas['dob']))
                                            {{ \Carbon\Carbon::parse($customerdatas['dob'])->format('d/m/Y') }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.gender') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->gender))
                                                {{ $franchise->gender }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.current_location') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->current_location))
                                            {{ $franchise->current_location }}
                                            @else
                                            {{ trans('N/A') }}
                                            @endif
                                        </div>    
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                       <strong>{{ trans('plugins/franchise::franchise.country') }}</strong><br>
                                       <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->country))
                                                {{ $franchise->country }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.state') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->state))
                                                {{ $franchise->state }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.city') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->city))
                                            {{ $franchise->city }}
                                            @else
                                            {{ trans('N/A') }}
                                            @endif
                                        </div>    
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                       <strong>{{ trans('plugins/franchise::franchise.pincode') }}</strong><br>
                                       <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->pincode))
                                                {{ $franchise->pincode }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.service_id') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->service_id))
                                                {{ $franchise->service_id }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-md-4">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                       <strong>{{ trans('plugins/franchise::franchise.certificate') }}</strong><br>
                                       <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->certificate))
                                                {{ $franchise->certificate }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.certificate_docs') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->certificate_docs))
                                                <a href="{{ url('storage',$franchise->certificate_docs) }}" target="_blank">
                                                <!-- <img src="{{  url('storage',$franchise->certificate_docs) }}" alt="certificate_docs" -->
                                                <!-- style="max-width: 200px; height: auto;  width:100%;"> -->
                                                    <div>
                                                        <i class="fa fa-image"></i> View Image
                                                    </div>
                                                </a> 
                                            @else
                                                {{ trans('N/A') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.experience_field') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->experience_field))
                                                {{ $franchise->experience_field }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.experience_of_year') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->experience_of_year))
                                                {{ $franchise->experience_of_year }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.specializations') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->specializations))
                                                {{ $franchise->specializations }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.language') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->language))
                                                {{ $franchise->language }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.level') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->level))
                                                {{ $franchise->level }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.charges') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->charges))
                                                {{ $franchise->charges }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                        <strong>{{ trans('Franchise Status') }}</strong><br>
                                        @if(isset($customerdatas) && !empty($customerdatas['status'])) 
                                        @if($customerdatas['status'] == 'activated') 
                                            <div class="p-2 mt-2 bg-success text-light" style=" border-radius:5px;">
                                            {{ trans('Enabled') }}
                                            </div>
                                        @endif

                                        @if($customerdatas['status'] == 'locked')
                                            <div class="p-2 mt-2 bg-secondary text-light" style=" border-radius:5px;">
                                            {{ trans('Disabled') }}
                                            </div>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.short_description') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->short_description))
                                                {{ $franchise->short_description }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-4">
                                        <strong>{{ trans('plugins/franchise::franchise.content') }}</strong><br>
                                        <div class="p-2  mt-2" style="background-color:#e5f7fd; border-radius:5px;">
                                            @if(isset($franchise) && !empty($franchise->content))
                                                {{ $franchise->content }}
                                            @else
                                                {{ trans('N/A') }}
                                            @endif  
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>
        <!-- Application Status Section -->
        
        <div class="col-md-3">

        <div class="pb-2">
                    <x-core::card>
                            <x-core::card.header>
                                <h4 class="card-title">
                                    {{ trans('Application Status') }}
                                </h4>
                            </x-core::card.header>

                            <x-core::card.body>
                                <div class="text-center">
                                    @if(isset($franchise) && !empty($franchise->application_status == 'accept'))
                                        <div class="p-2 bg-success text-light "  align="center" style=" border-radius:5px;">
                                            {{ trans('Accepted') }}
                                        </div>
                                    @endif

                                    <!-- For Pending -->
                                    @if(isset($franchise) && !empty($franchise->application_status == 'pending'))
                                        <div class="p-2 bg-warning text-light " align="center" style=" border-radius:5px;">
                                            {{ trans('Pending') }}
                                        </div>
                                    @endif

                                    <!-- For Reject -->
                                    @if(isset($franchise) && !empty($franchise->application_status == 'reject'))
                                        <div class="p-2 bg-danger text-light"  align="center" style=" border-radius:5px;">
                                            {{ trans('Rejected') }}
                                        </div>
                                    @endif
                                </div>                                 
                            </x-core::card.body>

                    </x-core::card>
                </div>

        @if(isset($franchise) && !empty($franchise->application_status=='pending'))
            <div class="pb-2">
            <x-core::card>
                <x-core::card.header>
                    <h4 class="card-title">
                        {{ trans('Application Request') }}
                    </h4>
                </x-core::card.header>

                     <x-core::card.body>
                        <form action="{{ route('franchise.update-status', $franchise->getKey()) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- If you want to use PUT method for update -->

                            <!-- Add hidden input for the franchise id -->
                            <input type="hidden" name="franchise_id" value="{{ $franchise->getKey() }}">

                            <!-- Add hidden input for status, this will be updated when buttons are clicked -->
                            <input type="hidden" id="status" name="status" value="{{ $franchise->application_status }}">

                            
                                <!-- Accept Button -->
                                 
                                 <div class="mb-2">
                                    <button type="submit" class="btn btn-success w-100" onclick="setStatus('accept')">
                                        {{ trans('Accept') }}
                                    </button>
                                </div>
                         
                                <!-- Reject Button -->
                                <div >
                                    <button type="submit" class="btn btn-danger w-100" onclick="setStatus('reject')">
                                        {{ trans('Reject') }}
                                    </button>
                                </div>
                        </form>
                    </x-core::card.body>

            </x-core::card>
            </div>
            @endif
            <!-- avatar -->
                <div class="pt-0">
                    <x-core::card>
                            <x-core::card.header>
                                <h4 class="card-title">
                                    {{ trans('Franchise Avatar') }}
                                </h4>
                            </x-core::card.header>

                            <x-core::card.body>
                                <div class="text-center">
                                    @if(!empty($customerdatas['avatar']))
                                        <img src="{{ url('storage',$customerdatas['avatar']) }}"  alt="Franchise Avatar">
                                    @else
                                        <div class="pt-4 pb-4">
                                        {{ trans('Image Unavailable') }}
                                        </div>
                                    @endif
                                </div>                                 
                            </x-core::card.body>

                    </x-core::card>
                </div>
        </div>
        
    </div>
</div>


<script>
    // JavaScript to set the status field when a button is clicked
    function setStatus(status) {
        // Set the hidden status field value based on the clicked button
        document.getElementById('status').value = status;
    }
</script>


@endsection
