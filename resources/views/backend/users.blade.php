@extends('main')

@section('bodycls','backend')
@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/dataTables.bootstrap4.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('js/back/users.js') }}"></script>

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
                    <h3 class="mb-5 text-dark">{{ __('Users') }}</h3>
                    <table id="tbl-users" class="table table-stripped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>PJ Category</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            @php
                            $category = App\Categories::getCategory($user->category_id);
                            @endphp
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ Str::upper($user->Role) }}</td>
                                    <td>{{ $category->name ?? '' }}</td>
                                    <td>{{ $user->created_at }}</td>
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