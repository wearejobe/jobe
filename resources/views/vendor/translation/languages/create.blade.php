@extends('translation::layout')

@section('body')
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
                    <h3 class="text-dark mb-5">{{ __('translation::translation.add_language') }}</h3>
                    <div class="card w-1/2">

                        <form action="{{ route('languages.store') }}" method="POST">

                            <fieldset>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="panel-body p-4">

                                    @include('translation::forms.text', ['field' => 'name', 'label' => __('translation::translation.language_name'), ])

                                    @include('translation::forms.text', ['field' => 'locale', 'label' => __('translation::translation.locale'), ])

                                </div>

                            </fieldset>

                            <div class="panel-footer flex flex-row-reverse">

                                <button class="button button-blue">
                                    {{ __('translation::translation.save') }}
                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection