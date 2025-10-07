<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminSettingsController extends Controller
{
    public function edit(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $displayName  = $admin->display_name ?? $admin->full_name ?? $admin->name ?? 'Admin';
        $displayEmail = $admin->email ?? '';
        $imagePath    = $admin->profile_image;                  // << correct column
        $avatarUrl    = $imagePath ? Storage::url($imagePath) : null;

        // Build initials
        $parts    = preg_split('/\s+/', trim($displayName)) ?: [];
        $initials = mb_strtoupper(collect($parts)->filter()->map(fn($p) => mb_substr($p, 0, 1))->join(''));

        return view('admin.admin-settings', compact('displayName', 'displayEmail', 'initials', 'avatarUrl'));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $admin = Auth::guard('admin')->user();

        // store on public disk, e.g. storage/app/public/...
        $path = $request->file('avatar')->store("avatars/admins/{$admin->id}", 'public');

        // persist to correct column
        DB::table('admins')->where('id', $admin->id)->update([
            'profile_image' => $path,                           // << correct column
            'updated_at'    => now(),
        ]);

        return back()->with('success', 'Profile photo updated.');
    }

    public function removeAvatar(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // delete existing file if present
        if (!empty($admin->profile_image)) {                   // << correct column
            Storage::disk('public')->delete($admin->profile_image);
        }

        // set to null on the correct column
        DB::table('admins')->where('id', $admin->id)->update([
            'profile_image' => null,                           // << correct column
            'updated_at'    => now(),
        ]);

        return back()->with('success', 'Profile photo removed.');
    }
}
