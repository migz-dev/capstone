<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth('faculty')->user();

        $data = $request->validate([
            'name'   => ['required','string','max:255'],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        // name maps to full_name via mutator
        $user->name = $data['name'];

        if ($request->hasFile('avatar')) {
            // delete old file if we stored a local path
            if (!empty($user->profile_image) && !preg_match('#^https?://#i', $user->profile_image)) {
                $old = str_starts_with($user->profile_image, 'avatars/')
                    ? $user->profile_image
                    : 'avatars/'.$user->profile_image;
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('avatar')->store('avatars', 'public'); // e.g. avatars/abc.jpg
            $user->profile_image = $path; // store the path
        }

        $user->save();

        return back()->with('ok', 'Profile updated.');
    }

    public function removeAvatar(Request $request)
    {
        $user = auth('faculty')->user();

        if (!empty($user->profile_image) && !preg_match('#^https?://#i', $user->profile_image)) {
            $old = str_starts_with($user->profile_image, 'avatars/')
                ? $user->profile_image
                : 'avatars/'.$user->profile_image;
            Storage::disk('public')->delete($old);
        }

        $user->profile_image = null;
        $user->save();

        return back()->with('ok', 'Profile photo removed.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth('faculty')->user();

        $request->validate([
            'current_password' => ['required','string'],
            'password'         => ['required','confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Your current password is incorrect.'])
                ->withInput();
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('ok', 'Password updated.');
    }
}
