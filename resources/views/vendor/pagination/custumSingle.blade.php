@if ($paginator->hasPages())

    <ul class="pagination">

        @if ($paginator->onfirstPage())
            <li class="page-item disabled style-disabled"><span class="page-link"><i
                        class="fas fa-arrow-left"></i></span></li>
        @else
            <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link"
                    rel="prev"><i class="fas fa-arrow-left"></i></a></li>
        @endif
        @if ($paginator->hasMorePages())
            <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="prev"><i
                        class="fas fa-arrow-right"></i></a></li>
        @else
            <li class="page-item disabled"><span class="page-link"><i class="fas fa-arrow-right"></i></span></li>
        @endif
    </ul>
@endif
