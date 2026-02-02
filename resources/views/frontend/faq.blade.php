@extends ('frontend.layout.app')
@section('title', @$title)
@section('content')

<style>
.card.mb-4 {
    box-shadow: 0px 6px 15px 0px rgba(0, 0, 0, 0.05);
}

#search-suggestions {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    width: 93%;
    max-height: 300px;
    overflow-y: auto;
    z-index: 9999;
    border-radius: 10px;
}

.suggestion-item {
    padding: 8px;
    cursor: pointer;
}

.highlight {
    background-color: #ffffcc;
    /* light yellow background */
    transition: background-color 5.5s ease;
}

.highlight-title {
    position: relative;
    overflow: hidden;
    background-color: #d1ecf1 !important;
    /* Optional base color */
    color: rgb(10, 165, 94);
}

.highlight-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    /* background: linear-gradient(90deg, rgb(10, 165, 94) 0%, rgba(13, 202, 240, 0.5) 50%, rgba(13, 202, 240, 0.2) 100%); */
    animation: shimmer 3s ease-out forwards;
    z-index: 1;
    pointer-events: none;
}

.highlight-title>* {
    position: relative;
    z-index: 2;
}

@keyframes shimmer {
    from {
        transform: translateX(-100%);
        opacity: 1;
    }

    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.suggestion-item:hover {
    background-color: #f0f0f0;
}

.suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.suggestion-item:hover {
    background-color: #f8f9fa;
}

.no-results {
    padding: 10px 12px;
    color: #888;
    font-style: italic;
}

button.accordion-button p {
    margin-bottom: 0px;
}

</style>

<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg')">
    <div class="container">
        <div class="breadcumb-content">
            <h2 class="breadcumb-title">Frequently Asked Have Any Questions?</h2>
        </div>
    </div>
</div>

@if(!empty($faq) && count($faq)>0)
<div class="faq-sec overflow-hidden space">
    <div class="container">

        <!-- <div class="row">
      <div class="title-area text-center mb-3">
        <h2 class="sec-title">Frequently Asked Have Any Questions?</h2>
      </div>
    </div> -->

        <div class="row mb-4">
            <div class="form-group2 col-12 col-md-4 position-relative">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="text" id="faq-search" class="form-control" name="surch" placeholder="Search"
                    autocomplete="off">

                <div id="search-suggestions" style="display: none;"></div>

            </div>


        </div>

        @php
        $groupedFaq = $faq->groupBy(function($item) {
        return $item->types->type ?? 'Uncategorized';
        });
        @endphp


        @foreach($groupedFaq as $type => $faqs)
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h5 class=" mb-0">{{ $type }}</h5>
                    </div>
                </div>

                <div class="row">
                    @php
                    $faqChunks = $faqs->chunk(ceil($faqs->count() / 2));
                    @endphp

                    @foreach($faqChunks as $chunk)
                    <div class="col-lg-6">
                        @foreach($chunk as $index => $values)
                        <div class="accordion-area accordion" id="faqAccordion-{{ $values->types->id ?? 'unknown' }}">
                            <div class="accordion-card {{ $index == 0 ? 'active' : '' }}">
                                <div class="accordion-header" id="collapse-item-{{ $values->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $values->id }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $values->id }}">
                                        {!! $values->title !!}
                                    </button>
                                </div>
                                <div id="collapse-{{ $values->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="collapse-item-{{ $values->id }}"
                                    data-bs-parent="#faqAccordion-{{ $values->types->id ?? 'unknown' }}">
                                    <div class="accordion-body">
                                        <p class="faq-text">{!! $values->description !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach


    </div>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const $searchInput = $('#faq-search');
    const $suggestions = $('#search-suggestions');

    function clearSuggestions() {
        $suggestions.empty().hide();
    }

    $searchInput.on('input', function() {
        const query = $(this).val().trim();

        if (query.length < 2) {
            clearSuggestions();
            return;
        }

        $.ajax({
            url: '/search-faq',
            method: 'GET',
            data: {
                q: query
            },
            success: function(response) {
                clearSuggestions();

                if (response && response.length > 0) {
                            response.forEach(function(faqItem) {
                                const $item = $('<div></div>')
                                    .addClass('suggestion-item')
                                    .html(faqItem.title) // Inject HTML content safely
                                    .attr('data-faq-id', faqItem.id)
                                    .on('click', function() {
                                        const faqId = $(this).attr('data-faq-id');
                                        const $accordionButton = $('[data-bs-target="#collapse-' + faqId + '"]');

                                        if ($accordionButton.length) {
                                            const $collapseDiv = $('#collapse-' + faqId);
                                            if (!$collapseDiv.hasClass('show')) {
                                                $accordionButton.trigger('click');
                                            }

                                            $('html, body').animate({
                                                scrollTop: $accordionButton.offset().top - 200
                                            }, 500);

                                            $accordionButton.addClass('highlight-title');
                                            setTimeout(() => {
                                                $accordionButton.removeClass('highlight-title');
                                            }, 30000);
                                        } else {
                                            alert('FAQ item not found.');
                                        }
                                    });

                                $suggestions.append($item);
                            });
                            $suggestions.show();
                        }



            },
            error: function() {
                console.error('Error fetching data');
            }
        });
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#faq-search, #search-suggestions').length) {
            clearSuggestions();
        }
    });
});
</script>
@endsection