@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/plugins/dropzone.css')}}">
<script src="{{asset('js/plugins/dropzone.js')}}"></script>
@if($usertype=='bj')
<script src="{{ asset('js/pages/hiring.js') }}"></script>
@endif
@if($usertype=='pj')
<script src="{{ asset('js/pages/cards_actions.js') }}"></script>
@endif
<script>
jQuery(function($){
    $('[data-toggle=tooltip]').tooltip();
});
</script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            
            <div class="col-sm-8">
                @if ( session('alert') )
                    <div class="alert alert-info mb-4 ml-3 mt-5">
                        <div class="alert-text">{{ session('alert') }}</div>
                        <div class="alert-dismiss"><a href="javascript:void(0)" data-dismiss="alert" class=""><span class="material-icons">highlight_off</span></a></div>
                    </div>
                @endif
                <div class="p-5">
                    <div class="row ">
                        <div class="col-sm-6 d-flex">
                            {{-- <img src="{{ $pj->avatar_url }} " alt="Avatar" class="sm-avatar mr-2 rounded-circle"> --}}
                            {!! App\User::getAvatar($applicant->id,'sm-avatar mr-2') !!}
                            <div class="user-info">
                                <h6 class="mb-0 mt-2">{{ $pj->fullname  }}<h6>
                                <small class="text-orangered">{{ $pj->profile_profession ?? '' }} </small>
                            </div>
                        </div>
                        <div class="col-sm-6 text-right">
                            {{ $contract->sent  }} {{__('ago')}}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            @if($usertype == 'bj')
                            <small><b>{{ $applicant->name  }}</b> wants to work with you in your project </small>
                            @endif
                            <h3>
                                {{ $job->title }} 
                            </h3>
                            @if($usertype == 'pj')
                            <a href="#"><small class="badge badge-success">View job <span class="material-icons md-12">link</span></small></a>
                        @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <?php echo nl2br($contract->description) ?>
                        </div>
                    </div>
                    @if((array) $files)
                    <hr>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <h6>Attachments</h6>
                            <div class="row">
                                <div class="col-sm-6">
                            @foreach($files as $file)
                                <div class="card bg-dark">
                                    <div class="card-header text-success">{{ $file->filename }}</div>
                                    <a class="btn btn-dark no-round" href="{{ route('download',['code'=>$file->link])}}">
                                        <span class="material-icons mr-2">cloud_download</span>Download
                                    </a>
                                </div>
                            @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            {{-- sidebar --}}
            <div class="col-sm-4 bg-dark sidebar pr-0">
                <div class="p-4 pr-5">
                    @if($usertype == 'bj')
                    <h5 class="text-white-75 mt-4 mb-5">About {{ $applicant->name  }}</h5>
                    <div class="info-block mb-3">
                        <label>Member since</label>
                        {{$applicant->created_at}}
                    </div>
                    <div class="info-block mb-3">
                        <label>Level</label>
                        <div class="row pj-categories">
                            @foreach($pj_categories as $cat)
                                <div class="col-sm-2 mt-2 text-center">
                                    <img src="{{ asset('images/cats/' . $cat->icon ) }}" data-toggle="tooltip" title="{{ $cat->name }}" class="xs-avatar category {{ $pj->pj_category->id==$cat->id ? 'active':''  }}" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="info-block mb-3">
                        <label>{{__('Hourly wage')}}</label>
                        <?php $wage = App\PjCategories::hourly_range($pj->pj_category->hourly_wage); ?>
                        <?php $currency = App\User::getUserCurrency(); ?>
                        {{ _('From ') }} {{ $currency . $wage->min }} 
                        @isset($wage->max)
                        {{ _(' to ') }} 
                        {{ $currency . $wage->max }}
                        @endisset
                    </div>
                    <div class="info-block mb-3">
                        <label>{{__('From')}}</label>
                        {{ $pj->profile_country ?? '' }}
                    </div>
                    <hr>
                    <div class="info-block mb-3">
                        @if(App\Hirings::canHire($applicant->id,$job->id))
                            <button data-toggle="modal" data-target="#mdl-hire" type="button" class="btn btn-success btn-block">Hire</button>
                        @else
                            <div class="alert alert-info">
                                <div class="p-3">
                                    <span class="material-icons mr-1">info</span>
                                    {{ $applicant->name . ' was hired for this job.' }}
                                </div>
                            </div>
                        @endif
                        {{-- <button type="button" class="btn btn-light btn-block">Message</button> --}}
                        {{-- <button type="button" class="btn btn-outline-light btn-block">Don't hire</button> --}}
                    </div>
                    @endif

                    @if($usertype == 'pj')
                    
                    @endif

                    @forelse ($cards as $card)
                        <div class="card {{$card->style}} mt-3">
                            <div class="card-header">
                                {{$card->title}}
                            </div>
                            <div class="card-body">
                                {{$card->description}}

                                <?php $buttons = json_decode($card->actions); ?>
                                @forelse ($buttons as $button)
                                    
                                    <a href="{{$button->href}}" @isset($button->onclick) {{ 'onclick=' . $button->onclick }}  @endisset  class="btn mt-2 btn-block {{$button->style}}">
                                        {{$button->text}}
                                    </a> 
                                @empty

                                @endforelse
                            </div>        
                        </div>
                    @empty
                        
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    
</div>
@if($usertype == 'bj')
<!-- Modal -->
<div class="modal fade" id="mdl-hire" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <form id="frm-hiring" action="{{ route('hire') }}" method="post">
            @csrf
            <input type="hidden" name="appid" value="{{ $contract->id }}">
            
            <div class="modal-content p-0 bg-light no-border">
                <div class="modal-body p-4">
                    <h3 class="page-sub-title mt-3 mb-4">Hiring {{ $applicant->name  }}</h3>
                    <div class="form-group">
                        <label for="">Tell {{ $applicant->name}} how you feel to start working with him/her</label>
                        <textarea required name="hire_message" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cover-letter">{{__('Attach files')}}</label>
                        <div id="hiring-files" class="drop-zone bg-dark p-3 text-center">
                            <div class="dz-message"><label class="text-white-75">{{__('Drop files here')}}</label></div>
                        </div>
                        <div class="help-desc">Add some files like contracts or terms agreements.</div>
                        <input type="hidden" id="uploads-folder" value="{{route('upload')}}">
                        <input type="hidden" id="rm-file-url" value="{{route('frm-file')}}">
                        <div id="hiring-uploaded-files"></div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="accept_terms" id="accept_terms" required />
                        <label class="form-check-label" for="accept_terms">
                            {!! __('I agree Jobe <b>Terms and Conditions</b> about hiring.') !!}
                        </label>
                    </div>
                </div>
                <div class="card-footer bg-dark">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-outline-success float-right" id="btn-hire">Hire</button>
                </div>
            </div>
        </form>
        <div class="hired-content d-none">
            <div class="modal-content p-0 bg-light no-border">
                <div class="modal-body p-4">
                    <h3 class="page-sub-title mt-3 mb-4 text-center">You hired {{ $applicant->name  }}</h3>
                    <p>Your hired {{ $applicant->name }} successfully for {{ $job->title }}. Wait to PJ respond to your hire and lets start to work.</p>
                    <div class="text-center mt-4">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-success">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

@endif


@if($usertype == 'pj')
<!-- Modal view hire -->
<input type="hidden" name="getHireURL" id="getHireURL" value="{{ route('getHire') }}">

<div class="modal fade" id="mdl-hire-pj" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <form action="" id="frm-accept-hire" method="post">   
            <div class="modal-content p-0 bg-light no-border">
                <div class="modal-body p-4">
                    <h3 class="page-sub-title mt-3 mb-4"><span class="company_name"></span> is hiring to you</h3>
                    <div id="hire-content">
                        <h6>From <span class="company_contact"></span>:</h6>
                        <div class="hire-message"></div>
                        <hr>
                        <label><small><b>{{__('Attachments')}}</b></small></label>
                        <div class="hire-files"></div>
                    </div>
                    <hr>
                    
                        @csrf
                        <div class="form-check">
                            <input type="checkbox" name="accept_terms" id="accept_terms" required />
                            <label class="form-check-label" for="accept_terms">
                                {!! __('I agree Jobe <b>Terms and Conditions</b> about hiring.') !!}
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="accept_terms_company" id="accept_terms_company" required />
                            <label class="form-check-label" for="accept_terms_company">
                                {!! __('I confirm that I\'ve read all <strong class="company_name"></strong> terms and conditions.') !!}
                            </label>
                        </div>
                </div>
                <div class="card-footer bg-dark">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Decline</button>
                    <button type="submit" class="btn btn-success float-right" id="btn-hire">Accept</button>
                </div>
            </div>
        </form>
    </div>
  </div>

@endif



@endsection