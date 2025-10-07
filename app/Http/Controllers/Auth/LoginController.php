<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email:rfc,dns'],
            'password' => ['required', 'string'],
            'remember' => ['nullable'],
        ]);

        $remember = $request->boolean('remember');

        // ✅ Use the student guard explicitly (doesn't touch faculty/admin sessions)
        if (! Auth::guard('web')->attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        )) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        // Login successful (safe to rotate session id; won’t log out other guards)
        $request->session()->regenerate();

        // Decide landing: dashboard if active & not expired, else revalidate
        $user    = Auth::guard('web')->user();
        $student = DB::table('students')->where('email', $user->email)->first();
        $term    = DB::table('academic_terms')->where('is_current', 1)->first();

        if ($student && $term) {
            $row = DB::table('student_semester_statuses')
                ->where(['student_id' => $student->id, 'term_id' => $term->id])
                ->first();

            $expired = $row && $row->expires_at && now()->greaterThan($row->expires_at);

            if ($row && $row->status === 'active' && ! $expired) {
                // Only honor intended URLs under /student
                $intended = $request->session()->pull('url.intended');
                $path = $intended ? parse_url($intended, PHP_URL_PATH) : null;

                if ($path && Str::startsWith($path, '/student')) {
                    return redirect()->to($intended);
                }

                return redirect()->intended(RouteServiceProvider::HOME);
            }

            return redirect()
                ->route('student.regcard.revalidate')
                ->with('error', 'Access requires verification for the current term.');
        }

        // Fallback (no student record or no current term)
        return redirect()
            ->route('student.regcard.revalidate')
            ->with('error', 'Access requires verification for the current term.');
    }

    public function destroy(Request $request)
    {
        // ✅ Logout ONLY the web (student) guard
        Auth::guard('web')->logout();

        // If no other guards are logged in, you can safely invalidate.
        $otherGuardsActive = Auth::guard('faculty')->check() || Auth::guard('admin')->check();

        if (! $otherGuardsActive) {
            // Full session kill only if this was the last/only guard
            $request->session()->invalidate();
        }

        // Always rotate the CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
