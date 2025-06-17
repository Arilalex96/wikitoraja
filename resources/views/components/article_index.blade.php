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
                <div class="small text-muted">{{ $article->created_at }}</div>
                <h2 class="card-title h4">{{ $article->title }}</h2>
                <p class="card-text">{{ $article->content }} ...</p>
                <a class="btn btn-primary" href="{{ $article->link }}">Baca selengkapnya →</a>
            </div>
        </div>
        @endforeach
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