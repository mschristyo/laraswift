@if ( session('successful'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successful') }}
    </div>
@endif

@if ( session('failed'))
     <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('failed') }}
    </div>
@endif


@if ( session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role-"alert">
        {{ session()->get('warning') }}
    </div>
@endif
	<div id="stripe-alert" class="alert alert-danger display-none">
		<meta>
  </div>

  <div id="success-alert" class="alert alert-success display-none">

  </div>

  <div id="failed-alert" class="alert alert-danger display-none">

  </div>
