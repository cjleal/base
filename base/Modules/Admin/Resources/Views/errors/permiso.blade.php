@extends('admin::layouts.default')
@section('content')

<div class="row">
	<div class="col-sm-12" style="height: 400px; padding: 100px 0;">
		<h3>
			{{ Lang::get('backend.without_permission') }}
		</h3>
	</div>
</div>

@endsection