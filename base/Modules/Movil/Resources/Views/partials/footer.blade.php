	<script type="text/javascript">
		var $url = "{{ URL::current() }}/",
		sessionLife = {{ \Config::get('session.lifetime') }};
	</script>
	
@if (isset($html['js']))
@foreach ($html['js'] as $js)
	<script type="text/javascript" src="{{ url($js) }}?v={{ env('APP_VERSION') }}"></script>
@endforeach
@endif

	@stack('js')