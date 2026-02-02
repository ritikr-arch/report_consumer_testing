@extends('layouts.app')





@section('content')


<div class="login-bg">


   <div class="container">


      <div class="row">


         <div class="col-12 col-lg-4 mx-auto">


            <div class="d-flex align-items-center min-vh-100">


               <div class="w-100 d-block p-4 mt-lg-5">


                  <div class="row">


                     <div class="col-lg-12">


                        <div class="text-center">


                           <h2 class="font-weight-700">Welcome Back</h2>


                           <h5 class=" mb-4">Sign in to your account</h5>


                        </div>


                        <form method="POST" action="{{ route('login') }}">


                        @csrf


                            <div class="form-group mb-3 position-relative">


                                <input class="form-control ps-5 @error('email') is-invalid @enderror" type="email" id="email" name="email"  value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Username">


                                <div class="form-icon">


                                    <svg xmlns="http://www.w3.org/2000/svg" width="31" height="30" viewBox="0 0 31 30" fill="none">


                                        <path d="M14.1832 14.3782C17.2535 14.3782 19.7424 11.8892 19.7424 8.81898C19.7424 5.74871 17.2535 3.25977 14.1832 3.25977C11.113 3.25977 8.62402 5.74871 8.62402 8.81898C8.62402 11.8892 11.113 14.3782 14.1832 14.3782Z" fill="#9A9A9A"/>


                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.36525 22.1102C5.36378 21.7194 5.45118 21.3334 5.62087 20.9813C6.15336 19.9163 7.655 19.3519 8.90104 19.0963C9.79968 18.9045 10.7107 18.7764 11.6274 18.7129C13.3246 18.5638 15.0316 18.5638 16.7287 18.7129C17.6453 18.7771 18.5563 18.9052 19.4551 19.0963C20.7011 19.3519 22.2028 19.8631 22.7353 20.9813C23.0765 21.699 23.0765 22.5321 22.7353 23.2497C22.2028 24.368 20.7011 24.8792 19.4551 25.1241C18.5575 25.3238 17.6461 25.4556 16.7287 25.5182C15.3474 25.6353 13.9596 25.6566 12.5753 25.5821C12.2558 25.5821 11.9469 25.5821 11.6274 25.5182C10.7134 25.4563 9.80555 25.3246 8.91169 25.1241C7.655 24.8792 6.16401 24.368 5.62087 23.2497C5.45205 22.8936 5.36473 22.5043 5.36525 22.1102Z" fill="#9A9A9A"/>


                                    </svg>


                                </div>


                                @error('email')


                                    <span class="invalid-feedback" role="alert">


                                        <strong>{{ $message }}</strong>


                                    </span>


                                @enderror


                            </div>


                            <div class="form-group mb-3 position-relative">


                                <a href="pages-recoverpw.html" class="text-muted float-end"><small></small></a>


                               <div class="position-relative">
                                    <input class="form-control ps-5 @error('password') is-invalid @enderror" 
                                          type="password" id="password" name="password" required 
                                          autocomplete="current-password" placeholder="Enter your password">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3" 
                                          onclick="togglePassword()" style="cursor: pointer;">
                                       <i class="fa-solid fa-eye" id="toggleIcon"></i>
                                    </span>
                                 </div>


                              <div class="form-icon">


                                 <svg xmlns="http://www.w3.org/2000/svg" width="18" height="23" viewBox="0 0 18 23" fill="none">


                                    <path d="M14.4991 7.92353V5.74909C14.4991 2.70487 12.1072 0.312988 9.063 0.312988C6.01878 0.312988 3.62689 2.70487 3.62689 5.74909V7.92353C1.77862 7.92353 0.365234 9.33692 0.365234 11.1852V18.7957C0.365234 20.644 1.77862 22.0574 3.62689 22.0574H14.4991C16.3474 22.0574 17.7608 20.644 17.7608 18.7957V11.1852C17.7608 9.33692 16.3474 7.92353 14.4991 7.92353ZM5.80134 5.74909C5.80134 3.90081 7.21472 2.48743 9.063 2.48743C10.9113 2.48743 12.3247 3.90081 12.3247 5.74909V7.92353H5.80134V5.74909ZM10.1502 16.6213C10.1502 17.2736 9.71533 17.7085 9.063 17.7085C8.41066 17.7085 7.97578 17.2736 7.97578 16.6213V13.3596C7.97578 12.7073 8.41066 12.2724 9.063 12.2724C9.71533 12.2724 10.1502 12.7073 10.1502 13.3596V16.6213Z" fill="#9A9A9A"/>


                                 </svg>


                              </div>


                              @error('password')


                                  <span class="invalid-feedback" role="alert">


                                      <strong>{{ $message }}</strong>


                                  </span>


                              @enderror


                           </div>


                           <div class="form-group mb-3 mt-4 d-flex justify-content-between">


                              <div>


                                 <!-- <input class="form-check-input" type="checkbox" id="checkbox-signin"


                                    checked>


                                 <label class="form-check-label ms-2" for="checkbox-signin">Remember


                                 me</label> -->





                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>





                                <label class="form-check-label" for="remember">


                                    {{ __('Remember Me') }}


                                </label>





                              </div>


                              <!-- <p class="mb-0">Forgot password?</p> -->


                              @if (Route::has('password.request'))


                                  <a class="mb-0" href="{{ route('password.request') }}">


                                      {{ __('Forgot Password?') }}


                                  </a>


                              @endif


                           </div>


                           <div class="form-group mb-0 text-center">


                            <button type="submit" class="login-btn">


                                {{ __('Login') }}


                            </button>


                           <!-- <a class="login-btn" href="index.php" class=" w-100" type="submit"> Sign In </a> -->


                              <!-- <button class=" w-100" type="submit"> Sign In </button> -->


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
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}
</script>
@endsection


