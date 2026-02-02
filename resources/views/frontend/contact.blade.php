@extends('frontend.layout.app')
@section('title', @$title)
@section('content')
<style>
    .phone-icon{
            position: relative;
    top: 16px;
    right: 22px;
    }
</style>
    <div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
        <div class="container">
            <div class="breadcumb-content">
                <h2 class="breadcumb-title">Contact Us</h2>
            </div>
        </div>
    </div>
    <div class="space" id="contact-sec" style="background-color:#f7f7f7;">
        <div class="container">
            <div class="row gy-40">
                <div class="col-md-7">
                    {{-- <div class="title-area mb-10">
                        <h2 class="sec-title">Consumer Protection Laws & Penalties</h2>
                    </div> --}}
                    <div class="#">
                        {{-- <div class="aplication-frm">
            <div class="appy-now">
              <span class="Apple-style-span">
                <a href="{{ url('public/frontend/pdf/dummy.pdf') }}" download>
                  <i class="fa fa-download" aria-hidden="true"></i>&nbsp; Download the Latest Consumer Affairs Documents, Guidelines and Handbook. </a>
              </span>
              <span class="Apple-style-span1">
                <a href="{{ url('public/frontend/pdf/dummy.pdf') }}" download> Download </a>
              </span>
            </div>
            <div class="appy-now">
              <span class="Apple-style-span">
                <a href="{{ url('public/frontend/pdf/dummy.pdf') }}" download>
                  <i class="fa fa-download" aria-hidden="true"></i>&nbsp; Download the Latest Consumer Affairs Documents, Guidelines. </a>
              </span>
              <span class="Apple-style-span1">
                <a href="{{ url('public/frontend/pdf/dummy.pdf') }}" download> Download </a>
              </span>
            </div>
          </div> --}}
                        <div class="contact-info-wrap">
                            <div class="contact-info">
                                <div class="contact-info_icon">
                                    <i class="fa-light fa-location-dot"></i>
                                </div>
                                <div class="media-body">
                                    <h3 class="box-title">Our Address</h3>
                                    <p class="contact-info_text">{{ $setting['company_address'] }}</p>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info_icon">
                                    <i class="fa-light fa-phone"></i>
                                </div>
                                <div class="media-body">
                                    <h3 class="box-title">Phone Number</h3>
                                    <span class="contact-info_text">
                                        <a href="tel:{{ $setting['phone'] }}">{{ $setting['phone'] }}</a>
                                    </span>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info_icon">
                                    <i class="fa-light fa-envelope"></i>
                                </div>
                                <div class="media-body">
                                    <h3 class="box-title">Email Address</h3>
                                    <span class="contact-info_text">
                                        <a href="mailto:{{ $setting['email_address'] }}">{{ $setting['email_address'] }}</a>
                                    </span>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info_icon">
                                    <i class="fa-light fa-clock"></i>
                                </div>
                                <div class="media-body">
                                    <h3 class="box-title">Working Time</h3>
                                    <span class="contact-info_text">Mon-Fri 8am – 4pm <span style="color:red;">Sunday
                                            Closed</span></span>
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-35">Follow The Social Media:</h5>
                        <div class="th-social  footer-social style2">
                            <a href="{{ $setting['social_fb'] }}">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="{{ $setting['social_twitter'] }}">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="{{ $setting['linked_in'] }}">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="{{ $setting['social_instagram'] }}">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="contact-form-wrapper">
                        <form action="{{ route('frontend.send-message') }}" method="POST" class="">
                            @csrf
                            <h3 class="form-title text-center">Get In Touch</h3>
                            <div class="row">
                                <div class="form-group col-12">
                                    <i class="fa-sharp fa-light fa-user"></i>
                                    <input type="text" class="form-control" name="name" placeholder="Your Name"
                                        value="{{ old('name') }}">
                                    <div class="text text-danger">
                                        @error('name')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <i class="fa-sharp fa-regular fa-envelope"></i>
                                    <input type="text" class="form-control" name="email" placeholder="Email Address"
                                        value="{{ old('email') }}">
                                    <div class="text text-danger">
                                        @error('email')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                               
                                <div class="form-group col-12">
                                
                        
                    
                        <div class="phone-input-group d-lg-flex">
                          <select name="country_code" class="countryCodeSelect form-control country-CodeSelect"></select>

                          <input
                            type="text"
                            name="phone"
                            maxlength="10"
                            class="form-control"
                            value="{{ old('phone') }}"
                            placeholder="Enter phone"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                             <i class="fal fa-phone phone-icon"></i>
                        </div>

                        @error('country_code')
                          <p class="error-message">{{ $message }}</p>
                        @enderror
                        @error('phone')
                            <p style="color:red;">{{ $message }}</p>
                        @enderror
                      
                                </div>
                                <div class="form-group col-12">
                                    <select name="category" id="category">
                                        <option value="">Select Category</option>
                                        @if(isset($categories) && count($categories)>0)
                                            @foreach($categories as $values)
                                                <option value="{{$values->id}}" {{ old('category') == $values->id ? 'selected' : '' }} >{{ucfirst($values->name)}}</option>
                                            @endforeach
                                            <option {{ old('category') == '0' ? 'selected' : '' }} value="0">Other</option>
                                            option
                                        @endif
                                    </select>
                                    <div class="text text-danger">
                                        @error('category')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <i class="fal fa-comment"></i>
                                    <textarea name="message" cols="30" rows="3" class="form-control" placeholder="Message">{{ old('message') }}</textarea>
                                    <div class="text text-danger">
                                        @error('message')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-btn col-12">
                                    <button class="th-btn fw-btn">Send Messages Now</button>
                                </div>
                            </div>
                            <p class="form-messages mb-0 mt-3"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map-sec">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.7310056272386!2d89.2286059153658!3d24.00527418490799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fe9b97badc6151%3A0x30b048c9fb2129bc!2sThemeholy!5e0!3m2!1sen!2sbd!4v1651028958211!5m2!1sen!2sbd"
            allowfullscreen="" loading="lazy"></iframe>
    </div>
@endsection
