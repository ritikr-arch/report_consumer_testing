@extends('admin.layouts.app') 
@section('content') 
<style>
  #signature-pad 
  {
    border: 2px dashed #ccc;
    width: 100%;
    max-width: 100%;
    height: 200px;
    cursor: crosshair;
  }
  .check-boxx{
    display: flex;
  }
  .check-boxx p{
    margin-bottom: 0;
    margin-left: 15px;
    margin-top: 15px;
  }

  .upload-box 
  {
    display: flow;
   border: 1px solid #dee2e6;
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

    .upload-text {
      font-size: 16px;
      color: #444;
      margin-bottom: 15px;
    }

    .upload-btn {
      background-color: #444c48;
      color: #fff;
      border: none;
      padding: 10px 30px;
      border-radius: 4px;
      font-weight: 600;
    }

    .upload-btn:hover {
      background-color: #2f3331;
    }

    .list-unstyled{
      text-align: center;
    margin-top: 12px;
    font-size: 13px;
    color: black;
    }
/* country code css*/

  .phone-input-group {
    display: flex;
    border: 1px solid #788094;
    border-radius: 50px;
    overflow: hidden;
  }

  .phone-input-group select,
  .phone-input-group input {
    border: none;
    outline: none;
    font-size: 14px;
    padding: 10px;
  }

  .phone-input-group select {
    flex-shrink: 0;
  }

  .phone-input-group input {
    flex: 1;
  }

  .error-message {
    color: red;
    font-size: 13px;
    margin-top: 4px;
  }

  /* Select2 height fix */
  .select2-container .select2-selection--single {
    height: 50px !important;
    display: flex !important;
    align-items: center !important; 
  }

  /* For flag icons in select2 */
  .select2-results__option img {
    margin-right: 8px;
    vertical-align: middle;
    width: 20px;
    height: 14px;
  }

  .select2-selection__rendered img {
    margin-right: 8px;
    vertical-align: middle;
    width: 20px;
    height: 14px;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow{
    display: none !important;
  }
  .select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #aaa;
    height: 30px;
    padding: 12px;
    margin-bottom: 7px;
}
.select2-results__option--selectable {
    cursor: pointer;
    font-size: 10px;
}
.select2-container {
    width: 32% !important;
    border-radius:10px !important;
}
.select2-results__option:before, .select2-results__option[aria-selected=true]:before{
  display:none;
}
.select2-container--default .select2-results__option[aria-selected=true], .select2-container--default .select2-results>.select2-results__options{
  font-size:10px !important;
}
.select2-container--default .select2-selection--single{
   
    border-radius: 50px !important;
}
.select2-dropdown{
width: 320.1009px !important;
    border-radius: 10px !important;
}
.select2-selection__rendered{
 text-overflow: inherit !important;
}
</style>
<div class="px-3">
  <!-- Start Content-->
  <div class="container-fluid">
    <input type="hidden" id="content_id" value="">
    <div class="row mt-3">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center d-flex mb-3">
              <div class="col-xl-12">
                <h4 class="header-title mb-0 font-weight-bold"> Complaint Form </h4>
              </div>
            </div>
            <form action="{{ route('admin.complaint.form.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-4">
                <div class="form-group ad-user">
                  <label>First Name <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                  <div class="text text-danger" id="error-first_name"> @error('first_name') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group ad-user">
                  <label> Last Name 
                  </label>
                  <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                  <div class="text text-danger" id="error-last_name"> @error('last_name') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group ad-user">
                  <label>Email
                  </label>
                  <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                  <div class="text text-danger" id="error-email"> @error('email') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                </div>
              </div>
             
              <div class="col-md-4">
                <div class="form-group ad-user">
                  <label>Phone No. <span class="text-danger">*</span>
                  </label>
                   <div class="phone-input-group d-flex">
                  <select name="country_code" class="form-select form-control countryCodeSelect" style="width: 50%;">
                              <option value="">Code</option>
                            </select>
                  <input type="text" name="phone_no" maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="form-control" value="{{ old('phone_no') }}">
                            </div>
                  <div class="text text-danger" id="error-phone_no"> @error('phone_no') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror @error('country_code') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group ad-user">
                  <label>Address <span class="text-danger">*</span>
                  </label>
                  <textarea name="address" class="form-control">{{ old('address') }}</textarea>

                  <div class="text text-danger" id="error-address"> @error('address') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Gender</label>
                   <div class="gender-flex">
                     <div class="form-group">
                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio99" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }} checked>
                    <label>Male</label>
                  </div>
                  <div class="form-group">
                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio77" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                    <label> Female</label>
                  </div>
                   </div>
                </div>
                <div class="text text-danger" id="error-gender"> @error('gender') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
              </div>
              <div class="col-md-8">
                <div class="radio-label">
                  <label>Age Group</label>
                  <div class="gender-flex">
                  <div class="form-group ps-0 ">
                    <input class="form-check-input" type="radio" name="age_group" id="inlineRadio46" value="18-30" checked {{ old('age_group') == '18-30' ? 'checked' : '' }}>
                    <label>18-30</label>
                  </div>
                  <div class="form-group">
                    <input class="form-check-input" type="radio" name="age_group" id="inlineRadio45" value="31-45" {{ old('age_group') == '31-45' ? 'checked' : '' }}>
                    <label> 31-45</label>
                  </div>
                  <div class="form-group">
                    <input class="form-check-input" type="radio" name="age_group" id="inlineRadio50" value="46-59" {{ old('age_group') == '46-59' ? 'checked' : '' }}>
                    <label> 46-59</label>
                  </div>
                  <div class="form-group">
                    <input class="form-check-input" type="radio" name="age_group" id="inlineRadio48" value="60 an over" {{ old('age_group') == '60 an over' ? 'checked' : '' }}>
                    <label> 60 an over</label>
                  </div>
                </div>
              </div>
                <div class="text text-danger" id="error-age_group"> @error('age_group') <p style="color:red; margin-bottom:0;">{{ $message }}</p> @enderror </div>
              </div>
              <div class="card-header bg-light py-1">
                <div class="row align-items-center">
                  <div class="col">
                    <h4 class="form-section mt-2 mb-2"> Information on Business</h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row aplication-frm">
                  <div class="col-md-4">
                    <div class="form-group ad-user">
                      <label>Business Name (or Individual) <span class="text-danger">*</span>
                      </label>
                      <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}">
                      <div class="text text-danger" id="error-business_name">@error('business_name') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group ad-user">
                      <label>Email <span class="text-danger">*</span>
                      </label>
                      <input type="text" name="business_email" class="form-control" value="{{ old('business_email') }}">
                      <div class="text text-danger" id="error-business_email">@error('business_email') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
              
                  <div class="col-md-4">
                    <div class="form-group ad-user">
                      <label>Phone Number<span class="text-danger">*</span>
                      </label>
                        <div class="phone-input-group d-flex">
                      <select name="business_country_code" class="form-select form-control countryCodeSelect">
                              <option value="">Code</option>
                            </select>
                      <input type="text" name="business_phone" maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="form-control" value="{{ old('business_phone') }}">
                                </div>
                      <div class="text text-danger" id="error-business_phone">
                        @error('business_phone') <p style="color:red;">{{ $message }}</p>@enderror  @error('business_country_code') <p style="color:red;">{{ $message }}</p>@enderror</div>
                    </div>
                  </div>
                <div class="col-md-12">
                    <div class="form-group ad-user">
                      <label>Address <span class="text-danger">*</span>
                      </label>
                     <textarea name="business_address" class="form-control">{{ old('business_address') }}</textarea>

                      <div class="text text-danger" id="error-business_address">@error('business_address') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-header bg-light py-1">
                <div class="row align-items-center">
                  <div class="col">
                    <h4 class="form-section mt-2 mb-2"> Information on Goods or Services</h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row aplication-frm">
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Product or Service Purchased<span class="text-danger">*</span>
                      </label>
                      <input type="text" name="goods" class="form-control" value="{{ old('goods') }}">
                      <div class="text text-danger" id="error-goods">@error('goods') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Brand <span class="text-danger">*</span>
                      </label>
                      <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                      <div class="text text-danger" id="error-brand">@error('brand') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Model/Serial Number <span class="text-danger">*</span>
                      </label>
                      <input type="text" name="serial" class="form-control" value="{{ old('serial') }}">
                      <div class="text text-danger" id="error-serial">@error('serial') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Category <span class="text-danger">*</span></label>
                      <div class="rela-icon">
                        <select class="form-control" name="category">
                          <option value="" selected disabled>Select</option>
                           @foreach($complaintCategory as $category)
                          <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}
                          </option>
                        @endforeach
                        </select>
                        <i class="fa-solid fa-caret-down"></i>
                        <div class="text text-danger" id="error-category">@error('category') <p style="color:red;">{{ $message }}</p>@enderror </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Date of Purchase <span class="text-danger">*</span>
                      </label>
                      <input type="date" name="date_purchase" class="form-control" value="{{ old('date_purchase') }}" id="date_of_purchase">
                      <div class="text text-danger" id="error-date_purchase">@error('date_purchase') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Warranty Period <span class="text-danger">*</span></label>
                      <div class="rela-icon">
                        <select class="form-control" name="warranty">
                          <option value="" selected disabled>Select</option>
                          <option value="None">None</option>
                          <option value="14 days or less" {{ old('warranty') == '14 days or less' ? 'selected' : '' }}>14 days or less</option>
                          <option value="1 month" {{ old('warranty') == '1 month' ? 'selected' : '' }}>1 month</option>
                          <option value="2 month" {{ old('warranty') == '2 month' ? 'selected' : '' }}>2 month</option>
                          <option value="3 month" {{ old('warranty') == '3 month' ? 'selected' : '' }}>3 month</option>
                        </select>
                        <i class="fa-solid fa-caret-down"></i>
                        <div class="text text-danger" id="error-warranty">@error('warranty') <p style="color:red;">{{ $message }}</p>@enderror </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Hire Purchase Item<span class="text-danger">*</span>
                      </label>
                      <div class="rela-icon">
                        <select class="form-control" name="hire_purchase">
                          <option value="" selected disabled>Select</option>
                          <option value="0" {{ old('hire_purchase') == '0' ? 'selected' : '' }}>Yes</option>
                          <option value="1" {{ old('hire_purchase') == '1' ? 'selected' : '' }}>No</option>
                        </select>
                        <i class="fa-solid fa-caret-down"></i>
                        <div class="text text-danger" id="error-hire_purchase">@error('hire_purchase') <p style="color:red;">{{ $message }}</p>@enderror </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ad-user">
                      <label>Did you sign a contract? <span class="text-danger">*</span>
                      </label>
                      <div class="rela-icon">
                        <select class="form-control" name="contract">
                          <option value="" selected disabled>Select</option>
                          <option value="0" {{ old('contract') == '0' ? 'selected' : '' }}>Yes</option>
                          <option value="1" {{ old('contract') == '1' ? 'selected' : '' }}>No</option>
                        </select>
                        <i class="fa-solid fa-caret-down"></i>
                        <div class="text text-danger" id="error-contract">@error('contract') <p style="color:red;">{{ $message }}</p>@enderror </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group ad-user">
                      <label>Additional Statement
                      </label>
                      <div class="rela-icon">
                        <textarea rows="5" type="text" name="additional_statement" class="form-control">{{ old('additional_statement') }}</textarea>
                        <div class="text text-danger">@error('additional_statement') <p style="color:red;">{{ $message }}</p>@enderror </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 position-relative">
                    <div class="form-group ad-user">
                      <label>Copy of Receipt/Contract/Agreement <span class="text-danger">*</span>
                      </label>
                      <div class="upload-box mx-auto" style="max-width: 500px;">
                      <div class="upload-icon"></div>
                      <div class="upload-text">Upload a file using the button below</div>
                        <label for="fileUpload" class="upload-btn">Browse</label>
                        <input type="file" id="fileUpload" name="documents[]" accept="image/*,.pdf,.doc,.docx" multiple><br>
                      </div>
                      <div class="text text-danger">@error('documents') <p style="color:red;">{{ $message }}</p>@enderror </div>
                      <span class="font-text">Only .jpg, .jpeg, .png, .pdf, .doc, and .docx files are accepted. Maximum file size: 2 MB.</span>
                      <div id="fileList"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-header bg-light py-1">
                <div class="row align-items-center">
                  <div class="col">
                    <h4 class="form-section mb-2"> Willingness to attend Proceedings  <span class="text-danger">*</span></h4>
                  </div>
                </div>
              </div>
              <div class="card-body pt-2">
                <div class="row aplication-frm">
                  <div class="col-xl-12 mb-4">
                    <div class="form-group  ad-user check-boxx">
                      <input type="checkbox" name="certify"><p>I certify the above information to be truthful and accurate to the best of my knowledge and belief. I am willing to testify to the same at any proceedings directly related to this complaint if required to do so.</p> 
                    </div>
                    <div class="text text-danger" id="error-certify">@error('certify') <p style="color:red;">{{ $message }}</p>@enderror </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Date <span class="text-danger">*</span>
                      </label>
                      <input type="date" name="date" class="form-control" value="{{ old('date') }}">
                      <div class="text text-danger" id="error-date">@error('date') <p style="color:red;">{{ $message }}</p>@enderror </div>
                    </div>
                  </div>
                  <div class="col-xl-12 text-center">
                    <button type="submit" class="searc-btn mt-3" id="submit-btn"> Submit </button>
                  </div>
                </div>
              </div>
            </div>
        	</form>
          </div>
        </div>
      </div>
    </div>
  </div> 
  <script>
  const fileInput = document.getElementById('fileUpload');
  const fileListDisplay = document.getElementById('fileList');

  fileInput.addEventListener('change', function () {
    if (fileInput.files.length > 0) {
      let list = '<ul class="list-unstyled">';
      for (let i = 0; i < fileInput.files.length; i++) {
        list += `<li>📄 ${fileInput.files[i].name}</li>`;
      }
      list += '</ul>';
      fileListDisplay.innerHTML = list;
    } else {
      fileListDisplay.innerHTML = '';
    }
  });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_of_purchase').setAttribute('max', today);
    });
</script>
@endsection