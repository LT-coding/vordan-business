@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Business') }}</div>

                    <div class="card-body">
                        <form action="{{ route('businesses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="company_name">{{ __('Company Name') }}</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="logo">{{ __('logo') }}</label>
                                <input type="file" name="logo" id="logo" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="tax_code">{{ __('Tax Code') }}</label>
                                <input type="text" name="tax_code" id="tax_code" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="register_code">{{ __('Register Code') }}</label>
                                <input type="text" name="register_code" id="register_code" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="registered_address">{{ __('Registered Address') }}</label>
                                <input type="text" name="registered_address" id="registered_address" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="activity_address">{{ __('Activity Address') }}</label>
                                <input type="text" name="activity_address" id="activity_address" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="users">{{ __('Users') }}</label>
                                <select name="users[]" id="users" class="form-control" multiple>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
