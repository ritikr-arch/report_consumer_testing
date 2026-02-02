@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')
<!-- breadcrumb -->
<style>

  .card-listing ul li {
    border-radius:0px;

  }

</style>
<div class="breadcumb-wrapper background-image" style="background-image: url(' {{ asset('frontend/img/bread-crum.jpg') }} ') ">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">{{ $title }}</h2>
    </div>
  </div>
</div>


<div class="container">
   <div class="row">
      <div class="card-listing col-12 col-md-8 ps-4">
         <ul>
           @if(!empty($press) && count($press)>0)

            @foreach($press as $values)
            <li>
               <div class="notification-view quick_link_section">
                  <div class="tab-title">
                  <i class="fas fa-list"></i> {{ $values['title'] }}
    
                     <div>
                        <div class="recent-post-meta">
                           <a href="#"> Date:
                           {{ date('d-m-Y',strtotime($values['created_at'])) }}</a>
                        </div>
                        @php
    $file = $values['link'];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    $url = ($values['type'] === 'video_external' || $values['type'] === 'link') 
        ? $file 
        : asset('admin/images/tips_advice/' . $file);
@endphp

                        <a 
    href="{{ $extension === 'pdf' ? $url : 'javascript:void(0);' }}"
    class="{{ $extension !== 'pdf' ? 'openContentModal' : '' }}" 
    data-link="{{ $url }}"
    data-title="{{ \Illuminate\Support\Str::limit(strip_tags($values['title']), 90) }}"
    style="font-size: 14px;"
    target="{{ $extension === 'pdf' ? '_blank' : '' }}">
    View Document
</a>
                     </div>
                  </div>
                  


               </div>
            </li>
            @endforeach

            @endif



         </ul>
      </div>
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true" style="z-index: 1050;">
  <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 600px;">
    <div class="modal-content rounded shadow" id="modal-content" style="position: relative; top: -25px;">

      <!-- Modal Header -->
      <!-- <div class="modal-header" style="background-color: #f8f9fa; position: relative;"> -->
        <!-- <p class="modal-title mb-0 pe-5" id="contentModalLabel" style="font-weight: bold;"></p> -->

        <!-- Close Button -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
          style="width: 30px; height: 30px; position: absolute; right: -5px; top: -5px; background: #bd1e2d; z-index: 9999; padding: 0; border-radius: 50%;">
          <i class="fa fa-close" style="position: relative; top: 1px;"></i>
        </button>
      <!-- </div> -->

      <!-- Modal Body -->
      <div class="modal-body p-0" style="height: calc(100% - 56px); overflow: hidden;">
        <div class="d-flex align-items-center justify-content-center h-100 w-100" id="modalContentWrapper">
          <!-- Dynamic content goes here -->
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Script -->
<script>
$(document).on('click', '.openContentModal', function () {
    const link = $(this).data('link');
    const title = $(this).data('title');
    const extension = link.split('.').pop().toLowerCase();

    // Set modal title
    $('#contentModalLabel').text(title);

    let contentHtml = '';

    // Reset height first
    $('#modal-content').css('height', '');

    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
        $('#modal-content').css('height', '660px');
        contentHtml = `<img src="${link}" class="img-fluid shadow" style="height: 100%; width: 100%;">`;

    } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
        $('#modal-content').css('height', '338px');
        contentHtml = `
            <video controls class="shadow" style="max-height: 100%; max-width: 100%;">
                <source src="${link}" type="video/${extension}">
                Your browser does not support the video tag.
            </video>`;

    } else {
        $('#modal-content').css('height', '500px'); // default/fallback height
        contentHtml = `<iframe src="${link}" width="100%" height="100%" frameborder="0" class="rounded shadow"></iframe>`;
    }

    $('#modalContentWrapper').html(contentHtml);
    $('#contentModal').modal('show');
});

</script>

@endsection