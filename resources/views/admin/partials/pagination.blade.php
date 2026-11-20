@if($paginator->hasPages())
    <div class="admin-pagination">
        <span class="admin-pagination-summary">Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results</span>
        <nav aria-label="Pagination">
            @if($paginator->onFirstPage())
                <span class="page-disabled" aria-disabled="true">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
            @endif
            @for($page = max(1, $paginator->currentPage() - 1); $page <= min($paginator->lastPage(), $paginator->currentPage() + 1); $page++)
                @if($page === $paginator->currentPage())
                    <span class="page-current" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
                @endif
            @endfor
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
            @else
                <span class="page-disabled" aria-disabled="true">Next</span>
            @endif
        </nav>
    </div>
@endif
