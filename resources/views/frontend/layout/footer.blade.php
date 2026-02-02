<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: start;
    }

    .rating input {
        display: none;
    }

    .rating label {
        font-size: 22px;
        color: gray;
        top: 5px;
        cursor: pointer;
    }

    .rating input:checked~label {
        color: gold;
    }

    .rating input[type="radio"]~label::before {
        display: none;
    }

    .rating input[type="radio"]~label {
        position: relative;
        padding-left: 10px;
    }

    .feed-dialog {
        top: 80px;
        left: 0px;
    }

    @media(max-width:767px) {
        .feed-dialog {
            top: 120px;
            left: 0px;
        }
    }
</style>

<!-- Start of feedback -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md feed-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p id="msg" class="text-center text-success"></p>
            <div class="modal-body">
                <form id="feedbackForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name">
                                <div class="text text-danger" id="name_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email">
                                <div class="text text-danger" id="email_error"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-2">
                                <label for="comments" class="form-label">Comments <span class="text-danger">*</span></label>
                                <textarea name="comments" class="form-control" id="comments" rows="2" placeholder="Share your thoughts"></textarea>
                                <div class="text text-danger" id="comments_error"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div style="display:flex; align-items:center;">
                                <label class="form-label me-2">Rating <span class="text-danger">*</span></label>
                                <div class="rating">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5">★</label>

                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4">★</label>

                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3">★</label>

                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2">★</label>

                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1">★</label>
                                </div>
                            </div>
                            <div class="text text-danger" id="rating_error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 0;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- Use type="button" so form is not submitted normally -->
                <button type="button" class="btn btn-danger" id="feedback-btn">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- End of feedback form -->

<footer class="footer-wrapper footer-layout4 position-relative">
    <div class="widget-area overlay">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-3">
                    <div class="widget footer-widget mb-0">
                        <div class="th-widget-about">
                            <div class="about-logo">
                                @php
                                    use App\Models\Setting;

                                    $setting = Setting::find('1');
                                @endphp
                                <a href="{{ route('frontend.home') }}">
                                    <img src="{{ asset('frontend/img/consumer-affairs-logo.png') }}" alt="Poolax"
                                        width="240px;" style="padding:5px 10px; background:#fff;">
                                </a>
                            </div>
                            <p class="text-white">The Consumer Affairs Department of St. Kitts & Nevis is dedicated
                                to protecting consumer rights, promoting education, and ensuring fair trade practices.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="widget widget_nav_menu  footer-widget">
                                <h3 class="widget_title">Quick link</h3>
                                <div class="menu-all-pages-container">
                                    <ul class="menu">
                                        <li>
                                            <a href="{{ route('frontend.about') }}"> About us </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.stores') }}">Price Collection</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.publication.articles') }}">Articles</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.complaint.form') }}">File a complaint</a>
                                        </li>
                                        <li>
                                            <a href="" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">Feedback </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="widget widget_nav_menu footer-widget">
                                <h3 class="widget_title"> Important Link</h3>
                                <div class="menu-all-pages-container">
                                    <ul class="menu">

                                        {{-- <li>
                                            <a href="{{ route('frontend.privacy') }}"> Privacy Policy </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.terms') }}"> Terms & Condition </a>
                                        </li> --}}
                                        <li>
                                            <a href="{{ route('frontend.disclaimers') }}"> Disclaimer </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.contact') }}"> Contact Us</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.faq') }}">FAQ</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="widget footer-widget mb-0">
                        <h3 class="widget_title"> Official Information</h3>
                        <div class="th-widget-about">
                            <p class="office-inform">
                                <i class="fa fa-phone"></i>&nbsp; {{ $setting['phone'] }}
                            </p>
                            <p class="office-inform">
                                <i class="far fa-envelope"></i>&nbsp; {{ $setting['email_address'] }}
                            </p>
                            <p class="office-inform">
                                <i class="fas fa-map-marker-alt"></i>&nbsp; {{ $setting['company_address'] }}
                            </p>
                            <div class="th-social  footer-social">
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
                </div>
            </div>
        </div>
        <div class="copyright-wrap">
            <div class="row align-items-center">
                <div class="col-12">
                    <p class="copyright-text text-white text-center">Copyright © {{ date('Y') }} <a
                            href="#" style="color:#bf1e2e !important;">Consumer Affairs</a>. All Rights
                        Reserved. </p>
                </div>
            </div>
        </div>
</footer>

<!-- Jquery -->
<script src="{{ asset('frontend/js/vendor/jquery-3.6.0.min.js') }}"></script>
<!-- Slick Slider -->
<script src="{{ asset('frontend/js/slick.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<!-- Isotope Filter -->
<script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
<!-- Magnific Popup -->
<script src="{{ asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>
<!-- Range Slider -->
<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
<!-- imagesloaded  -->
<script src="{{ asset('frontend/js/imagesloaded.pkgd.min.js') }}"></script>
<!-- odometer -->
<script src="{{ asset('frontend/js/odometer.js') }}"></script>
<!-- Nice Select -->
<script src="{{ asset('frontend/js/nice-select.min.js') }}"></script>
<!-- circle-progress -->
<script src="{{ asset('frontend/js/circle-progress.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.ripples.js') }}"></script>
<!-- Main Js File -->
<script src="frontend/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<script>
    $(document).ready(function () {
        $('#exampleModal').on('hidden.bs.modal', function () {
            $('#feedbackForm')[0].reset();
            $('#name_error').text('');
            $('#email_error').text('');
            $('#comments_error').text('');
            $('#rating_error').text('');
            $('input[name="rating"]').prop('checked', false);
            $('#msg').text('');
        });
    });
</script>

<script>


    //Digital Signature Code
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    let drawing = false;
    let lastX = 0;
    let lastY = 0;

    // Set canvas dimensions to match CSS size
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;

    canvas.addEventListener('mousedown', (e) => {
        drawing = true;
        const rect = canvas.getBoundingClientRect();
        lastX = e.clientX - rect.left;
        lastY = e.clientY - rect.top;
    });

    canvas.addEventListener('mouseup', () => {
        drawing = false;
    });

    canvas.addEventListener('mouseout', () => {
        drawing = false;
    });

    canvas.addEventListener('mousemove', (e) => {
        if (!drawing) return;
        const rect = canvas.getBoundingClientRect();
        const currentX = e.clientX - rect.left;
        const currentY = e.clientY - rect.top;

        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
    });

    function clearPad() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    const fileInput = document.getElementById('fileUpload');
    const fileListDisplay = document.getElementById('fileList');

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            let list = '<ul class="list-unstyled">';
            for (let i = 0; i < fileInput.files.length; i++) {
                list += `<li> ${fileInput.files[i].name}</li>`;
            }
            list += '</ul>';
            fileListDisplay.innerHTML = list;
        } else {
            fileListDisplay.innerHTML = '';
        }
    });
</script>
<script>
    // Validation 
    $('#complaint-form').on('submit', function(e) {
        e.preventDefault();

        // Clear old errors
        $('.error').text('');

        let isValid = true;

        // Business Name
        let business_name = $('#business_name').val().trim();
        if (business_name === '') {
            $('#error-business_name').text('The business name field is required.');
            isValid = false;
        } else {
            $('#error-business_name').text('');
        }

        // Business Email
        let business_email = $('#business_email').val().trim();
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (business_email === '') {
            $('#error-business_email').text('The business email field is required.');
            isValid = false;
        } else if (!emailRegex.test(business_email)) {
            $('#error-business_email').text('The business email field must be a valid email address.');
            isValid = false;
        } else {
            $('#error-business_email').text('');
        }


        // Business Phone 
        let countryCodeSelect = $('#countryCodeSelect').val().trim();

        if (countryCodeSelect === '') {
            $('#error-country-code').text('The business Country Code field is required.');
            isValid = false;
        } else {
            $('#error-country-code').text('');
        }
        // Business Phone 
        let business_phone = $('#business_phone').val().trim();

        if (business_phone === '') {
            $('#error-business_phone').text('The business phone field is required.');
            isValid = false;
        } else if (!/^\d{7,10}$/.test(business_phone)) {
            $('#error-business_phone').text('The business phone field must be between 7 and 10 digits.');
            isValid = false;
        } else {
            $('#error-business_phone').text('');
        }

        // Business Address 
        let business_address = $('#business_address').val().trim();

        if (business_address === '') {
            $('#error-business_address').text('The business address field is required.');
            isValid = false;
        } else {
            $('#error-business_address').text('');
        }

        // Product or service purchased
        let business_goods = $('#business_goods').val().trim();
        if (business_goods === '') {
            $('#error-goods').text('The product or service purchased field is required.');
            isValid = false;
        } else {
            $('#error-goods').text('');
        }

        // Brand
        let business_brand = $('#business_brand').val().trim();
        if (business_brand === '') {
            $('#error-brand').text('The brand field is required.');
            isValid = false;
        } else {
            $('#error-brand').text('');
        }

        // Modal/Serial Number
        let serial = $('#serial').val().trim();
        if (serial === '') {
            $('#error-serial').text('The model/serial number field is required.');
            isValid = false;
        } else {
            $('#error-serial').text('');
        }

        // Category
        let category = $('#category').val();

        if (!category) {
            $('#error-category').text('The category field is required.');
            isValid = false;
        } else {
            $('#error-category').text('');
        }

        // Date of purchase
        let date_of_purchase = $('#date_of_purchase').val();

        if (date_of_purchase === '') {
            $('#error-date_purchase').text('The date of purchase field is required.');
            isValid = false;
        } else {
            $('#error-date_purchase').text('');
        }

        // Warranty
        let warranty = $('#warranty').val();
        if (!warranty) {
            $('#error-warranty').text('The warranty field is required.');
            isValid = false;
        } else {
            $('#error-warranty').text('');
        }


        // Purchase
        let hire_purchase = $('#hire_purchase').val();
        if (!hire_purchase) {
            $('#error-hire_purchase').text('The hire purchase item field is required.');
            isValid = false;
        } else {
            $('#error-hire_purchase').text('');
        }

        // Contract
        let contract = $('#contract').val();
        if (!contract) {
            $('#error-contract').text('The contract field is required.');
            isValid = false;
        } else {
            $('#error-contract').text('');
        }

        // Certify
        let certify = $('#certify').val().trim();
        if (!certify) {
            $('#error-certify').text('The certify field is required.');
            isValid = false;
        } else {
            $('#error-certify').text('');
        }

        // Signature Pad 
        const canvas = document.getElementById('signature-pad');
        const signatureImage = canvas.toDataURL();

        const blankCanvas = document.createElement('canvas');
        blankCanvas.width = canvas.width;
        blankCanvas.height = canvas.height;
        const blankData = blankCanvas.toDataURL();

        if (signatureImage === blankData) {
            $('#sign_error').css('display', 'block').html(
                '<p style="color:#dc3545;">The signed field is required.</p>');
            isValid = false;
        } else {
            $('#sign_error').hide();
            $('#signature_image').val(signatureImage);
        }

        // Date
        let submit_date = $('#submit_date').val();
        if (submit_date === '') {
            $('#error-date').text('The date field is required.');
            isValid = false;
        } else {
            $('#error-date').text('');
        }

      let captchaResponse = grecaptcha.getResponse();
        if (!captchaResponse) {
            $('#error-captcha').text('Please complete the reCAPTCHA to proceed.');
            isValid = false;
        } else {
            $('#error-captcha').text('');
        }

        // Documents 
        const fileInput = $('#fileUpload')[0];

        const file = fileInput.files[0];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        const maxSizeMB = 2;

        $('#error-documents').text('');

        if (!file) {
            $('#error-documents').text('The copy of receipt/contract/agreement is required.');
            isValid = false;
        } else {
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const fileSizeMB = file.size / (1024 * 1024);

            if (!allowedExtensions.includes(fileExtension)) {
                $('#error-documents').text('Only JPG, PNG, GIF, PDF, DOC, and DOCX files are allowed.');
                isValid = false;
            } else if (fileSizeMB > maxSizeMB) {
                $('#error-documents').text('File must be less than ' + maxSizeMB + ' MB.');
                isValid = false;
            }
        }

        if (isValid) {
            this.submit();
        }
    });
</script>
<script>
  $(document).ready(function() {
    $('#feedback-btn').click(function(e) {
        e.preventDefault();

        // Clear previous error messages
        $('#name_error').text('');
        $('#email_error').text('');
        $('#comments_error').text('');
        $('#rating_error').text('');
        $('#msg').text('').show();

        var name = $('#name').val();
        var email = $('#email').val();
        var comments = $('#comments').val();
        var rating = $("input[name='rating']:checked").val();

        $.ajax({
            url: "{{ route('frontend.submit-feedback') }}",
            method: 'POST',
            data: {
                name: name,
                email: email,
                comments: comments,
                rating: rating,
                _token: '{{ csrf_token() }}'
            },
            dataType: "JSON",
            success: function(response) {
                $('#msg').html('Thank you! Your feedback has been submitted successfully.').show();
                $('#feedbackForm')[0].reset();

                // Hide error messages after success
                $('#name_error').hide();
                $('#email_error').hide();
                $('#comments_error').hide();
                $('#rating_error').hide();

                // Fade out message after 5 seconds
                $('#msg').delay(5000).fadeOut('slow');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;

                    $('#name_error').text(errors.name ? errors.name[0] : '').show();
                    $('#email_error').text(errors.email ? errors.email[0] : '').show();
                    $('#comments_error').text(errors.comments ? errors.comments[0] : '').show();
                    $('#rating_error').text(errors.rating ? errors.rating[0] : '').show();
                } else {
                    // Other errors
                    $('#msg').html('Something went wrong. Please try again later.').show().delay(5000).fadeOut('slow');
                }
            }
        });
    });
});
</script>
<script>
    const $affectedElements = $("p, h1, h2, h3, h4, h5, h6, span, a, td");

    // Save original sizes and set limits
    $affectedElements.each(function () {
        const $this = $(this);
        const originalSize = parseFloat($this.css("font-size"));
        $this.data("orig-size", originalSize);
    });

    // Limits
    const minSize = 12;
    const maxSize = 22;

    $("#btn-increase").click(function () {
        changeFontSize(1);
    });

    $("#btn-decrease").click(function () {
        changeFontSize(-1);
    });

    $("#btn-origs").click(function () {
        $affectedElements.each(function () {
            const $this = $(this);
            const original = $this.data("orig-size");
            $this.css("font-size", original + "px");
        });
    });

    function changeFontSize(direction) {
        $affectedElements.each(function () {
            const $this = $(this);
            let currentSize = parseFloat($this.css("font-size"));
            let newSize = currentSize + direction;

            // Clamp within range
            if (newSize >= minSize && newSize <= maxSize) {
                $this.css("font-size", newSize + "px");
            }
        });
    }
</script>

<script>
  const dateObj = new Date();
const day = String(dateObj.getDate()).padStart(2, '0');
const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Months are 0-based
const year = dateObj.getFullYear();
const todayFormatted = `${day}-${month}-${year}`; // "22-05-2025"
document.querySelectorAll('input[type="date"]').forEach(input => {
  input.placeholder = "dd-mm-yyyy";
  input.setAttribute('max', todayFormatted); // Optional for native fallback
  flatpickr(input, {
    dateFormat: "d-m-Y",
    maxDate: todayFormatted // This disables future dates in Flatpickr
  });
});
</script>
<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function () {
    const defaultCode = "+1-869"; // Default selected
    const $select = $(".countryCodeSelect");

    fetch("https://restcountries.com/v3.1/all?fields=name,cca2,idd")
      .then(response => response.json())
      .then(data => {
        // Sort countries alphabetically by name
        data.sort((a, b) => a.name.common.localeCompare(b.name.common));

        data.forEach(country => {
          const name = country.name.common;
          const root = country.idd?.root || "";
          const suffix = country.idd?.suffixes?.[0] || "";
          const code = root + suffix;

          if (code) {
            const formattedCode = root === "+1" && suffix ? `${root}-${suffix}` : code;
            const flagUrl = `https://flagcdn.com/w20/${country.cca2.toLowerCase()}.png`;

            const option = new Option(`${formattedCode} ${name}`, formattedCode, false, false);
            $(option).attr('data-flag', flagUrl);
            $(option).attr('data-name', name);
            $(option).attr('data-code', formattedCode);
            $select.append(option);
          }
        });

        // Initialize Select2
        $select.select2({
          templateResult: formatCountry,
          templateSelection: formatCountry,
          escapeMarkup: markup => markup
        });

        // Set default value
        $select.val(defaultCode).trigger('change');
      });

    function formatCountry(state) {
      if (!state.id) {
        return state.text;
      }
      const flagUrl = $(state.element).data('flag');
      const name = $(state.element).data('name');
      const code = $(state.element).data('code');

      if (flagUrl && name && code) {
        return `<img src="${flagUrl}" style="width: 20px; vertical-align: middle; margin-right: 6px;" /> ${code} ${name}`;
      }
      return state.text;
    }
  });
</script>


</body>

</html>
