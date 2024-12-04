<div class="container">
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        <div class="flex p-3 justify-center">
            <p>{{ Session::get('success') }}</p>
        </div>
    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger" role="alert">
        <div class="flex p-3 justify-center">
            <p>{!! Session::get('error') !!}</p>
        </div>
    </div>
@endif
</div>