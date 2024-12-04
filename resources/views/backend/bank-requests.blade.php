@extends('main')

@section('bodycls','backend')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/dataTables.bootstrap4.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('js/back/requests.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">

        <div class="row">
            <div class="col-12">
                @include('backend.b-sidebar')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="p-5">
                    <h3 class="mb-5 text-dark">{{ __('Withdrawal requests') }}</h3>
                    <table id="tbl-requests" class="table table-stripped table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Balance</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Account #</th>
                                <th>Bank</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $request)
                                <tr>
                                    <td>{{ $request->user_id }}</td>
                                    <td>{{ $request->balance }}</td>
                                    <td>{{ $request->email }}</td>
                                    <td>{{ $request->account_name }}</td>
                                    <td>{{ $request->account_number }}</td>
                                    <td>{{ $request->BankName }}</td>
                                    <td class="text-success"><b>${{ $request->amount }}</b></td>
                                    <td>{{ Str::upper($request->status) }}</td>
                                    <td>
                                        @if($request->status == 'requested' )
                                        <a href="{{ route('backend.request.validate') }}" data-token="{{ csrf_token() }}" data-id="{{$request->id}}" class="btn-procesar btn btn-primary btn-sm" type="button">
                                            {{ __('Process') }}
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
       

    
</div>
@endsection