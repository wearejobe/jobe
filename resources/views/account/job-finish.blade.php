@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/pages/dashboard.js') }}"></script>
<script src="{{ asset('js/pages/finishing-project.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.job-sidebar')
                
            </div>
            <div class="col-sm-9">
                <div class="p-5">
                    <h6>{{ $job->title }}</h6>
                    <hr>
                    <div class="stats-container container">
                        @if($form)
                            @if($allowFinish)
                                {{-- finish job | worker rating --}}
                                <div class="alert alert-info">
                                    <div>
                                        <span class="material-icons">info</span>                                        
                                        You are finishing this job
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <form action="{{ route('job.finishAndRate',['code'=>$job->md5_id])}}" id="frm-rate" method="post">
                                        @csrf
                                        <p>Share your experience! Your honest feedback provides helpfull information to both the freelancer and the Jobe network.</p>
                                        <hr>

                                        {{-- old rating --}}
                                        {{-- <div class="text-center d-none">
                                            <h6 class="text-capitalize">Rate <span class="text-info">{{ Str::lower($job->pjName) }}</span> work</h6>
                                            <div class="rating-container d-inline-block">
                                                @for($i=1;$i<6;$i++)
                                                <a href="javascript:void(0)" class="rating-star text-info star-{{$i}}">
                                                    <i class="far fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </a>
                                                @endfor
                                            </div>
                                        </div> --}}

                                        <table class="table table-striped tbl-rating">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        1 Poor / 5 Excellent
                                                    </th>
                                                    <th>1</th>
                                                    <th>2</th>
                                                    <th>3</th>
                                                    <th>4</th>
                                                    <th>5</th>
                                                </tr>
                                            </thead>
                                            @foreach ($rating_fields as $item)
                                            <tr>
                                                <td>
                                                    <h6>{{ $item->heading }}</h6>
                                                    <p class="mb-0">{{ $item->description }}</p>
                                                </td>
                                                @for($i=1;$i<6;$i++)
                                                <td>
                                                    {{-- <div class="custom-control custom-radio mb-3"> --}}
                                                        <input type="radio" value="{{ $i }}" class="" id="{{ $item->key .'_'. $i }}" name="rd_{{ $item->id }}[]">
                                                        {{-- <label class="custom-control-label" for="rate_{{ $item->associated}}"></label>
                                                    </div> --}}
                                                </td>
                                                @endfor
                                            </tr>
                                            @endforeach
                                        </table>

                                        <hr>
                                        <input type="hidden" name="rating" id="rating" value="0">
                                        <div class="form-group">
                                            <label for="feedback_message">{{__('Write a feedback for your rating')}}</label>
                                            <textarea name="feedback_message" id="feedback_message" rows="5" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group text-center">
                                            <button class="btn btn-primary" type="submit">
                                                Finish & Rate
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                {{-- can't finish job | pending items --}}
                                <div class="alert alert-warning">
                                    <div>
                                        <span class="material-icons">warning</span>                                        
                                        Can't continue to finish the job/project because of pending items
                                    </div>
                                </div>
                                @php 
                                $countIntervals = count($pendingIntervals);
                                $countPayments = count($pendingPayments);
                                $countTasks = count($pendingTasks);
                                @endphp
                                @if($countIntervals > 0)
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h6>Pending work intervals</h6>
                                        <div id="pending-intervals" class="list-group pending-intervals {{ ($countIntervals>3) ? 'contracted':'' }}">
                                            @foreach($pendingIntervals as $item)
                                                <div class="list-group-item">
                                                    <b>{{ $item->title }}</b><br>
                                                    <small>{{__('Pending to check for payment')}}</small>
                                                </div>    
                                            @endforeach
                                            
                                        </div>
                                        @if($countIntervals > 3)
                                        <button data-target="pending-intervals" class="btn btn-toggle-contracted btn-outline-info btn-block">
                                            <span class="more">More +</span>
                                            <span class="less d-none">Less -</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif
                                
                                @if($countPayments > 0)
                                <div class="row mt-3" >
                                    <div class="col-sm-12">
                                        <h6>Pending Payments</h6>
                                        <div id="pending-payments" class="list-group pending-payments {{ ($countPayments>3) ? 'contracted':'' }}">
                                            @foreach($pendingPayments as $item)
                                                <div class="list-group-item">
                                                    <b>{{ $item->title }}</b><br>
                                                    <small>{{__('Pending to pay')}}</small>
                                                </div>    
                                            @endforeach
                                            
                                        </div>
                                        @if($countPayments > 3)
                                        <button data-target="pending-payments" class="btn btn-toggle-contracted btn-outline-info btn-block">
                                            <span class="more">More +</span>
                                            <span class="less d-none">Less -</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif
                                @if($countTasks > 0)
                                <div class="row mt-3" >
                                    <div class="col-sm-12">
                                        <h6>Pending Taks</h6>
                                        <div id="pending-tasks" class="list-group pending-tasks {{ ($countTasks>3) ? 'contracted':'' }}">
                                            @forelse($pendingTasks as $item)
                                                <div class="list-group-item">
                                                    <b>{{ $item->title }}</b><br>
                                                    <small>{{__('Pending task')}}</small>
                                                </div>    
                                            @endforeach
                                        </div>
                                        @if($countTasks > 3)
                                        <button data-target="pending-tasks" class="btn btn-toggle-contracted btn-outline-info btn-block">
                                            <span class="more">More +</span>
                                            <span class="less d-none">Less -</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            @endif
                            
                        @else
                            <div class="alert alert-success">
                                <div class="p-3">
                                    <span class="material-icons mr-2">info</span>
                                    {{ __('Your job/project has been successfully completed.') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection