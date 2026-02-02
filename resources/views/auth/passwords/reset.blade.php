@extends('layouts.app')





@section('content')

<style>
    .toggle-password {
    position: absolute;
    top: 50%;
    right: 30px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    z-index: 2;
}

</style>


<div class="login-bg">
   <div class="container">
      <div class="row">
         <div class="col-12 col-lg-4 mx-auto">
            <div class="d-flex align-items-center min-vh-100">
               <div class="w-100 d-block p-4 mt-lg-5">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="text-center">
                           <h3 class="font-weight-650">{{ __('Reset Password') }}</h3>
                        </div>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <div class="form-group mb-3 position-relative">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 position-relative">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="new-password">
                                <span class="toggle-password" toggle="#password"><i class="fa fa-eye"></i></span>
                                <!-- <span>Password must be alphanumeric</span> -->
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 position-relative">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                <span class="toggle-password" toggle="#password-confirm"><i class="fa fa-eye"></i></span>
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button type="submit" class="login-btn">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>

                        </form>
                        <!-- end row -->


                     </div>


                     <!-- end .padding-5 -->


                  </div>


                  <!-- end col -->


               </div>


               <!-- end row -->


            </div>


            <!-- end .w-100 -->


         </div>


         <!-- end .d-flex -->


      </div>


      <!-- end col-->


   </div>


   <!-- end row -->


</div>


<!-- end container -->


</div>

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


@endsection


