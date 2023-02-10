@extends('master')

@section('content')
    <div class="container mt-4">
        <h2>{{ $header }}</h2>
        @include('partials.success-alert')
        <div class="row">
            @forelse($adoptions as $adoption)
                <div class="col-4 pet">
                    <!-- Guest, Task 1 Use partial here -->
                    <div class="card mb-4 shadow-sm">
                        <div class="ratio ratio-1x1">
                            <img src="{{ asset($adoption->image_path) }}" class="card-img-top "
                                 style="object-fit: cover" alt="">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title pet-name">{{ $adoption->name }}</h5>
                            <p class="card-text pet-description"
                               style="height: 60px; overflow: hidden;  text-overflow: ellipsis;">{{ $adoption->description }}</p>
                            <a href="{{ route('adoptions.show', [$adoption->id]) }}" class="btn btn-primary pet-show">More Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <h2>This list is empty</h2>
            @endforelse
        </div>
        @if($adoptions instanceof Illuminate\Pagination\AbstractPaginator)
            {!! $adoptions->links() !!}
        @endif
    </div>
@endsection
