@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Admin Panel') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action">Users</a>
                                    <a href="{{ route('businesses.index') }}" class="list-group-item list-group-item-action">Businesses</a>
                                    <a href="#" class="list-group-item list-group-item-action">Settings</a>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header">Admin Dashboard</div>
                                    <div class="card-body">
                                        <h5 class="card-title">Welcome, {{ Auth::user()->email }}</h5>
                                        <p class="card-text">You are logged in as an admin.</p>
                                        <a href="{{ route('users.index') }}" class="btn btn-primary">Manage Users</a>
                                        <a href="{{ route('businesses.index') }}" class="btn btn-primary">Manage Businesses</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
