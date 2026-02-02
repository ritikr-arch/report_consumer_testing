@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

<style>
.modal-dialog.modal-lg.modal-dialog-centered.height-500 .modal-body {
    height: 440px;
    overflow: auto;
    text-align: justify;
}

.btn-close {
    padding: .25em .4em;
    color: var(--bs-btn-close-color);
    background: transparent var(--bs-btn-close-bg) center / .7em auto no-repeat;

}

.modal-content {
    border-radius: 12px;
    outline: 0;
}
</style>

<div class="px-3">

    <!-- Start Content-->

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center d-flex mb-3">
                            <div class="col-xl-5">
                                <h4 class="header-title mb-0 font-weight-bold">
                                    Contact Us
                                </h4>
                            </div>
                            <div class="col-12 col-md-7 col-lg-7">

                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()"><i
                                            class="fa-solid fa-filter"></i>&nbsp;Filter</button>

                                </div>

                            </div>
                        </div>



                        <div class="row mb-4">
                            <form action="{{route('admin.enquiry.filter')}}" method="get">
                                <hr>
                                <div id="dropdown" class="dropdown-container-filter" style="flex-wrap: nowrap;">
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                            value="{{ request('name') }}" maxlength="200">
                                    </div>
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="email" id="email"
                                            placeholder="Email" value="{{ request('email') }}" maxlength="200">
                                    </div>
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="phone" id="phone"
                                            placeholder="Phone" value="{{ request('phone') }}"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            maxlength="12">
                                    </div>
                                    <!--                            
                           <select class="form-select" name="type" aria-label="Default select example">
                              <option value="" selected="">Type</option>
                              <option {{ request('type') === 'general' ? 'selected' : '' }} value="general">General</option>
                              <option {{ request('type') === 'service' ? 'selected' : '' }} value="service">Service</option>
                           </select> -->

                                    <div class="filter-date">
                                        <input type="text" value="{{ request('start_date') }}" name="start_date" class="form-control"  placeholder="Start Date" id="start_date" autocomplete="off">
                                    </div>
                                    <div class="filter-date">
                                        <input type="text" value="{{ request('end_date') }}" name="end_date"
                                            class="form-control" placeholder="End Date" id="end_date" autocomplete="off">
                                    </div>

                                         <div class="d-flex"> 
                                        <button type="submit" class="d-fle searc-btn btn-sm">Search</button>
                                        <a href="{{route('admin.enquiry.list')}}" type="button" class="btn btn-secondary btn-sm">Reset</a>

                                    </div>

                                </div>

                            </form>

                        </div>



                        <div class="table-responsive white-space">

                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">

                                        <th style="min-width:7%;">S.No.</th>
                                        <th style="min-width:10%;">Name</th>
                                        <th style="min-width: 10%;">Category</th>
                                        <th style="min-width:20%;">Email</th>
                                        <th style="min-width:10%;">Phone</th>
                                        <th style="min-width:20%;">Message</th>
                                        <th style="min-width:120px;">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($data) && count($data)>0)
                                    @foreach($data as $key=>$value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <div style="display:flex;align-items: center;">
                                                @if($value->is_read == '0')
                                                    <img src="{{ asset('new.gif') }}" style="width: 40px; height: 40px; margin-right: 2px;">
                                                @endif
                                                {{ ucfirst($value->name) }}
                                                    
                                            </div>
                                            @if($value->is_read == '0')
                                                <a href="#" class="mark-as-read" data-id="{{ $value->id }}" style="color: #007bff; text-decoration: none;">
                                                Mark as Read
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ ($value->category_id == '0')?'Other':ucfirst(($value->enquiryCategory)?$value->enquiryCategory->name:'N/A') }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->country_code }} {{ $value->phone }}</td>
                                        @php
                                            $words = explode(' ', $value->message);
                                            $shortMessage = implode(' ', array_slice($words, 0, 15));
                                            $isLong = count($words) > 15;
                                        @endphp

                                        <td>
                                            {{ $shortMessage }}{{ $isLong ? '...' : '' }}

                                            @if($isLong)
                                                <a href="#" data-id="{{$value->id}}" class="read-more" data-comment="{!! htmlspecialchars($value->message, ENT_QUOTES) !!}" data-bs-toggle="modal" data-bs-target="#commentModal"> 
                                                Read More</a>

                                               
                                                
                                            @endif
                                        </td>

                                        <td>{{ customt_date_format($value->created_at) }}</td>
                                    </tr>
                                    @endforeach
                                    @else

                                    <tr>
                                        <td colspan="6">
                                            <p class="no_data_found">No Data found! </p>
                                        </td>
                                    </tr>


                                    @endif
                                </tbody>
                            </table>
                            @if (isset($data))
                            {{ $data->appends(request()->query())->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end row-->

    </div>

    <!-- container -->

</div>

</div>

</div>
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered height-500">
    <div class="modal-content">
      <div class="modal-header px-4">
        <h5 class="modal-title" id="commentModalLabel">View Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4 py-3" id="modalComment">

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Reusable Modal -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('.read-more').on('click', function(r) {
        var id = $(this).data('id');
        var _this = this;
        // if(id){
        //     $.ajax({
        //         url: "{{route('admin.enquiry.update')}}",
        //         type:'get',
        //         data:{id:id},
        //         success: function(response){
        //             if(response.success){
        //                 console.log(response.success)
        //                 $(_this).closest('tr').find('.status').remove();
        //                 // $(this).closest('tr').find('.status').remove()
        //             }else{
                      
        //             }
        //         }
        //     });
        // }
        
        // console.log(id);
      var commentHtml = $(this).data('comment');
      // console.log('this',commentHtml);
      $('#modalComment').html(commentHtml);
    });
  });

  $(document).on('click', '.mark-as-read', function (e) {
    e.preventDefault();

    var id = $(this).data('id');
    var _this = this;

    // If already read, stop action
    if ($(_this).find('.status').length > 0) {
        return;
    }

    Swal.fire({
        title: 'Mark as Read?',
        text: "Do you want to mark this comment as read?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{route('admin.enquiry.update')}}",
                type: 'GET',
                data: { id: id },
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            'Marked!',
                            'The comment has been marked as read.',
                            'success'
                        ).then(() => {
                        window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                }
            });
        }
    });
});
</script>

<script>

$(document).ready(function() {
   
    // var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
    //     backdrop: 'static',
    //     keyboard: false
    // });



    $('#zone').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.zone.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.text-danger').text('');
            },

            success: function(response) {
                // console.log(response);
                if (response.success) {
                    toastr.success(response.message)
                    modal.hide();
                    location.reload();
                }
            },

            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                $(".text-danger").text("");
                if (errors.name) {
                    $(".error-name").text(errors.name[0]);
                }
                if (errors.status) {
                    $(".error-status").text(errors.status[0]);
                }
                modal.show();
            }
        });
    });



    $(".toggleSwitch").on("change", function() {
        var status = $(this).is(":checked") ? 1 : 0;
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.zone.update.status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: status,
                id: id
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    toastr.success(response.message)
                }
            },
            error: function(xhr, status, error) {
                toastr.success(response.message)
            }
        });

    });


    $('#exampleModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#id').val('');
        $("#editImage").hide().attr('src', '');
        $("#formHeading").text('Add Zone');
        $(".btn-save").text('Add Zone');
    });

    $('body').on('click','.modal-close',function(e){
        console.log('fdsa');
        $('.ddsa').modal('hide');
    });


    function setupDropdown(dropdownButtonId) {
        const $dropdownButton = $('#' + dropdownButtonId);
        const $dropdownMenu = $dropdownButton.next();
        const $dropdownItems = $dropdownMenu.find('.dropdown-item');
        // Toggle dropdown visibility
        $dropdownButton.on('click', function() {
            $dropdownMenu.toggle();
        });



        // Update dropdown button text on item click

        $dropdownItems.on('click', function() {
            const selectedValue = $(this).data('value');
            $dropdownButton.html(selectedValue + ' <i class="fa fa-caret-down"></i>');
            $dropdownMenu.hide();
        });



        // Close dropdown when clicking outside

        $(document).on('click', function(e) {
            if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e
                    .target).length === 0) {
                $dropdownMenu.hide();
            }
        });
    }



    // Initialize dropdowns

    setupDropdown('dropdownButton1');
    setupDropdown('dropdownButton2');
    setupDropdown('dropdownButton3');
    setupDropdown('dropdownButton4');
});



function toggleDropdown() {
    var dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("active");
}



window.onload = function() {
    let params = new URLSearchParams(window.location.search);
    if (params.has('name') || params.has('email') || params.has('phone') || params.has('type') || params.has(
            'start_date') || params.has('end_date')) {
        let dropdown = document.getElementById("dropdown");
        dropdown.classList.toggle("active");
    }
};

document.addEventListener("DOMContentLoaded", function() {
    let startDateInput = document.querySelector('input[name="start_date"]');
    let endDateInput = document.querySelector('input[name="end_date"]');

    startDateInput.addEventListener("change", function() {
        let startDate = startDateInput.value;
        endDateInput.min = startDate; // Set min date for End Date
    });
});
</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<script>
$(function() {
    $("#start_date").datepicker();
    $("#end_date").datepicker();
});
</script>

@endpush