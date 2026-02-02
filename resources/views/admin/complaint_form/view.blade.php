@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<style>
.btn-sucess{
background-color: #006738;
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    padding: 6px 10px;
    margin-top: 10px;
} 
.btn-sucess:hover{
    background-color: #006738;
    color: white;
    border: none;
}
   #signature-pad 
  {
    border: 2px dashed #ccc;
    width: 100%;
    max-width: 100%;
    height: 200px;
    cursor: crosshair;
  }
 .form-group.ad-user input {
    height:40px !important;
 }
 .form-group.ad-user select {
    height:40px !important;
 }


    .responsive-table-container {
        width: 100%;
        overflow-x: auto;
    }

    .status-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        min-width: 800px; /* Ensures horizontal scroll on smaller devices */
    }

    .status-table th, .status-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
        vertical-align: top;
    }

    .status-table th {
       background-color: rgb(245 245 245) !important;
        color: #000 !important;
    }

    .status-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .status-table tr:hover {
        background-color: #f1f1f1;
    }
    .compl-btn{
        text-align:right;
    }
    .compl-btn button {
    padding: 10px 30px;
}

    @media screen and (max-width: 768px) {
        .status-table {
            font-size: 14px;
        }
        .status-table th,
        .status-table td {
            padding: 8px;
        }
    }
     .modal-backdrop.show {
    background-color: rgba(0, 0, 0, 0.3) !important; /* lighter black */
  }
  .overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5); /* semi-transparent black */
  z-index: 9998; /* thoda loader ke neeche rakhna */
  display: none; /* hidden by default */
}

</style>


<div class="px-3">

    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-centerr d-flex">
                            <div class="col-xl-12">
                               <!--  <h4 class="header-title mb-0 font-weight-bold" >
                                    View
                                </h4> -->
                            </div>
                        </div>

                        <div class="row">

                        <h4 class="header-title mb-0 font-weight-bold mb-3 view_header">
                        Information on the Consumer

                                </h4>
                        
                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>Complaint ID </h5>
                                    <h6> {{ 'CID'.@$data->complaint_id}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>First Name </h5>
                                    <h6> {{@$data->first_name}}</h6>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>Last Name </h5>
                                    <h6> {{@$data->last_name}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>Email </h5>
                                    <h6> {{@$data->email}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>Phone Number </h5>
                                    <h6>{{@$data->country_code}} {{@$data->phone}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>Address </h5>
                                    <h6> {{@$data->address}}</h6>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>Gender </h5>
                                    <h6> {{@$data->gender}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Age Group </h5>
                                    <h6> {{@$data->age_group}}</h6>
                                </div>
                            </div>

                            <h4 class="header-title mb-0 font-weight-bold mb-3 view_header" >
                            Information on Business </h4>


                            <div class="col-md-6">
                                <div class="view-txt widt-240">
                                    <h5> Business Name (or Individual) </h5>
                                    <h6> {{@$data->business_name}}</h6>
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Email </h5>
                                    <h6> {{@$data->business_email}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Phone Number </h5>
                                    <h6>{{@$data->business_country_code}} {{@$data->business_phone}}</h6>
                                </div>
                            </div>

                           <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Address </h5>
                                    <h6> {{@$data->business_address}}</h6>
                                </div>
                            </div>

                            <h4 class="header-title mb-0 font-weight-bold mb-3 view_header">
                            Information on Goods or Services </h4>

                            <div class="col-md-6">
                                <div class="view-txt widt-240">
                                    <h5> Product or Service Purchased </h5>
                                    <h6> {{@$data->service}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Brand </h5>
                                    <h6> {{@$data->brand}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Model/Serial Number</h5>
                                    <h6> {{@$data->serial}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Category </h5>
                                    <h6> {{@$data->category}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5> Date of Purchase </h5>
                                    <h6> {{ isset($data->date_of_purchase) ? customt_date_format($data->date_of_purchase) : ''}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt widt-240">
                                    <h5> Warranty Period Provided </h5>
                                    <h6> {{@$data->warranty}}</h6>
                                </div>
                            </div>

                           

                            <div class="col-md-6">
                                <div class="view-txt ">
                                    <h5> Hire Purchase Item </h5>
                                    <h6>{{ ($data->hire_purchase_item == '0') ? 'Yes' : 'No' }}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt widt-240">
                                    <h5> Did you sign a contract? </h5>
                                    <h6>{{ ($data->sign_contract == '0') ? 'Yes' : 'No' }}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>  Additional Statement </h5>
                                    <h6> {{@$data->additional_statement}}</h6>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="view-txt">
                                    <h5>Copy of Receipt/Contract/Agreement</h5>
                                    @if(!empty($documents) && count($documents)>0)
                                        @php $count=1; @endphp
                                        @foreach($documents as $index => $doc)

                                        <a target="_blank" href="{{ asset($doc['document']) }}"> 
                                           View
                                        </a>

                                        @if(!$loop->last),&nbsp;@endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <h4 class="header-title mb-0 font-weight-bold mb-3 view_header">
                            Willingness to attend Proceedings
                           </h4>


                            <div class="col-md-12">
                                <div class="view-txt">
                                 

                                    @php
                                $imageUrl = '';

                                if (!empty($data->signed)) {
                                    // Get the base64-encoded data
                                    $base64Image = @file_get_contents($data->signed);

                                    if (!empty($base64Image)) {
                                        $imageName = 'signature_' . time() . '.png';

                                        // Save the image to storage
                                        Storage::put("public/signatures/{$imageName}", $base64Image);

                                        // Get the public URL
                                        $imageUrl = asset("storage/signatures/{$imageName}");
                                    }
                                }
                            @endphp

                            <h5>Signed</h5>
                            @if (!empty($imageUrl))
                                <h6>
                                    {{-- You can enable this link if needed --}}
                                    {{-- <a href="{{ $imageUrl }}" target="_blank">View</a> --}}
                                    <img src="{{ asset($data->signed) }}" alt="Signed" style="width200px;height:70px;">
                                </h6>
                            @endif

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="">
                                    <h5> Date :  <span style="font-weight: 600;   color: var(--bs-heading-color);"> 
                                    {{ isset($data->date) ? customt_date_format($data->date) : ''}}
                                        
                            </span></h5>
                                    
                                </div>
                            </div>


                            <h4 class="header-title mb-0 font-weight-bold mb-3 view_header" >
                            For Official Use Only
                           </h4>
                           
                    
                  
                              <div class="col-md-12">
                                <form  id="complaint_form"> 
                                                @csrf 
<div class="overlay"></div>
<div class="loader" style="display:none;"></div>
                                             
                                                <div class="row">
                                                     <input type="hidden" id="id" value="{{ @$data->id }}" name="id">
                                                    @if($roles[0] == 'Admin')
                                                    @php  
                                                    $column ="col-md-4";
                                                    @endphp
                                                <div class="{{ $column }}">
                                               
                                                <div class="form-group ad-user">
                                                    <label>Investigator <span style="color:red;">*</span></label>
                                                    <!-- <input type="text" class="form-control" name="supervisior" id="supervisior" maxlength="250"> -->
                                                    <select class="form-control" id="supervisior" name="supervisior">
                                                
                                                @if(isset($officer) && count($officer)>0) 
                                                @foreach($officer as $values)
                                                @php 
                                                    $name = $values['name'];
                                                @endphp;
                                                 <option value="{{ $values['title'] }} {{ $values['name'] }}" {{ $data->investing_officer == $values['name'] ? 'selected' : '' }}>
                                                  {{ $values['title'] }} {{ $values['name'] }} ({{ $values->getRoleNames()->implode(', ') }})
                                                </option>

                                                @endforeach
                                                @endif
                                                </select>
                                                    <span class="text-danger error-supervisior"></span>
                                                </div>
                                                </div>
                                            @else
                                             @php  
                                                    $column ="col-md-6";
                                                    @endphp
                                                     <input type="hidden" id="supervisior" value="{{ $user->name }}" name="supervisior">
                                                @endif
                                                <div class="{{ $column }}">
                                                <div class="form-group ad-user">
                                                    <label>Case Document</label>
                                                    <input type="file" class="form-control" name="exhibits" id="exhibits" accept=".pdf,.docx,.doc">
                                                    <span class="text-danger error-exhibits"></span>
                                                </div>
                                                </div>
                                                
                                                <div class="{{ $column }}">
                                                <div class="form-group ad-user">
                                                    <label>Status <span style="color:red;">*</span> </label>
                                                    <div class="rela-icon">
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="0">New </option>
                                                        <option value="1">Resolved </option>
                                                        <option value="2">In Progress</option>
                                                        <option value="3">Dismissed</option>
                                                        <option value="4">Closed</option>
                                                    </select>
                                                    <i class="fa-solid fa-caret-down"></i> 
                                                    <span class="text-danger error-status"></span>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group ad-user">
                                                    <label>Feedback <span style="color:red;">*</span></label>
                                                    <textarea class="form-control" name="result" id="result"></textarea>
                                                    <span class="text-danger error-result"></span>
                                                </div>
                                                </div>

                                                <div class="col-md-6" id="remark">
                                                <div class="form-group ad-user">
                                                    <label>Remark <span style="color:red;"></span></label>
                                                    <textarea class="form-control" name="remark" id="remark"></textarea>
                                                    <span class="text-danger error-remark"></span>
                                                </div>
                                                </div>
                                                

                                                
                                                
                                            
                                                    <div class="col-xl-12 text-end">
                                                        <button type="button" class="btn btn-sm btn-primary" onclick="clearPad()">Clear</button>
                                                    </div>
                                                <div class="col-xl-12 mb-4 ">
                                                    <div class="input text required">
                                                        <label>Signature <span style="color:red;"></span></label>
                                            <canvas id="signature-pad"></canvas><br>
                                            <input type="hidden" name="signature_image" id="signature_image" accept="image/*,.pdf,.docx,.doc">
                                            <div class="text text-danger" id="sign_error">@error('signature_image')<p style="color:red;">{{$message}}</p>@enderror</div>
                                                </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                <div class="form-group ad-user">
                                                    <label>Date <span style="color:red;">*</span></label>
                                                    <input type="date" class="form-control" name="end_date" id="end_date">
                                                    <span class="text-danger error-end_date"></span>
                                                </div>
                                                </div>
                                                <div class="compl-btn">

                                                <button type="submit" class="btn btn-sucess" id="submit-btn">Submit</button>
                                                </div>
                                            </div>

                                </form>
                            </div>
                      
                        <hr class="mt-3 mb-3">
                        <h2>Recent Comments</h2>
                           @if($complaintformstatuses->count() > 0)
                            <table class="status-table mt-2">
                                <thead>
                                    <tr>
                                        <th style="width:4%;">#</th>
                                         @if($roles[0] == 'Admin')
                                        <th style="width:10%;">Investigator</th>
                                        @endif
                                        <th style="width:15%;">Recennt updated Date</th>
                                        <th style="width:12%;">Case Document</th>
                                        <th style="width:20%;">Feedback</th>
                                        <th style="width:5%;">Signature</th>
                                        <th style="width:15%;">Status Update Date</th>
                                        <th style="width:26%;">Remark</th>
                                        <th style="width:20%;">Status</th>
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
                                            @if($roles[0] == 'Admin')
                                            <td>{{ $complaint->official_use_supervisior }}</td>
                                            @endif
                                            <td>{{ customt_date_format($complaint->official_use_date) }}&nbsp;{{ date('h:i A', strtotime($complaint->official_use_date)) }}</td>
                                            <td>
                                                @if($complaint->official_use_exhibits)
                                                    <a href="{{ asset($complaint->official_use_exhibits) }}" target="_blank">View</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                           
                                            <td>
                                             <div class="max-h-[4.5em] overflow-hidden relative" style="line-height: 1.5em;">
                                                <span class="block">
                                                   {{ \Illuminate\Support\Str::limit($complaint->official_use_feedback, 50) }}
                                                </span>
                                                @if(strlen($complaint->official_use_feedback) > 50)
                                                   <button type="button" class="btn btn-link p-0 text-primary text-sm"
                                                         onclick="showFullContent(`{!! addslashes($complaint->official_use_feedback) !!}`)">
                                                   Read More
                                                   </button>
                                                @endif
                                             </div>
                                             </td>
                                            <td>
                                                @if($complaint->official_use_signature)
                                                <a href="{{ asset($complaint->official_use_signature) }}" target="_blank">View</a>
                                                    <!-- <img src="{{ asset($complaint->official_use_signature) }}" alt="Signature" width="80"> -->
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ customt_date_format($complaint->official_use_end_date) }}</td>
                                           <td>
                                             <div class="max-h-[4.5em] overflow-hidden relative" style="line-height: 1.5em;">
                                                <span class="block">
                                                   {{ \Illuminate\Support\Str::limit($complaint->official_use_remark, 50) }}
                                                </span>
                                                @if(strlen($complaint->official_use_remark) > 50)
                                                   <button type="button" class="btn btn-link p-0 text-primary text-sm"
                                                         onclick="showFullContent(`{!! addslashes($complaint->official_use_remark) !!}`)">
                                                   Read More
                                                   </button>
                                                @endif
                                             </div>
                                             </td>
                                            <td>{{ $statusText[$complaint->status] ?? 'Unknown' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        @else
                            <p>No complaint form status found for this complaint.</p>
                        @endif
                           
                            
                       
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

</div>

</div>
<!-- Common Reusable Modal -->
<div class="modal fade" id="commonReadMoreModal" tabindex="-1" aria-labelledby="readMoreLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- center vertically + large modal -->
    <div class="modal-content bg-white rounded shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="readMoreLabel">Content</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="readMoreModalBody" style="max-height: 70vh; overflow-y: auto; color: #333;">
        <!-- Full content will be inserted here -->
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  function showFullContent(content) {
    // Set content with line breaks converted to <br>
    document.getElementById('readMoreModalBody').innerHTML = content.replace(/\n/g, "<br>");

    // Show modal using Bootstrap
    const modalElement = document.getElementById('commonReadMoreModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    // Optional: attach close button event manually
    const closeBtn = modalElement.querySelector('.btn-close');
    closeBtn.onclick = () => {
      modal.hide();
    };
  }
</script>

<script>
  
$(document).ready(function () {
 $('#complaint_form').on('submit', function (e) {
    e.preventDefault();

    // Disable submit button and show loader
    $('#submit-btn').prop('disabled', true).text('Submitting...');
    $('.overlay').show();
$('.loader').show();
    // Check if canvas has content (rough check)
    const blank = document.createElement('canvas');
    blank.width = canvas.width;
    blank.height = canvas.height;

    if (canvas.toDataURL() === blank.toDataURL()) {
        document.getElementById('sign_error').innerHTML = "<p style='color:red;'>Signature is required.</p>";
        $('#submit-btn').prop('disabled', false).text('Submit');
    $('.overlay').hide();
$('.loader').hide();
        return;
    }

    // ✅ Set value BEFORE creating FormData
    const dataUrl = canvas.toDataURL('image/png');
    document.getElementById('signature_image').value = dataUrl;

    let formData = new FormData(this); // Now it includes updated hidden input value

    $.ajax({
        url: "{{ route('admin.complaint.form.save') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('.text-danger').text('');
        },
        success: function (response) {
            if (response.success) {
                toastr.success(response.message);
                document.getElementById('complaint_form').reset();
                setTimeout(() => location.reload(), 1000);
            } else {
                $('#submit-btn').prop('disabled', false).text('Submit');
                $('.loader').hide();
            }
        },
        error: function (xhr) {
            let errors = xhr.responseJSON.errors || {};
            $(".text-danger").text("");
            const fields = [
                'supervisior', 'investing_officer', 'state_date',
                'exhibits', 'result', 'signature',
                'end_date', 'status', 'remark'
            ];
            fields.forEach(field => {
                if (errors[field]) {
                    $(`.error-${field}`).text(errors[field][0]);
                }
            });

            $('#submit-btn').prop('disabled', false).text('Submit');
            $('.overlay').hide();
$('.loader').hide();
        }
    });
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

@endsection