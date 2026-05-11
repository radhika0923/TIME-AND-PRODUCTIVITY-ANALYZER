<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index(Request $request): View
    {
        return view('settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (name & email).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $emailChanged = $user->email !== $validated['email'];

        $user->fill($validated);

        // If the email changed, reset verification
        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Re-send verification if email changed
        if ($emailChanged) {
            $user->sendEmailVerificationNotification();

            return redirect()
                ->route('settings')
                ->with('profile_status', 'Profile updated. A verification email has been sent to your new address.');
        }

        return redirect()
            ->route('settings')
            ->with('profile_status', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('settings')
            ->with('password_status', 'Password changed successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out first
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Delete the user and all associated data (cascade via DB)
        $user->delete();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }
}
