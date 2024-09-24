@if ($paginator->hasPages())
    <div class="d-flex justify-content-center my-4 pagination-custom">
        @if ($paginator->onFirstPage())
            <span class="disabled pagination-link">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link">&laquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="disabled pagination-link">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active pagination-link">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link">&raquo;</a>
        @else
            <span class="disabled pagination-link">&raquo;</span>
        @endif
    </div>
@endif
