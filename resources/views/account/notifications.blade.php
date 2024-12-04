@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/pages/notifications.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.jobs-sidebar')
                
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1>{{__('tr_general.notifications')}}</h1>
                    <div class="notifications-container mt-5">
                        <div class="list-group">
                            @forelse ($n as $noti)
                                @if($loop->index < 15)

                                <a href="{{$noti->fields->url}}" data-read="{{ route('readNotification',['id'=>$noti->id]) }}" class="notification list-group-item list-group-item-action flex-column align-items-start {{ ( $noti->read_at ==null ) ? 'active':'' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <p class="mb-1">{{ __($noti->fields->title) }}</p>
                                        <small>{{ App\Main::localizeAndHuman($noti->created_at) }}</small>
                                    </div>
                                      <small class="mb-1">{{ $noti->fields->description}}</small>
                                      {{-- <small>Donec id elit non mi porta.</small> --}}
                                </a>
                                @endif
                            @empty
                                <div class="empty-alert">
                                    {{__('tr_general.no_notifications')}}
                                </div>
                            @endforelse
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection