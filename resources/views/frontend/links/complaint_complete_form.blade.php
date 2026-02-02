@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content') 
{!! NoCaptcha::renderJs() !!}
<style>
  #signature-pad 
  {
    border: 2px dashed #ccc;
    width: 100%;
    max-width: 100%;
    height: 200px;
    cursor: crosshair;
  }

  .form-check-input[type=checkbox] {
      border-radius: .25em;
      padding: 0px;
      visibility: visible;
      opacity: 1;
      display: inline-block;
      vertical-align: middle;
      width: 1em;
      height: 1em;
  }

 .upload-box 
  {
    display: flow;
    border: 1px solid #788094;
    border-radius: 10px;
    padding: 20px 20px 20px;
    text-align: center;
    position: relative;
    transition: 0.3s ease;
    background-color: #fff;
    height: 140px;
  }

  .form-group.ad-user .upload-box label {
    color: #fff;
  }

  .upload-icon 
  {
    font-size: 40px;
    color: #333;
    margin-bottom: 15px;
  }
  textarea 
  {
    min-height: 120px;
  }

  .upload-text {
    font-size: 16px;
    color: #444;
    margin-bottom: 15px;
  }

  .upload-btn 
  {
    background-color: #444c48;
    color: #fff;
    border: none;
    left: 38%;
    position: absolute;
    padding: 10px 30px;
    border-radius: 4px;
    font-weight: 600;
    width: 130px;
  }

  .upload-btn:hover 
  {
    background-color: #2f3331;
  }

  input[type="file"] 
  {
    display: none;
  }
  #fileList{
    text-align:center !important;
  }
  
  
</style>
<div class="breadcumb-wrapper background-image" style="background-image: url('{{ asset('frontend/img/bread-crum.jpg') }} ')">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">Complaint</h2>
    </div>
  </div>
</div>
<section style="background:#f7f7f7;">
  <div class="container">
    <div class="row">
      <div class="complaint-form my-0">
       @if(session()->has('success')) 
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
        @endif 
        <form id="complaint-form" action="{{ route('frontend.complaint-complete-form-process') }}" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="complaint_id" value="{{ $complaint['complaint_id'] }}">
          <input type="hidden" name="email" value="{{ $complaint['email'] }}"> 
          @csrf 
          <div class="card mb-4 border-0 bornew ">
            <div class="card-body">
              <div class="text-white">
                <div class="row">
                  <div class="col-md-6">
                    <p class="mb-1 row border-dotted">
                      <span class="font-bold col-md-2 p-0">Name </span>
                      <span class="col-md-10">{{ isset($complaint['first_name']) ? $complaint['first_name'] : '' }} {{ isset($complaint['last_name']) ? $complaint['last_name'] : '' }}</span>
                    </p>
                    <p class="mb-1 row border-dotted">
                      <span class="font-bold col-md-2 p-0"> Email Id </span>
                      <span class="col-md-10">
                        <span mailto:class="trigger">{{ isset($complaint) ? $complaint['email'] : '' }}</span>
                      </span>
                    </p>
                  </div>
                  <div class="col-md-6">
                    <p class="mb-1 row border-dotted">
                      <span class="col-md-2 p-0 font-bold"> Phone No: </span>
                      <span class="col-md-10">{{ isset($complaint) ? $complaint['country_code'] : '' }} {{ isset($complaint) ? $complaint['phone'] : '' }}</span>
                    </p>
                    <p class="mb-1 row border-dotted">
                      <span class="font-bold col-md-2 p-0"> Address: </span>
                      <span class="col-md-10">
                        <span class="trigger">{{ isset($complaint) ? $complaint['address'] : '' }}</span>
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card radius-10">
            <div class="card-header bg-light py-1">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="form-section mt-2 mb-2"> Information on Business</h6>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row aplication-frm">
                <div class="col-xl-4 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Business Name (or Individual) <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="business_name" class="form-field w-input" value="{{ old('business_name') }}" id="business_name">
                    <div class="text text-danger" id="error-business_name">@error('business_name') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-4 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Email <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="business_email" class="form-field w-input" value="{{ old('business_email') }}" id="business_email">
                    <div class="text text-danger" id="error-business_email">@error('business_email') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                
              
                <div class="col-xl-4 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Phone Number <span class="text-danger">*</span>
                    </label>
                     <div class="phone-input-group d-lg-flex">
                    <select id="countryCodeSelect" name="business_country_code" class="form-select form-field w-input form-control countryCodeSelect country-CodeSelect">
                              <option value="">Code</option>
                            </select>
                      <div class="text text-danger" id="error-country-code">@error('business_country_code') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    <input type="text" name="business_phone" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="form-field w-input" value="{{ old('business_phone') }}" id="business_phone">
                    <div class="text text-danger" id="error-business_phone">@error('business_phone') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                </div>
                
            <div class="col-xl-12 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Address <span class="text-danger">*</span>
                    </label>
                   <textarea name="business_address" class="form-field w-input" id="business_address" rows="4">{{ old('business_address') }}</textarea>

                    <div class="text text-danger" id="error-business_address">@error('business_address') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-header bg-light py-1">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="form-section mt-2 mb-2"> Information on Goods or Services</h6>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row aplication-frm">
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Product or Service Purchased <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="goods" class="form-field w-input" value="{{ old('goods') }}" id="business_goods">
                    <div class="text text-danger" id="error-goods">@error('goods') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Brand <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="brand" class="form-field w-input" value="{{ old('brand') }}" id="business_brand">
                    <div class="text text-danger" id="error-brand">@error('brand') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Model/Serial Number<span class="text-danger">*</span>
                    </label>
                    <input type="text" name="serial" class="form-field w-input" value="{{ old('serial') }}" id="serial">
                    <div class="text text-danger" id="error-serial">@error('serial') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Category <span class="text-danger">*</span>
                    </label>
                      <select class="form-control" name="category" id="category">
                        <option value="" selected disabled>Select</option>
                        @foreach($complaintCategory as $category)
                          <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}
                          </option>
                        @endforeach
                      </select>

                    <div class="text text-danger" id="error-category">@error('category') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Date of Purchase <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="date_purchase" class="form-field w-input" value="{{ old('date_purchase') }}" id="date_of_purchase">
                    <div class="text text-danger" id="error-date_purchase">@error('date_purchase') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Warranty Period<span class="text-danger">*</span>
                    </label>
                    <select class="form-control" name="warranty" id="warranty">
                      <option value="" selected disabled>Select</option>
                      <option value="None">None</option>
                      <option value="14 days or less" {{ old('warranty') == '14 days or less' ? 'selected' : '' }}>14 days or less</option>
                      <option value="1 month" {{ old('warranty') == '1 month' ? 'selected' : '' }}>1 month</option>
                      <option value="2 month" {{ old('warranty') == '2 month' ? 'selected' : '' }}>2 month</option>
                      <option value="3 month" {{ old('warranty') == '3 month' ? 'selected' : '' }}>3 month</option>
                    </select>
                    <div class="text text-danger" id="error-warranty">@error('warranty') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Hire Purchase Item<span class="text-danger">*</span>
                    </label>
                     <select class="form-control" name="hire_purchase" id="hire_purchase">
                        <option value="" selected disabled>Select</option>
                        <option value="0" {{ old('hire_purchase') == '0' ? 'selected' : '' }}>Yes</option>
                        <option value="1" {{ old('hire_purchase') == '1' ? 'selected' : '' }}>No</option>
                      </select>
                    <div class="text text-danger" id="error-hire_purchase">@error('hire_purchase') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Did you sign a contract?<span class="text-danger">*</span>
                    </label>
                      <select class="form-control" name="contract" id="contract">
                        <option value="" selected disabled>Select</option>
                        <option value="0" {{ old('contract') == '0' ? 'selected' : '' }}>Yes</option>
                        <option value="1" {{ old('contract') == '1' ? 'selected' : '' }}>No</option>
                      </select>
                    <div class="text text-danger" id="error-contract">@error('contract') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-6 mb-4">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Additional Statement</label>
                    <textarea rows="5" name="additional_statement" class="form-field w-input">{{ old('additional_statement') }}</textarea>
                    <div class="text text-danger">@error('additional_statement') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                <div class="col-xl-6 mb-4">
                  <div class="position-relative">
                    <div class="form-group ad-user">
                      <label>Copy of Receipt/Contract/Agreement <span class="text-danger">*</span>
                      </label>
                      <div class="upload-box mx-auto">
                      <div class="upload-icon"></div>
                      <div class="upload-text">Upload a file using the button below</div>
                        <label for="fileUpload" class="upload-btn">Browse</label>
                        <input type="file" name="documents[]" id="fileUpload" accept="image/*,.pdf,.doc,.docx" multiple><br>
                      </div>
                      <div class="text text-danger" id="error-documents">@error('documents') <p style="color:red;">{{ $message }}</p>@enderror </div>
                      <span>Only .jpg, .jpeg, .png, .pdf, .doc, and .docx files are accepted. Maximum file size: 2 MB.</span>
                      <div id="fileList"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-header bg-light py-1">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="form-section mt-2 mb-2"> Willingness to attend Proceedings <span class="text-danger">*</span></h6>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row aplication-frm">
                <div class="col-xl-12 mb-4">
                  <div class="input text required">
                 <p class="d-flex">
                   <span style="width: 20px;"><input class="form-check-input me-2" type="checkbox" id="certify" name="certify">  </span>
                  <span>I certify the above information to be truthful and accurate to the best of my knowledge and belief. I am willing to testify to the same at any proceedings directly related to this complaint if required to do so.</span></p>
                  </div>
                  <div class="text text-danger" id="error-certify">@error('certify') <p style="color:red;">{{ $message }}</p>@enderror </div>
                </div>

                <div class="col-xl-6">
                  <label class="form-label" for="account-no">Signed <span class="text-danger">*</span></label>
                </div>
                <div class="col-xl-6 mb-2 text-end">
                 <button type="button" class="btn btn-primary" onclick="clearPad()">Clear</button>
                </div>
                <div class="col-xl-12 mb-4 ">
                  <div class="input text required">
                    <canvas id="signature-pad"></canvas><br>
                    <input type="hidden" name="signature_image" id="signature_image" accept="image/*,.pdf,.docx,.doc">
                    <div class="text text-danger" id="sign_error">@error('signature_image')<p style="color:red;">{{$message}}</p>@enderror</div>
                  </div>
                </div>
                <div class="col-xl-3 mb-4 ">
                  <div class="input text required">
                    <label class="form-label" for="account-no">Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="date" class="form-field w-input" value="{{ old('date') }}" id="submit_date">
                    <div class="text text-danger" id="error-date">@error('date') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                </div>
                  <div class="col-xl-9 mb-2">
                      <div class="input text required"> {!! NoCaptcha::display() !!} <div id="error-captcha" class="text text-danger"> @error('g-recaptcha-response') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                      </div>
                    </div>
                <div class="col-xl-12 mb-4 mt-4 text-center">
                  <button type="submit" class="th-btn"> Submit </button>
                </div>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div> 
</section> 

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_of_purchase').setAttribute('max', today);
    });
</script>

@endsection


