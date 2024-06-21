@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Business') }}</div>

                    <div class="card-body">
                        <form action="{{ route('businesses.update', $business->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="company_name">{{ __('Company Name') }}</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $business->company_name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="avatar">{{ __('Avatar') }}</label>
                                <input type="file" name="avatar" id="avatar" class="form-control-file">
                                @if ($business->account->avatar)
                                    <img src="{{ asset('storage/' . $business->account->avatar) }}" alt="Avatar" style="width: 100px; height: 100px; margin-top: 10px;">
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="tax_code">{{ __('Tax Code') }}</label>
                                <input type="text" name="tax_code" id="tax_code" class="form-control" value="{{ old('tax_code', $business->account->tax_code) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="register_code">{{ __('Register Code') }}</label>
                                <input type="text" name="register_code" id="register_code" class="form-control" value="{{ old('register_code', $business->account->register_code) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="registered_address">{{ __('Registered Address') }}</label>
                                <input type="text" name="registered_address" id="registered_address" class="form-control" value="{{ old('registered_address', $business->account->registered_address) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="activity_address">{{ __('Activity Address') }}</label>
                                <input type="text" name="activity_address" id="activity_address" class="form-control" value="{{ old('activity_address', $business->account->activity_address) }}">
                            </div>

                            <div class="form-group">
                                <label for="users">{{ __('Users') }}</label>
                                <select name="users[]" id="users" class="form-control" multiple>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, $business->users->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
