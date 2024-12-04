@extends('main')

@section('bodycls','backend')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/dataTables.bootstrap4.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('js/back/transfers.js') }}"></script>
@endpush
<div class="container">

    <div class="card card-main-content no-border">

        <div class="row">
            <div class="col-12">
                @include('backend.b-sidebar')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="p-5">
                    <h3 class="mb-5 text-dark">{{ __('Incomming transfer') }}</h3>
                    <table id="tbl-transfers" class="table table-stripped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Reference #</th>
                                <th>T. Name</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transfers as $transfer)
                                <tr>
                                    <td>{{ $transfer->user_id }}</td>
                                    <td>{{ $transfer->user_email }}</td>
                                    <td>{{ $transfer->t_number }}</td>
                                    <td>{{ $transfer->t_name }}</td>
                                    <td>{{ App\Main::humanDate($transfer->t_date) }}</td>
                                    <td class="text-success"><b>${{ $transfer->total }}</b></td>
                                    <td>{{ Str::upper($transfer->status) }}</td>
                                    <td>
                                        @if($transfer->status == 'pending' )
                                        <a href="{{ route('backend.transfer.validate') }}" data-token="{{ csrf_token() }}" data-id="{{$transfer->id}}" class="btn btn-validate btn-primary btn-sm" type="button">
                                            {{ __('Validate') }}
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