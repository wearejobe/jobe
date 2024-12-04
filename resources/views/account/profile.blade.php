@extends('main')

@section('body')

@push('head')
<link rel="stylesheet" href="{{ URL::asset('css/jquery-ui.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('css/plugins/croppie.css') }}" type="text/css" />
<script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('js/inputmask.jquery.js') }}"></script>
<script src="{{ URL::asset('js/plugins/croppie.min.js') }}"></script>
<script src="{{ URL::asset('js/pages/avatar-save.js') }}"></script>
<script>var firstTime = false;</script>
@endpush
<div class="container profile">

    <div class="card card-main-content no-border mb-5">
        {{-- <h3 class="page-title">Your Profile</h3> --}}
        <div class="p-3">        
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <button onclick="event.preventDefault(); document.getElementById('profile-save').submit();"  class="btn btn-success btn-block mt-3" id="btn-save">{{ __('Save') }}</button>
                    </div>
                    {{-- <div class="col-sm-3">
                        
                    </div>
                    <div class="col-sm-3">
                        
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card bg-lightgray no-border rounded">
                            @php
                            $cat = null;
                            if(property_exists($uf,'profile_pj_category')):
                                $cat =  App\PjCategories::find($uf->profile_pj_category); 
                            endif; 
                            @endphp
                            {{-- @if($cat) --}}
                            <div class="card-body text-left cat cat-{{ Str::lower($cat->name ?? 'silver') }}">
                                <h6 class="mb-4">
                                    {{ __('You are ') }} {{ $cat->name ?? 'silver' }}<br>
                                    <small><a target="_blank" href="https://wearejobe.com/profesional-jobe/">{{ __('See how to get higher') }}</a></small>
                                </h6>
                            
                            {{-- @endif --}}
                                <div class="avatar mx-auto white">
                                    @if( property_exists($uf,'avatar') )
                                        @php $imgFile = App\Upload::getAvatarFile($uf->avatar); @endphp
                                        <img src="{{ $imgFile }}" class="rounded-circle custom-avatar img-fluid">
                                    @else
                                        <img src="{{ asset('images/default-avatar.svg') }}" class="rounded-circle img-fluid">
                                    @endif
                                    <button data-toggle="modal" data-target="#mdl-avatar" class="btn btn-small btn-dark btn-circle btn-sm btn-edit"><span class="material-icons">create</button>
                                </div>
                                <h4 class="font-weight-bold mt-3 text-center">{{ Auth::user()->name }} {{ $uf->profile_lastname ?? '' }}</h4>
                                <p class="text-muted text-center">{{ $uf->profile_profession ?? '' }}</span>
                            </div>
                            
                            @if($rating!=null)
                            <div class="card-body rating text-center text-white rating-{{ Str::kebab($rating->value) }}">
                                <p>Your rating</p>
                                <h5>{{ number_format($rating->value,1) }}</h5>


                                <div class="stars"> 
                                <?php 
                                    $rvalue = $rating->value;  //enter how many stars to rating
                                    $max_stars = 5; //enter maximum no.of stars
                                    $entero = is_int($rvalue);
                                    for ($i = 1; $i <= $max_stars; $i++){?>
                                    <?php if(ceil($rvalue) == $i && $entero == false) { ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php } elseif(ceil($rvalue) >= $i) { ?>
                                        <i class="fas fa-star"></i>
                                    <?php } else { ?>
                                        <i class="far fa-star"></i>
                                    <?php } 
                                    }?>
                                </div>
                                
                                <span>{{ $rating->number }} reviews</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="list-group mt-3 nav-tabs">
                            <a data-toggle="tab" href="#tab-personal" role="tab" aria-controls="tab-personal" aria-selected="true" class="list-group-item list-group-item-action active"><span class="material-icons">account_circle</span>{{ __('Personal') }}</a>
                            <a data-toggle="tab" href="#tab-account" role="tab" aria-controls="tab-account" aria-selected="true" class="list-group-item list-group-item-action"><span class="material-icons">tune</span>{{ __('Account') }}</a>
                            <a data-toggle="tab" href="#tab-professional" role="tab" aria-controls="tab-professional" aria-selected="true" class="list-group-item list-group-item-action"><span class="material-icons">business_center</span>{{ __('Professional') }}</a>
                            <a data-toggle="tab" href="#tab-wallet" role="tab" aria-controls="tab-wallet" aria-selected="true" class="list-group-item list-group-item-action"><span class="material-icons">account_balance_wallet</span>{{ __('My Wallet') }}</a>
                            <a href="{{ __('logout') }}" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="list-group-item list-group-item-action"><span class="material-icons">power_settings_new</span>{{ __('Logout') }}</a>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="card bg-lightgray no-border rounded">
                            <div class="card-body">
                            
                                <form action="{{ route('profile.save') }}" id="profile-save" method="post">
                                    @csrf
                                    <input type="hidden" id="return_url" name="return_url" value="{{ Request::url() }}" />
                                    <input type="hidden" value="{{ $uf->profile_pj_category ?? '1' }}" name="pj_category">
                                    <div class="tab-content" id="tabs-profile">
                                        <div class="tab-pane fade show active p-5" id="tab-personal" role="tabpanel" aria-labelledby="tab-personal">
                                            <h4 class="mb-4">{{ __('Personal') }}</h4>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="name" class="form-label">{{ __('First Name') }}</label>
                                                    <div class="frm-control-container">
                                                        <input id="name" type="name" class="form-control" name="name" value="{{ Auth::user()->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="name" class="form-label">{{ __('Last Name') }}</label>
                                                    <div class="frm-control-container">
                                                        <input id="name" type="name" class="form-control" name="lastname" value="{{ $uf->profile_lastname ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="email" class="form-label">{{ __('E-mail') }}</label>
                                                    <div class="frm-control-container">
                                                        <input readonly class="form-control" value="{{ Auth::user()->email }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="country" class="form-label">{{ __('Profession') }}</label>
                                                    <div class="frm-control-container">
                                                        <input type="text" class="form-control" name="profession" placeholder="Web Developer" value="{{ $uf->profile_profession  ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                                    <div class="frm-control-container input-group">
                                                        <input type="text" name="country_code" class="form-control" style="max-width:60px" value="{{ $uf->profile_country_code ?? '' }}" placeholder="000">
                                                        <input id="phone" type="phone" class="form-control" placeholder="(___) _______" name="phone" value="{{ $uf->profile_phone ?? '' }}">
                                                        <span class="input-group-append">
                                                            <span class="input-group-text" >
                                                                <span title="{{ __('Country code + area + number')}}" data-toggle="tooltip" data-placement="top"  class="material-icons">info</span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="address" class="form-label">{{ __('Address') }}</label>
                                                    <div class="frm-control-container">
                                                        <input id="address" type="text" class="form-control" name="address" value="{{ $uf->profile_address ?? '' }}">
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="birth" class="form-label">{{ __('Date of birth') }}</label>
                                                    <div class="frm-control-container">
                                                        <div class="input-group">
                                                            <select name="bday" id="bday" class="form-control">
                                                                <option>{{ __('Day')}}</option>
                                                                @for($i=1;$i<=31;$i++)
                                                                    @isset($uf->profile_bday)
                                                                        <option {{ $i == $uf->profile_bday ? 'selected':'' }} value="{{ $i }}">{{ $i }}</option>
                                                                    @else
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endisset
                                                                @endfor
                                                            </select>
                                                            <select name="bmonth" id="bmonth" class="form-control">
                                                                <option>{{ __('Month')}}</option>
                                                                @foreach($months as $nm => $month)
                                                                    @isset($uf->profile_bmonth)
                                                                        <option {{ $nm == $uf->profile_bmonth ? 'selected':'' }} value="{{ $nm }}">{{ $month }}</option>
                                                                    @else
                                                                        <option value="{{ $nm }}">{{ $month }}</option>
                                                                    @endisset
                                                                @endforeach
                                                            </select>
                                                            <select name="byear" id="byear" class="form-control">
                                                                <option>{{ __('Year')}}</option>
                                                                @foreach($years as $y)
                                                                    @isset($uf->profile_byear)
                                                                        <option  {{ $y == $uf->profile_byear ? 'selected':'' }} value="{{ $y }}">{{ $y }}</option>
                                                                    @else
                                                                        <option value="{{ $y }}">{{ $y }}</option>
                                                                    @endisset
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="country" class="form-label">{{ __('Country') }}</label>
                                                    <div class="frm-control-container">
                                                        <select id="country" class="form-control" name="country">
                                                            <option>{{ __('Select Country') }}</option>
                                                            @foreach($countries as $country)
                                                                @isset($uf->profile_country)
                                                                    <option {{ $uf->profile_country == $country->id ? 'selected':'' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                                    @else
                                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                                @endisset
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="tab-pane fade p-5" id="tab-account" role="tabpanel" aria-labelledby="tab-account">
                                            <h4 class="mb-4">{{ __('Account') }}</h4>
                                            <div class="form-group row">
                                                
                                                <div class="col-sm-6">
                                                    <label for="currency" class="form-label">{{ __('Currency') }}</label>
                                                    <div class="frm-control-container">
                                                        <select id="currency" class="form-control" name="currency">
                                                            <option>{{ __('Select Currency') }}</option>
                                                            @foreach($currencies as $currency)
                                                                @isset($uf->profile_currency)
                                                                    <option {{ $currency->id == $uf->profile_currency ? 'selected':'' }} value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                                @else
                                                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                                @endisset
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="lang" class="form-label">{{ __('Language') }}</label>
                                                    <div class="frm-control-container">
                                                        <select id="lang" class="form-control" name="lang">
                                                            <option>{{ __('Select Language') }}</option>
                                                            @foreach($languages as $language)
                                                                @isset($uf->profile_lang)
                                                                    <option {{ $uf->profile_lang == $language->id ? 'selected' : '' }} value="{{ $language->id }}">{{ $language->name }}</option>
                                                                @else
                                                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                                                                @endisset
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-1">
                                                <div class="col-md-2">
                                                    <label class="form-label">{{ __('Password') }}</label><br>
                                                </div>
                                                <div class="col">
                                                    <a href="{{ route('password.request') }}" class="btn btn-outline-info btn-sm">{{ __('Change password')}}</a>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-1">
                                                <div class="col-sm-6">
                                                    <label for="linkedin" class="form-label">{{ __('Linked in') }}</label>
                                                    <input type="url" name="linkedin" id="linkedin"  class="form-control" value="{{$uf->profile_linkedin ?? '' }}" placeholder="{{ __('https://www.linkedin.com/in/user/') }}" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade p-5" id="tab-professional" role="tabpanel" aria-labelledby="tab-professional">
                                            <h4 class="mb-4">{{ __('Professional')}}</h4>
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <label for="profesional_description" class="form-label">{{ __('Professional Description') }}</label>
                                                    <textarea name="prof_des" id="profesional_description" cols="30" rows="5" class="form-control">{{ $uf->profile_prof_des ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="profesional_description" class="form-label">{{ __('Professional Description') }}</label>
                                                    <div class="frm-control-container">
                                                        <select id="category" class="form-control" name="category">
                                                            <option value="">{{ __('Select category') }}</option>
                                                            @foreach($categories as $cat)
                                                                @isset($uf->profile_category)
                                                                    <option {{ $cat->id == $uf->profile_category ? 'selected':'' }} value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                                @else
                                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                                @endisset
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <label for="experience_description" class="form-label">{{ __('Experience Description') }}</label>
                                                    <textarea name="exp_desc" id="experience_description" cols="30" rows="5" class="form-control">{{ $uf->profile_exp_desc ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-8">
                                                    <label for="skill_source" class="form-label">{{ __('Skills') }}</label>
                                                    <input type="text" id="skills" autocomplete="off" class="jobe-autocomplete-helper form-control">
                                                    <input type="hidden" name="skill_source" value="{{ $uf->profile_skill_source ?? '' }}" id="skills-source">
                                                    <div class="add-suggestion-container d-none">
                                                        <a id="btn-add-suggestion" href="{{ route('api.addSkill') }}" data-token="{{ csrf_token() }}">{{__('Add as new skill')}}</a>
                                                    </div>
                                                    <div class="skills-container mt-2">
                                                        @isset($skills)
                                                            @foreach(json_decode($skills) as $skill)
                                                                <button data-id="{{ $skill->id }}" type="button" class="btn btn-sm btn-outline-info tag-item">{{ __($skill->name) }}<span class="material-icons ml-1">highlight_off</span></button>
                                                            @endforeach
                                                        @endisset
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @push('head')
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.16/moment-timezone-with-data.min.js"></script>

                                        <script src="{{ URL::asset('js/profile.js') }}"></script>
                                        @isset($uf->profile_category)
                                        <script>
                                        jQuery(function($){
                                            getSkills('<?=csrf_token()?>',"<?=$uf->profile_category?>");
                                        })
                                        </script>
                                        @endisset
                                        <script>
                                        jQuery(function($){   
                                            $("#category").change(function(e){
                                                var category = $(this).val();
                                                console.log(category);
                                                getSkills('<?=csrf_token()?>',category);
                                            });
                                        });
                                        </script>
                                        
                                        @endpush
                                        <div class="tab-pane fade p-5" id="tab-wallet" role="tabpanel" aria-labelledby="tab-wallet">
                                            <h4 class="mb-4">{{ __('Wallet')}}</h4>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    
                                                    <div class="card wallet-card no-border round">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 text-right">
                                                                    <h3 class="balance">JOBE</h3>
                                                                </div>
                                                            </div>
                                                            @isset($jobeac)
                                                            <?php $wallet = json_decode($jobeac)?>
                                                            <div class="row">
                                                                <div class="col-sm-12 text-left">
                                                                <h3 class="balance mb-0">{{ $c ?? ''}}{{ number_format($wallet->a,2) }}</h3>
                                                                <h6 class="text-bold my-3 account-number"><?php echo $wallet->n ?></h6>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 text-left">
                                                                    <h6 class="text-uppercase m-0">{{ Auth::user()->name }} {{ $uf->profile_lastname ?? '' }}</h6>
                                                                </div>
                                                            </div>
                                                            @endisset
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col">
                                                            
                                                            <button data-target="#mdl-request-withdrawal-ba" data-toggle="modal" type="button" class="btn btn-dark">{{ __('Request Bank Account Withdrawal') }}</button>
                                                            
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


{{-- MDL Bank account withdrawal --}}
<div class="modal fade" id="mdl-request-withdrawal-ba">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content no-border">
            <div class="card no-border">
                <div class="card-body p-0">
                    <div class="container frm-banck-account-withdrawal">
                        <form action="{{ route('payment.request-baw') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 bg-light">
                                    <h6 class="mt-3">{{__('Enter your account info')}}</h6>
                                    <hr>
                                    <div class="form-group">
                                        <label for="account_name">{{__('Enter Account Name')}}</label>
                                        <input required name="account_name" id="account_name" class="form-control" placeholder="" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">{{__('Enter Account Number')}}</label>
                                        <input required name="account_number" id="account_number" class="form-control" placeholder="" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="image-selector">{{__('Bank')}}</label>
                                        <select required name="account_bank" class="form-control" id="">
                                            <option value="">{{__('Select your bank')}}</option>
                                            @php 
                                            $banksSV = App\FormOptions::getSvBanks();
                                            @endphp
                                            @forelse ($banksSV as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->heading }}</option>
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="form-group text-center">
                                        <button class="btn btn-primary" type="submit">
                                            {{ _('Request') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="container request-result d-none">
                        <div class="row">
                            <div class="col-sm-12 bg-light">
                                <h6 class="mt-3">{{__('Request Sent')}}</h6>
                            </div>
                            <div class="col-sm-12 bg-light">
                                <div class="request-message"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="card-footer bg-dark">
                    <button data-dismiss="modal" class="btn btn-light float-right">Close</button>
                    {{-- <button type="submit" id="btn-new-task" class="btn btn-sm btn-success btn-add-task float-right">Save</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- MDL Bank account withdrawal --}}
{{-- MDL AVATAR --}}
<div class="modal fade" id="mdl-avatar">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content no-border">
            <div class="card no-border">
                <div class="card-body p-0">
                    <div class="container image-selection">
                        <div class="row">
                            <div class="col-sm-12 bg-light">
                                <h6 class="mt-3">{{__('Change your profile picture')}}</h6>
                                <hr>
                                <div class="form-group text-center">
                                    <label>{{__('Select picture')}}</label>
                                </div>
                                <div class="form-group text-center">
                                    <label for="image-selector" class="btn btn-primary">{{__('Select from your device')}}</label>
                                    <input id="image-selector" class="form-control bg-light border-dark invisible" placeholder="John Doe" type="file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container image-cropper d-none">
                        <div class="row">
                            <div class="col-sm-12 bg-light">
                                <h6 class="mt-3">{{__('Crop profile picture')}}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-light text-center">
                                <div id="cropper-profile">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-light text-center">
                                <div class="form-group">
                                    @csrf
                                    <input type="hidden" name="upload" value="{{ route('upload.data')}}">
                                    <input type="hidden" name="urlav" value="{{ route('avatar.save')}}">
                                    <button id="btn-change" class="btn btn-secondary" type="button">{{__('Change picture')}}</button>
                                    <button id="btn-save-avatar" class="btn btn-success" type="button">{{__('Save profile picture')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-dark">
                    <button data-dismiss="modal" class="btn btn-light float-right">Cancel</button>
                    {{-- <button type="submit" id="btn-new-task" class="btn btn-sm btn-success btn-add-task float-right">Save</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- MDL AVATAR --}}


{{--     Adiós al freelance, bienvenido a Jobe, el centro de negocios más grande del mundo. 

    Estás a punto de formar parte del equipo de profesionales mejor calificado para prestar sus servicios a empresas de gran prestigio a nivel mundial.
    
    A partir de hoy serás un Professional Jobe o PJ. Para iniciar actualiza tu perfil y comienza a recibir ofertas de negocios. --}}


@if ( session('open_tab') )
@push('head')
<script>
jQuery(function($){
    var url_h = "<?=session('open_tab')?>";
    $('.nav-tabs a[href="#' + url_h.split('#')[1] + '"]').tab('show');
});
</script>
@endpush
@endif
{{-- first time code --}}
@if ( session('first-time') == 'true' )
@push('head')
<link href="{{ URL::asset('css/swiper.min.css') }}" type="text/css" rel="stylesheet" />
<script src="{{ URL::asset('js/swiper.min.js') }}"></script>
<script>
firstTime = true;
</script>
@endpush

    <!-- Modal -->

    <div class="modal fade" id="slide-first-time" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            
                <div class="sw-first-time-container swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <h2 class="text-center">Adiós al freelance, bienvenido a Jobe, el centro de negocios más grande del mundo.</h2>
                            <img src="{{ asset('images/jobe-network.png') }}">
                        </div>
                        <div class="swiper-slide">
                            <h2 class="text-center">Estás a punto de formar parte del equipo de profesionales mejor calificado para prestar sus servicios a empresas de gran prestigio a nivel mundial.</h2>
                            <img src="{{ asset('images/teamwork.png') }}">
                        </div>
                        <div class="swiper-slide">
                            <h2 class="text-center">A partir de hoy serás un Professional Jobe o PJ. Para iniciar actualiza tu perfil y comienza a recibir ofertas de negocios.</h2>
                            <img src="{{ asset('images/PJ.png') }}">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-sm-3 text-center">
                                    <button type="button" data-dismiss="modal" class="btn btn-outline-dark">{{ __('Start') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    {{-- <div class="swiper-button-prev"></div> --}}
                </div>
                
                
            </div>
        </div>
    </div>

@endif
</div>
@endsection
<?php session()->forget('first-time'); ?>