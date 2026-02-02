@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content') 
{!! NoCaptcha::renderJs() !!}
<style>
   .th-btn-complain {
    position: relative;
    z-index: 2;
    overflow: hidden;
    vertical-align: middle;
    display: inline-block;
    text-transform: uppercase;
    text-align: center;
    border: none;
    background-color: #ba202f;
    color: var(--white-color);
    font-family: var(--body-font);
    font-size: 14px;
    font-weight: 600;
    line-height: 1;
    padding: 12px 25px;
    min-width: 80px;
    margin-top:15px;
    border-radius: 30px;
    -webkit-transition: all 0.4s ease-in-out;
    transition: all 0.4s ease-in-out;
}
 .modal-backdrop.show {
    background-color: rgba(0, 0, 0, 0.3) !important; /* lighter black */
  }
</style>


<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
   <div class="container">
      <div class="breadcumb-content">
         <h2 class="breadcumb-title">Complaint Status</h2>
      </div>
   </div>
</div>
<section style="background:#f7f7f7;">
   <div class="container">
   <div class="row">
      <div class="col-md-12 mx-auto mt-4 padd-50">
         <div class="page-single service-single griv-wrap">
            <div class="row complaint-wrap">
               <div class="col-md-12 comp-head">
                  <!-- <h4>Complaint Form Status</h4> -->
                 
               </div>

               @if($complaint['status'] == '0')
               <div class="col-md-4">
                  @if(!empty($complaint))
                 @if($complaint['status'] == '0')
                 <p><span>Latest Update :</span> {{ isset($complaint['created_at']) ? customt_date_format($complaint['created_at']) : '-'}}  </p>
                 @else
                     @if(!empty($complaint['official_use_end_date']))
                     <p><span>Latest Update :</span> {{ isset($complaint['official_use_end_date']) ? customt_date_format($complaint['official_use_end_date']) : '-'}}  </p>@endif
                     @endif
                  </div>
                  <div class="col-md-4">
                     <p><span>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </span> 
                        @if($complaint['status'] == '0')
                        New
                        @elseif($complaint['status'] == '1')
                        Resolved
                        @elseif($complaint['status'] == '2')
                        In progress
                        @endif
                     </p>
                  </div>
                 

                   <div class="col-md-12">
                  
                     <p>
                        <span>Feedback &nbsp;&nbsp;&nbsp;: </span>
                       @if ($complaint['status'] == '0' && empty($complaint['investing_officer']))
                           Thank you for registering your complaint. A support team member has been assigned to your case and will address it shortly. We appreciate your patience.
                        @elseif (!empty($complaint['investing_officer']))
                           A support team member <strong style="color:black;">{{ $complaint['investing_officer'] }}</strong> has been assigned to your case and will get in touch with you shortly to resolve the issue. We sincerely appreciate your patience and understanding.
                        @else
                           {{ $complaint['official_use_result'] }}
                        @endif

                     </p>

              
                  </div>
                    @endif
                  @endif
                  <div class="col-md-12 text-center">
                        @if($complaintformstatuses->count() > 0)
                              <div class="table-responsive mt-4">
                                 <table class="table table-bordered table-striped status-table">
                                      <thead class="thead-light">
                                          <tr>
                                             <th style="width:5%">#</th>
                                             <th style="width:22%">Recent Updated Date</th> <!-- Increased width -->
                                             <th style="width:10%">Status</th>
                                             <th style="width:10%">Investigator</th>
                                             <th style="width:14%">Case Document</th>
                                             <th style="width:22%">Feedback</th>
                                             <th style="width:22%">Remark</th>
                                          </tr>
                                       </thead>

                                       <tbody>
                                          @php
                                             $statusText = [
                                                   '0' => 'New',
                                                   '1' => 'Resolved',
                                                   '2' => 'In Progress',
                                                   '3' => 'Dismissed',
                                                   '4' => 'Closed',
                                             ];
                                          @endphp

                                          @foreach($complaintformstatuses as $key => $complaint)
                                             <tr>
                                                   <td>{{ $key + 1 }}</td>
                                                   <td>{{ customt_date_format($complaint->official_use_date) }} {{ date('h:i A', strtotime($complaint->official_use_date)) }}</td>
                                                   <td>{{ $statusText[$complaint->status] ?? 'Unknown' }}</td>
                                                   <td>{{ $complaint->official_use_supervisior }}</td>
                                                   <td>
                                                      @if(!empty($complaint->official_use_exhibits) && trim($complaint->official_use_exhibits) !== 'exhibits/')
                                                         <a href="{{ asset($complaint->official_use_exhibits) }}" target="_blank">Download <i class="fa fa-download" aria-hidden="true"></i></a>
                                                      @else
                                                         N/A
                                                      @endif
                                                   </td>
                                                   <td>
                                                      <div class="text-truncate" style="max-width: 200px;">
                                                         <span>
                                                               {{ \Illuminate\Support\Str::limit($complaint->official_use_feedback, 100) }}
                                                         </span>
                                                         @if(strlen($complaint->official_use_feedback) > 100)
                                                               <button type="button" class="btn btn-link p-0 text-primary text-sm"
                                                                  onclick="showFullContent(`{!! addslashes($complaint->official_use_feedback) !!}`)">
                                                                  Read More
                                                               </button>
                                                         @endif
                                                      </div>
                                                   </td>
                                                   <td>
                                                      <div class="text-truncate" style="max-width: 200px;">
                                                         <span>
                                                               {{ \Illuminate\Support\Str::limit($complaint->official_use_remark, 100) }}
                                                         </span>
                                                         @if(strlen($complaint->official_use_remark) > 100)
                                                               <button type="button" class="btn btn-link p-0 text-primary text-sm"
                                                                  onclick="showFullContent(`{!! addslashes($complaint->official_use_remark) !!}`)">
                                                                  Read More
                                                               </button>
                                                         @endif
                                                      </div>
                                                   </td>
                                             </tr>
                                          @endforeach
                                       </tbody>
                                 </table>
                              </div>
                          @endif

                    <button type="button" class="th-btn" onclick="window.history.back();">Back</button>

                  </div>
               </div>
            </div>
         </div>
         
      </div>
   </div>
</section>
<!-- Common Reusable Modal -->
<div class="modal fade" id="commonReadMoreModal" tabindex="-1" aria-labelledby="readMoreLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- center vertically + large modal -->
    <div class="modal-content bg-white rounded shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="readMoreLabel">Content</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body" id="readMoreModalBody" style="max-height: 70vh; overflow-y: auto; color: #333;">
        <!-- Full content will be inserted here -->
      </div>
    </div>
  </div>
</div>
<script>
  function showFullContent(content) {
    // Set content with line breaks converted to <br>
    document.getElementById('readMoreModalBody').innerHTML = content.replace(/\n/g, "<br>");

    // Show modal using Bootstrap
    const modal = new bootstrap.Modal(document.getElementById('commonReadMoreModal'));
    modal.show();
  }
</script>


@endsection