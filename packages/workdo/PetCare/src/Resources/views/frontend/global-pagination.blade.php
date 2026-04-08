        
 <!-- Pagination Start -->
@if ($paginator->hasPages())
    <div class="flex justify-center lg:mt-8 mt-5">
        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="p-1 h-8 w-8 flex rounded-md items-center justify-center border border-gray-300 text-gray-400 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M15 18L9 12L15 6" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="p-1 h-8 w-8 flex rounded-md items-center justify-center border border-gray-300 text-gray-600 hover:text-black hover:bg-gray-50 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M15 18L9 12L15 6" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-gray-600">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="p-1 h-8 w-8 flex rounded-md items-center justify-center border border-primary bg-primary text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="p-1 h-8 w-8 flex rounded-md items-center justify-center border border-gray-300 text-gray-600 hover:text-black hover:bg-gray-50 transition-all duration-300">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="p-1 h-8 w-8 flex rounded-md items-center justify-center border border-gray-300 text-gray-600 hover:text-black hover:bg-gray-50 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M9 6l6 6-6 6" />
                    </svg>
                </a>
            @else
                <span class="p-1 h-8 w-8 flex rounded-md items-center justify-center border border-gray-300 text-gray-400 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M9 6l6 6-6 6" />
                    </svg>
                </span>
            @endif
        </div>
    </div>
@endif
<!-- Pagination End -->