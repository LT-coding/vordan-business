@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Businesses') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('businesses.create') }}" class="btn btn-primary">{{ __('Add New Business') }}</a>
                        </div>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Tax Code</th>
                                <th>Register Code</th>
                                <th>Registered Address</th>
                                <th>Activity Address</th>
                                <th>Avatar</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($businesses as $business)
                                <tr>
                                    <td>{{ $business->id }}</td>
                                    <td>{{ $business->company_name }}</td>
                                    <td>{{ $business->account->tax_code }}</td>
                                    <td>{{ $business->account->register_code }}</td>
                                    <td>{{ $business->account->registered_address }}</td>
                                    <td>{{ $business->account->activity_address }}</td>
                                    <td>
                                        @if ($business->account->avatar)
                                            <img src="{{ asset('storage/' . $business->account->avatar) }}" alt="Avatar" style="width: 50px;">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('businesses.edit', $business->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                                        <form action="{{ route('businesses.destroy', $business->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
