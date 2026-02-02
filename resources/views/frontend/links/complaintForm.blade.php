@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content') 


{!! NoCaptcha::renderJs() !!}
<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">Complaint</h2>
    </div>
  </div>
</div>
<section style="background:#f7f7f7;">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="complaint-form my-0">
          <form action="{{ route('frontend.complaint.form.process') }}" method="POST"> 
            @csrf 
            <div class="row gy-40 aplication-frm">
              <div class="col-xl-12 col-xxl-12"> 
                @if(session()->has('success_message')) 
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
                  </div> 
                @endif 
                @if(session()->has('error_message')) 
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
                  </div> 
                @endif 
              </div>
            </div>
                <div class="page-single service-single griv-wrap">
                  <div class="row ">
                    <div class="col-md-12 comp-head">
                      <h4>Complaint Form</h4>
                    </div>
                    <div class="col-xl-6 mb-3">
                      <div class="input text required">
                        <label class="form-label griv-frm" for="account-no">First Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="first_name" class="form-field w-input" value="{{ old('first_name') }}">
                        <div class="text text-danger"> @error('first_name') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                      </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                      <div class="input text required">
                        <label class="form-label griv-frm" for="customer-name"> Last Name 
                        </label>
                        <input type="text" name="last_name" class="form-field w-input" value="{{ old('last_name') }}">
                        <div class="text text-danger"> @error('last_name') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                      </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                      <div class="input text required">
                        <label class="form-label griv-frm" for="email">Email <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="email" class="form-field w-input" value="{{ old('email') }}">
                        <div class="text text-danger"> @error('email') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                      </div>
                    </div>
                    
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                        <label for="phoneInput" class="form-label griv-frm">Phone Number <span class="text-danger">*</span></label>

                        <div class="phone-input-group d-lg-flex">
                          <select name="country_code" class="countryCodeSelect form-control country-CodeSelect"></select>

                          <input
                            type="text"
                            name="phone_no"
                            maxlength="10"
                            class="form-control"
                            value="{{ old('phone_no') }}"
                            placeholder="Enter phone"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        </div>

                        @error('country_code')
                          <p class="error-message">{{ $message }}</p>
                        @enderror
                        @error('phone_no')
                          <p class="error-message">{{ $message }}</p>
                        @enderror
                      </div>
                    </div>
                    
                    <div class="col-xl-4 mb-4">
                      <div class="radio-label">
                        <label class="form-label griv-frm" for="customer-name">Gender</label>
                        <div class="form-check form-check-inline ps-0  ps-0">
                          <input class="form-check-input" type="radio" name="gender" id="inlineRadio99" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }} checked>
                          <label class="form-check-label" for="inlineRadio99">Male</label>
                        </div>
                        <div class="form-check form-check-inline ps-0 ">
                          <input class="form-check-input" type="radio" name="gender" id="inlineRadio77" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                          <label class="form-check-label" for="inlineRadio77"> Female</label>
                        </div>
                      </div>
                      <div class="text text-danger"> @error('gender') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                    </div>
                    <div class="col-xl-8 mb-4">
                      <div class="radio-label">
                        <label class="form-label griv-frm" for="customer-name">Age Group</label>
                        <div class="form-check form-check-inline ps-0  ps-0 ">
                          <input class="form-check-input" type="radio" name="age_group" id="inlineRadio46" value="18-30" checked {{ old('age_group') == '18-30' ? 'checked' : '' }}>
                          <label class="form-check-label" for="inlineRadio46">18-30</label>
                        </div>
                        <div class="form-check form-check-inline ps-0 ">
                          <input class="form-check-input" type="radio" name="age_group" id="inlineRadio45" value="31-45" {{ old('age_group') == '31-45' ? 'checked' : '' }}>
                          <label class="form-check-label" for="inlineRadio45"> 31-45</label>
                        </div>
                        <div class="form-check form-check-inline ps-0 ">
                          <input class="form-check-input" type="radio" name="age_group" id="inlineRadio50" value="46-59" {{ old('age_group') == '46-59' ? 'checked' : '' }}>
                          <label class="form-check-label" for="inlineRadio50"> 46-59</label>
                        </div>
                        <div class="form-check form-check-inline ps-0 ">
                          <input class="form-check-input" type="radio" name="age_group" id="inlineRadio48" value="60 an over" {{ old('age_group') == '60 an over' ? 'checked' : '' }}>
                          <label class="form-check-label" for="inlineRadio48"> 60 an over</label>
                        </div>
                      </div>
                      <div class="text text-danger"> @error('age_group') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                      <div class="input text required">
                        <label class="form-label griv-frm" for="customer-name">Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="address" class="form-field w-input" rows="3">{{ old('address') }}</textarea>

                        <div class="text text-danger"> @error('address') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                      </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                      <div class="input text required"> {!! NoCaptcha::display() !!} <div class="text text-danger"> @error('g-recaptcha-response') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                      </div>
                    </div>
                    <div class="col-xl-12 mb-3 mt-4 text-center">
                      <button type="submit" class="th-btn"> Submit </button>
                    </div>
                  </div>
                </div>
          </form>
        </div>
      </div>
      <div class="col-md-4 pd-0-track ">
        <div class="input-box-wrap">
        <h3>Track Your Complaint</h3>
        @if(session()->has('error_search_complaint')) 
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error_search_complaint') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
          </div> 
        @endif 
        <label class="form-label griv-frm" for="customer-name">Complaint ID <span class="text-danger">*</span></label>
        <div class="input-box">
          <i class="uil uil-search"></i>
          <form action="{{ route('frontend.search-complaint') }}" method="GET">
            @csrf
            <input type="text" name="complaint_id" class="form-control" placeholder="Enter Your Complaint ID" />
            <div class="text ">@error('complaint_id')<p class="text-danger" style="font-size: 14px; padding-top: 8px; line-height: 15px;">{{ $message }}</p>@enderror</div>
            <button class="button">Search</button>
          </form>
        </div>

        @if(!empty($complaint))
        <div class="custom-det-p">
          @if(!empty($complaint['official_use_end_date']))
          <h4><span>Latest Update :</span> {{ isset($complaint['official_use_end_date']) ? date('M d, Y h:i A',strtotime($complaint['official_use_end_date'])) : '-'}} </h4>
          @endif

          <h4><span>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </span> 
            @if($complaint['status'] == '0')
              New
            @elseif($complaint['status'] == '1')
              Resolved
            @elseif($complaint['status'] == '2')
              In progress
            @endif
          </h4>
          <h4>
            <span>Feedback &nbsp;&nbsp;&nbsp;: </span>
            @if($complaint['status'] == '0')
            We are working on it.
            @else 
            {{ isset($complaint['official_use_result']) ? $complaint['official_use_result'] : '-' }}
            @endif
            
          </h4>
        </div>
        @endif
        </div>
      </div>
    </div>
  </div>
</section>


@endsection