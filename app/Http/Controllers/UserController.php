<?php

namespace App\Http\Controllers;

use App\Mail\UserPasswordMail;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessUser;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $employeeUserIds = BusinessEmployee::where('owner_id', Auth::id())->pluck('user_id');
        $users = User::whereIn('id', $employeeUserIds)->get();

        return view('users.index', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $businesses = Business::with('account')->whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
        return view('users.create', compact('businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'businesses' => 'required|array',
            'businesses.*' => 'exists:businesses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate a random password
        $password = User::generatePassword();

        // Create the user
        $user = User::create([
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $password, // Store the password hash in the database
        ]);

        $owner = Auth::user();

        BusinessEmployee::create([
            'user_id' => $user->id,
            'owner_id' => $owner->id,
        ]);

        // Attach user to selected businesses from the form
        foreach ($request->input('businesses') as $businessId) {
            BusinessUser::create([
                'user_id' => $user->id,
                'business_id' => $businessId,
            ]);
        }

        // Send email with the password
        Mail::to($user->email)->send(new UserPasswordMail($user, $password));

        return redirect()->route('users.index')->with('status', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $businesses = Business::with('account')->whereHas('users', function ($query) use ($id) {
            $query->where('user_id', Auth::id());
        })->get();

        $user = User::find($id);

        return view('users.edit', compact('businesses', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id), // Ignore the current user's email
            ],
            'phone' => [
                'required',
                Rule::unique('users')->ignore($user->id), // Ignore the current user's phone number
            ],
            'businesses' => 'required|array',
            'businesses.*' => 'exists:businesses,id',
        ]);

        $user->update([
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        $user->businesses()->sync($request->input('businesses', []));

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
