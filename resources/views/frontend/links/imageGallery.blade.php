@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')

<style>
  .carousel {
    position: relative;
    height: auto;
    overflow: hidden;
}
.carousel-item {
    height: 400px;
}

img.d-block.w-100 {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: #fff;
}
.carousel-indicators button {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.4); /* Inactive dot */
  border: 1px solid #0a8f0a;
  opacity: 0.7;
  margin: 0 4px;
  transition: background-color 0.3s, opacity 0.3s;
}

.carousel-indicators button:hover {
  opacity: 1;
}

.carousel-indicators .active {
  background-color: #007bff; /* Active dot color */
  opacity: 1;
  transform: scale(1.2);
}
.lightbox-content {
  min-height: 400px;
  background-color: white;
}



</style> 

<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">Gallery</h2>
    </div>
  </div>
</div>

@if(!empty($image) && count($image)>0)
<div class="container">
  <div class="popup-gallery flex-wrap gap-3">
    @foreach($image as $values)
    <div class="gallery_item">
      <a class="gallery-item hover_affect_nlf position-relative" 
         href="{{ asset('admin/images/imageGallery/'.$values['image']) }}" 
         data-category-id="{{ $values->id }}" 
         title="{{ $values['title'] }}">
        <img src="{{ asset('admin/images/imageGallery/'.$values['image']) }}" 
             alt="{{ $values['title'] }}" 
             class="img-thumbnail" 
             >
          @if(!empty($values['title']))
              <h4 style="font-size:18px;margin-top:10px">{{ $values['title'] }}</h4>
          @else
            <h4 style="font-size:18px;margin-top:10px">No title</h4>
            @endif
      </a>

      <div class="d-none category-images" data-category-id="{{ $values->id }}">
        @if(isset($values->multiImages) && count($values->multiImages) > 0)
          @foreach($values->multiImages as $slideImage)
            <a class="related-image" href="{{ asset('admin/images/imageGallery/'.$slideImage->name) }}" data-image-id="{{ $slideImage->id }}">
              <img src="{{ asset('gallery/images/'.$slideImage->name) }}" class="img-fluid" alt="Image">
            </a>
          @endforeach
        @else
          <p style="color:#a92424;">No image available</p>
        @endif
      </div>

    </div>
    @endforeach
  </div>
</div>
@endif

<!-- Lightbox Modal -->
<!-- Modal HTML -->
<!-- Bootstrap Modal -->
<div class="modal fade" id="lightbox-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body lightbox-content d-flex align-items-center justify-content-center">
        <!-- Content will be injected here by JS -->
      </div>
    </div>
  </div>
</div>


<!-- Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const galleryGrid = document.querySelector('.popup-gallery');
  const modal = document.getElementById('lightbox-modal');
  const modalBody = modal.querySelector('.lightbox-content');
  const bsModal = new bootstrap.Modal(modal);

  function createSlides(images) {
    return images.map((img, index) => `
      <div class="carousel-item${index === 0 ? ' active' : ''}">
        <img src="${img.src}" class="d-block w-100" alt="${img.alt}">
      </div>
    `).join('');
  }

  function createIndicators(images) {
    return images.map((_, index) => `
      <button type="button" data-bs-target="#lightboxCarousel" data-bs-slide-to="${index}"
        class="${index === 0 ? 'active' : ''}"
        aria-current="${index === 0 ? 'true' : ''}" aria-label="Slide ${index + 1}">
      </button>
    `).join('');
  }

  function loadModalContent(categoryId) {
    // Get images first
    const relatedImages = document.querySelectorAll(
      `.category-images[data-category-id="${categoryId}"] .related-image img`
    );

    const images = Array.from(relatedImages).map(img => ({
      src: img.closest('a').getAttribute('href'),
      alt: img.getAttribute('alt')
    }));

    // If no images, show message immediately
    if (images.length === 0) {
      modalBody.innerHTML = `<h5 class="text-center p-4 text-danger">No images available for this Album.</h5>`;
      bsModal.show();
      return;
    }

    // If images found, show loader first
    modalBody.innerHTML = `
      <div class="d-flex justify-content-center align-items-center bg-white" style="height: 400px;">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    `;
    bsModal.show();

    // After 2 seconds, show carousel
    setTimeout(() => {
      const carouselMarkup = `
        <div id="lightboxCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
          <div class="carousel-indicators">
            ${createIndicators(images)}
          </div>
          <div class="carousel-inner">
            ${createSlides(images)}
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      `;

      modalBody.innerHTML = carouselMarkup;

      const carouselEl = document.getElementById('lightboxCarousel');
      const carousel = new bootstrap.Carousel(carouselEl);

      carouselEl.addEventListener('slide.bs.carousel', function (event) {
        const indicators = carouselEl.querySelectorAll('.carousel-indicators button');
        indicators.forEach(btn => btn.classList.remove('active'));
        if (indicators[event.to]) {
          indicators[event.to].classList.add('active');
        }
      });
    }, 2000);
  }

  // Click event
  galleryGrid.addEventListener('click', (e) => {
    const link = e.target.closest('.gallery-item');
    if (!link) return;

    e.preventDefault();
    const categoryId = link.getAttribute('data-category-id');
    loadModalContent(categoryId);
  });
});


</script>


@endsection
