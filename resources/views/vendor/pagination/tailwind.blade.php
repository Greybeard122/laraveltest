@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center mt-4 space-y-1">
        
        {{-- Display Result Count (Single Line) --}}
        <div class="text-sm text-gray-600 text-center">
            Showing 
            <span class="font-semibold">{{ $paginator->firstItem() }}</span> 
            to 
            <span class="font-semibold">{{ $paginator->lastItem() }}</span> 
            of 
            <span class="font-semibold">{{ $paginator->total() }}</span> 
            results
        </div>

        {{-- Pagination Links (Forces New Line) --}}
        <div class="flex items-center space-x-2 mt-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 bg-gray-300 text-gray-600 rounded cursor-not-allowed">«</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 bg-white border rounded hover:bg-gray-200">«</a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 border">{{ $element }}</span>
                @endif
                
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 bg-blue-600 text-white rounded">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 border hover:bg-gray-200">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 bg-white border rounded hover:bg-gray-200">»</a>
            @else
                <span class="px-3 py-2 bg-gray-300 text-gray-600 rounded cursor-not-allowed">»</span>
            @endif
        </div>
    </nav>
@endif
