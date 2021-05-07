@extends('layouts.app')

@section('content')
<div class="container">
    @if(Session::has('msg'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('msg') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endisset
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    Project name: <span class="font-weight-bold">{{ $project->name }}</span>
                </div>
            </div>
        </div>
        @yield('childphase')
    </div>
</div>
@endsection
