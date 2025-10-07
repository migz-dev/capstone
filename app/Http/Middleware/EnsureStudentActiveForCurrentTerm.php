<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureStudentActiveForCurrentTerm
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $student = DB::table('students')->where('email', $user->email)->first();
        $term    = DB::table('academic_terms')->where('is_current', 1)->first();

        if (! $student || ! $term) {
            return redirect()->route('student.regcard.revalidate')
                ->with('error', 'Access requires verification for the current term.');
        }

        $row = DB::table('student_semester_statuses')
            ->where(['student_id' => $student->id, 'term_id' => $term->id])
            ->first();

        $expired = $row && $row->expires_at && now()->greaterThan($row->expires_at);

        if (! $row || $row->status !== 'active' || $expired) {
            return redirect()->route('student.regcard.revalidate')
                ->with('error', 'Access requires verification for the current term.');
        }

        return $next($request);
    }
}
