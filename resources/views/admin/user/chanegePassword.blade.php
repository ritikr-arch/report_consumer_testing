@extends('admin.layouts.app')
@section('title', @$title)
@section('content')
<style>
  .toggle-password {
    position: absolute;
    top: 38px;
    right: 26px;
    cursor: pointer;
    z-index: 10;
    font-size: 20px;
    color: #6c757d;
}

</style>
<div class="px-3 mt-5">
    <!-- Start Content-->
    <div class="container-fluid">

           <form  action="{{ route('admin.password.update') }}"  id="passwordForm" method="POST" enctype="multipart/form-data">
            @csrf

           

          

            <div class="row mt-3">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body">

                            <div class="row align-items-center d-flex mb-3">
                                <div class="col-xl-12 text-center">
                                    <h4 class="header-title mb-0 font-weight-bold">Change Password</h4>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Old Password --}}
                                <div class="col-md-12">
                                    <div class="form-group ad-user">
                                        <label for="old_password">Old Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="old_password" name="old_password"
                                           maxlength="20" required>
                                        @error('old_password')
                                            <small class="text-danger font-weight-bold">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                               {{-- New Password --}}
                          <div class="col-md-12">
                              <div class="form-group ad-user position-relative">
                                  <label for="password">New Password <span class="text-danger">*</span></label>
                                  <input type="password" class="form-control" id="password" name="password"
                                      maxlength="15" required>
                                  <span class="toggle-password" toggle="#password">
                                      <i class="fa fa-eye"></i>
                                  </span>
                                  @error('password')
                                      <small class="text-danger font-weight-bold">{{ $message }}</small>
                                  @enderror
                              </div>
                          </div>

                          {{-- Confirm Password --}}
                          <div class="col-md-12">
                              <div class="form-group ad-user position-relative">
                                  <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                      maxlength="15" required>
                                  <span class="toggle-password" toggle="#password_confirmation">
                                      <i class="fa fa-eye"></i>
                                  </span>
                                  @error('password_confirmation')
                                      <small class="text-danger font-weight-bold">{{ $message }}</small>
                                  @enderror
                              </div>
                          </div>


                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary mt-4">Submit</button>
                                </div>
                                <div class="col-md-1"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- end row-->
    </div>
    <!-- container -->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script>
    document.querySelectorAll('.toggle-password').forEach(function (element) {
        element.addEventListener('click', function () {
            const target = document.querySelector(this.getAttribute('toggle'));
            const icon = this.querySelector('i');

            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>

<script>
$(document).ready(function() {
  $.validator.addMethod("alphanumeric", function(value, element) {
    // Password must contain at least one letter and one number
    return this.optional(element) || /^(?=.*[a-zA-Z])(?=.*\d).+$/.test(value);
  }, "Please use alphanumeric password.");

  $("#passwordForm").validate({
    rules: {
      old_password: {
        required: true,
        minlength: 8,
        maxlength: 20
      },
      password: {
        required: true,
        minlength: 8,
        maxlength: 20,
        alphanumeric: true,
        equalTo: "#password_confirmation" // confirm password validation
      },
      password_confirmation: {
        required: true,
        minlength: 8,
        maxlength: 20
      }
    },
    messages: {
      old_password: {
        required: "Please enter your old password.",
        minlength: "Password must be at least 8 characters.",
        maxlength: "Password cannot be more than 20 characters."
      },
      password: {
        required: "Please enter a new password.",
        minlength: "Password must be at least 8 characters.",
        maxlength: "Password cannot be more than 20 characters.",
        alphanumeric: "Please use alphanumeric password.",
        equalTo: "Password confirmation does not match."
      },
      password_confirmation: {
        required: "Please confirm your password.",
        minlength: "Password must be at least 8 characters.",
        maxlength: "Password cannot be more than 20 characters."
      }
    }
  });
});

</script>


@endsection
