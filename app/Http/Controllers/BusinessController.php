<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessAccount;
use App\Models\BusinessEmployee;
use App\Models\BusinessUser;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $businesses = Business::with('account')->whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('businesses.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $users = [];
        if ($this->isOwner(Auth::id())) {
            $employeeUserIds = BusinessEmployee::where('owner_id', Auth::id())->pluck('user_id');
            $users = User::whereIn('id', $employeeUserIds)->get();
        }

        return view('businesses.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'tax_code' => 'required|string|max:255',
            'register_code' => 'required|string|max:255',
            'registered_address' => 'required|string|max:255',
            'activity_address' => 'nullable|string|max:255',
            'users' => 'array',
            'users.*' => 'exists:users,id'
        ]);

        $business = Business::create([
            'company_name' => $request->input('company_name'),
            'avatar' => $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
            'verified' => null,
        ]);

        BusinessAccount::create([
            'business_id' => $business->id,
            'tax_code' => $request->input('tax_code'),
            'register_code' => $request->input('register_code'),
            'registered_address' => $request->input('registered_address'),
            'activity_address' => $request->input('activity_address'),
            'avatar' => $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
        ]);

        $userIds = $request->input('users', []);
        $userIds[] = Auth::id();

        foreach ($userIds as $userId) {
            BusinessUser::create([
                'user_id' => $userId,
                'business_id' => $business->id,
            ]);
        }

        return redirect()->route('businesses.index')->with('status', 'Business created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $business = Business::findOrFail($id);

        return view('businesses.show', compact('business'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $business = Business::findOrFail($id);
        $users = [];
        if ($this->isOwner(Auth::id())) {
            $employeeUserIds = BusinessEmployee::where('owner_id', Auth::id())->pluck('user_id');
            $users = User::whereIn('id', $employeeUserIds)->get();
        }

        return view('businesses.edit', compact('business', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'tax_code' => 'required|string|max:255',
            'register_code' => 'required|string|max:255',
            'registered_address' => 'required|string|max:255',
            'activity_address' => 'nullable|string|max:255',
            'users' => 'array',
            'users.*' => 'exists:users,id'
        ]);

        $business = Business::findOrFail($id);
        $business->update([
            'company_name' => $request->input('company_name'),
            'avatar' => $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : $business->avatar,
        ]);

        $businessAccount = $business->account;
        $businessAccount->update([
            'tax_code' => $request->input('tax_code'),
            'register_code' => $request->input('register_code'),
            'registered_address' => $request->input('registered_address'),
            'activity_address' => $request->input('activity_address'),
            'avatar' => $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : $businessAccount->avatar,
        ]);

        $userIds = $request->input('users', []);
        $userIds[] = Auth::id();

        BusinessUser::where('business_id', $business->id)->delete();

        foreach ($userIds as $userId) {
            BusinessUser::create([
                'user_id' => $userId,
                'business_id' => $business->id,
            ]);
        }

        return redirect()->route('businesses.index')->with('status', 'Business updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $business = Business::findOrFail($id);
        $business->delete();

        return redirect()->route('businesses.index')->with('status', 'Business deleted successfully');
    }

    /**
     * Check if the user is an owner of any business.
     */
    private function isOwner($userId): bool
    {
        return Business::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->exists();
    }
}
