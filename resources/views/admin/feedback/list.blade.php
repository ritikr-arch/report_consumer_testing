@extends('admin.layouts.app') 
@section('title', @$title) 
@section('content') 
<style>
  .ratingg-sp span{
    font-size: 20px;
  }
  .modal-dialog.modal-lg.modal-dialog-centered.height-500 .modal-body {
    height: 440px;
    overflow: auto;
    text-align: justify;
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
                <h4 class="header-title mb-0 font-weight-bold"> Feedback List </h4>
              </div>
              <div class="col-12 col-md-7 col-lg-7">
                <div class="search-btn1 text-end">
                  <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()">
                    <i class="fa-solid fa-filter"></i>&nbsp;Filter </button>
                </div>
              </div>
            </div>
            <div class="row mb-4">
              <form action="{{route('admin.feedback.filter')}}" method="get" id="complaintfilter">
                <hr>
                
                <div id="dropdown" class="dropdown-container-filter">
                  <div class="name-input">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ request('name') }}" maxlength="200">
                                    </div>
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="{{ request('email') }}" maxlength="200">
                                    </div>
                  <div class="filter-date">
                    <!-- <label for="start-date">Start Date</label> -->
                    <input type="text" value="{{ request('start_date') }}" name="start_date" class="form-control" placeholder="Start Date" id="start_date" autocomplete="off">
                  </div>
                  <div class="filter-date">
                    <!-- <label for="end-date">End Date</label> -->
                    <input type="text" value="{{ request('end_date') }}" name="end_date" class="form-control" placeholder="End Date" id="end_date" autocomplete="off">
                  </div>
                  <button type="submit" class="d-flex searc-btn">Search</button>
                  <a href="{{ route('admin.feedback.list') }}" type="button" class="btn btn-secondary btn-sm">Reset</a>
                </div>
              </form>
            </div>
            <div class="table-responsive white-space">
              <table class="table table-hover mb-0">
                <thead>
                  <tr class="border-b bg-light2">
                    <th style="width:5%;">S.No.</th>
                    <th style="width:20%;">Name</th>
                    <th style="width:10%;">Email</th>
                    <th style="width:31%;">Comments</th>
                    <th style="width:12%;">Rating</th>  
                    <th style="width:12%;">Created At</th>
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
                   @endif {{ ucfirst($value->name) }}
                     
        </div>

                      @if($value->is_read == '0')
                        <a href="#" class="mark-as-read" data-id="{{ $value->id }}" style="color: #007bff; text-decoration: none;">
                          Mark as Read
                        </a>
                      @endif
                  
                </td>

                    <td>{{$value->email}}</td>
                  <td>
                      {!! Str::limit(strip_tags(ucfirst($value->comment)), 100) !!}
                      @if (strlen(strip_tags($value->comment)) > 100)
                        <a href="#" data-id="{{$value->id}}" class="read-more" data-comment="{!! htmlspecialchars($value->comment, ENT_QUOTES) !!}" data-bs-toggle="modal" data-bs-target="#commentModal">
                          Read More
                        </a>
                      @endif
                    </td>
                    
                    <td class="ratingg-sp">
                      @if($value->rating=='1')
                      <span>★</span>
                      @elseif($value->rating=='2')
                      <span>★★</span>
                      @elseif($value->rating=='3')
                      <span>★★★</span>
                      @elseif($value->rating=='4')
                      <span>★★★★</span>
                      @elseif($value->rating=='5')
                      <span>★★★★★</span>
                      @endif
                    </td>
                    <td>{{ customt_date_format($value->created_at) }}</td>
                  </tr> 
                  @endforeach 
                  @else 
                  <tr>
                    <td colspan="5">
                      <p class="no_data_found">No Data found! </p>
                    </td>
                  </tr> 
                  @endif 
                </tbody>
              </table>
              @if (isset($data)) {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }} @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end row-->
  </div>
  <!-- container -->
</div>
<!-- content -->
</div>
</div>
<!-- Reusable Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered height-500">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commentModalLabel">View Comment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalComment"></div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {

    $('.read-more').on('click', function() {

      var id = $(this).data('id');
      var _this = this;


      var commentHtml = $(this).data('comment');
      console.log('this',commentHtml);
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
                url: "{{ route('admin.update.read.status') }}",
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


<!-- END wrapper -->
<script>
  function toggleDropdown() 
  {
    var dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("active");
  }

  window.onload = function() 
  {
    let params = new URLSearchParams(window.location.search);
    if (params.has('name') || params.has('start_date') || params.has('status') || params.has('end_date')) 
    {
      let dropdown = document.getElementById("dropdown");
      dropdown.classList.toggle("active");
    }
  }
</script>
@endsection 

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<script>
$(function() {
    $("#start_date").datepicker();
    $("#end_date").datepicker();
});
</script>
@endpush