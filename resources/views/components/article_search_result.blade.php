<h5 class="mb-3">Search result for '{{ $search }}':</h5>
<div class="row">
    <div class="d-flex justify-content-start flex-wrap article-wrapper">
        @foreach($articles as $article)
        <div class="card mb-4 article">
            <a href="{{ $article->link }}">
                <div class="card-image-wrapper">
                    <img class="card-img-top img-fluid object-fit-cover" src="{{ asset('/uploaded-image/article/'.$article->image) }}" alt="article image" />
                </div>
            </a>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">{{ $article->created_at }}</div>
                    <div class="d-flex gap-1 align-items-center">
                        <i class="fas fa-star"></i>
                        <div class="text-muted">{{ $article->rating }}/5</div>
                    </div>
                </div>
                <h2 class="card-title h4">{{ $article->title }}</h2>
                <p class="card-text">{{ $article->content }} ...</p>
                <a class="btn btn-primary" href="{{ $article->link }}">Read more →</a>
            </div>
        </div>
        @endforeach
        @if(count($articles) == 0)
            <div class="result-not-found mx-auto mt-5">Result not found</div>
        @endif
    </div>
</div>

@if(!count($articles) == 0)
    @include('components.pagination', [
        'search' => $search,
        'sort' => $sort, 
        'total_page' => $total_page,
        'current_page' => $current_page,
    ])
@endif

