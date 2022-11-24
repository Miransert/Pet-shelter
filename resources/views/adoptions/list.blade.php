@extends('master')

@section('content')
    <div class="container mt-4">
        <h2>{{ $header }}</h2>
        @include('partials.success-alert')
        <div class="row">
            @forelse($adoptions as $adoption)
                <div class="col-4 pet">
                    <!-- Guest, Task 1 Use partial here -->
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
