<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessAccount;
use App\Models\BusinessUser;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected string $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image'], // Validate as image file
            'register_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('business_accounts')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'tax_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('business_accounts')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'registered_address' => ['required', 'string', 'max:255'],
            'activity_address' => ['nullable', 'string', 'max:255'],
        ]);
    }

    public function showRegistrationForm(Request $request): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $referral = $request->get('referral', null);
        return view('auth.register', compact('referral'));
    }

    protected function create(array $data): User
    {
        $user = User::create([
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('business_admin');

        $business = Business::create([
            'company_name' => $data['company_name'],
            'verified' => null,
            'logo' => $data['logo'] ? $this->storelogo($data['logo']) : null,
        ]);

        BusinessUser::create([
            'user_id' => $user->id,
            'business_id' => $business->id,
        ]);

        BusinessAccount::create([
            'business_id' => $business->id,
            'tax_code' => $data['tax_code'],
            'register_code' => $data['register_code'],
            'registered_address' => $data['registered_address'],
            'activity_address' => $data['activity_address'],
        ]);

        if (isset($data['referral']) && $rUser = User::where('referral_code', $data['referral'])->first()) {
            UserReferral::create([
                'referral_user_id' => $rUser->id,
                'user_id' => $user->id,
            ]);
            $rUser->update(['referral_bonus' => $rUser->referral_bonus + 10]);
        }

        event(new Registered($user));


        return $user;
    }

    protected function storelogo($logo)
    {
        return $logo->store('logos', 'public');
    }
}
