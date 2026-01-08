<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's addresses.
     */
    public function addresses(Request $request): View
    {
        return view('profile.addresses', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function deleteAddress(Request $request, \App\Models\UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return Redirect::route('profile.addresses')->with('success', 'Alamat berhasil dihapus.');
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'province_id' => 'required|integer',
            'province_name' => 'required|string',
            'city_id' => 'required|integer',
            'city_name' => 'required|string',
            'district' => 'required|string',
            'subdistrict' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create(array_merge($request->all(), [
            'user_id' => $user->id,
            'is_primary' => $isFirst,
        ]));

        return Redirect::route('profile.addresses')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function setPrimaryAddress(Request $request, \App\Models\UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->makePrimary();

        return Redirect::route('profile.addresses')->with('success', 'Alamat utama berhasil diperbarui.');
    }
}
