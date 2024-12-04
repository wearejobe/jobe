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
                    <h3 class="mb-5 text-dark">
                        {{ __('translation::translation.languages') }}
                        <div class="flex flex-grow justify-end items-center">

                            <a href="{{ route('languages.create') }}" class="btn btn-primary">
                                {{ __('translation::translation.add') }}
                            </a>

                        </div>
                    </h3>
                    @if(count($languages))

                        <div class="card">

                            <div class="panel-body">

                                <table>

                                    <thead>
                                        <tr>
                                            <th>{{ __('translation::translation.language_name') }}</th>
                                            <th>{{ __('translation::translation.locale') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($languages as $language => $name)
                                            <tr>
                                                <td>
                                                    {{ $name }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('languages.translations.index', $language) }}">
                                                        {{ $language }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection