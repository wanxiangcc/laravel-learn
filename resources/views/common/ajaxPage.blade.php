@if ($paginator->hasPages())
    <ul class="pagination pagination-lg">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="disabled"><span>&raquo;</span></li>
        @endif
    </ul>
@endif
共 {{$paginator->currentPage()}} 条记录
<script type="text/javascript">
function _goToPage(page , url){
	if(typeof(loadAjaxData) == "undefined"){
		// location.href = url;
		$('.pagination a').on('click', function (event) {
	        event.preventDefault();
	        if ( $(this).attr('href') != '#' ) {
	            $.ajax({
	                url: $(this).attr('href'),
	                type: 'GET',
	                // data:{'_token': "{{ csrf_token() }}"},
	                success: function(data) {
	                }
	            });
	        }
	    });
	}else{
		loadAjaxData(page);
	}
}
</script>