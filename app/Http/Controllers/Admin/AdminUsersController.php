<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AdminUsersController extends Controller
{
    /**
     * Role pill styles (shared by Users + Archives pages)
     */
    private array $roleStyles = [
        'Clinical Instructor' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'icon' => 'graduation-cap'],
        'Student Nurse' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'icon' => 'book-open'],
        'Admin' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'icon' => 'shield'],
    ];

    /**
     * Helpers to detect optional archive columns on tables.
     */
    private bool $facultyHasArchivedAt;
    private bool $studentsHasArchivedAt;

    public function __construct()
    {
        $this->facultyHasArchivedAt = Schema::hasColumn('faculty', 'archived_at');
        $this->studentsHasArchivedAt = Schema::hasColumn('students', 'archived_at');
    }

    /**
     * GET /admin/users
     * Active/All (non-archived) list — matches your existing look.
     */
    public function index(Request $request)
    {
        $perPage = (int) ($request->integer('per_page') ?: 10);
        $page = (int) max(1, $request->integer('page') ?: 1);

        // --- Clinical Instructors (faculty) — exclude archived
// Faculty: exclude archived
$cis = DB::table('faculty')
    ->select(['faculty_id','full_name as name','email','profile_image','status','created_at'])
    ->when(Schema::hasColumn('faculty','archived_at'), fn($q) => $q->whereNull('archived_at'))
    ->orderByDesc('created_at')
    ->get()
    ->map(function ($r) {
        $status = $r->status === 'approved' ? 'Active' : ucfirst($r->status ?? 'Inactive');
        $avatar = $r->profile_image ? Storage::url($r->profile_image) : null;
        return (object)[
            'id' => $r->faculty_id,
            'name' => $r->name,
            'email' => $r->email,
            'role' => 'Clinical Instructor',
            'status' => $status,
            'created_at' => $r->created_at,
            'avatar_url' => $avatar,
        ];
    });

// Students: exclude archived (archived_at IS NULL)
// (If you also use is_active, keep your existing where('s.is_active',1) as you like)
$students = DB::table('students as s')
    ->leftJoin('users as u', 'u.email', '=', 's.email')
    ->select(['s.student_number','s.full_name as name','s.email','s.is_active','s.created_at','u.avatar_path'])
    ->when(Schema::hasColumn('students','archived_at'), fn($q) => $q->whereNull('s.archived_at'))
    ->orderByDesc('s.created_at')
    ->get()
    ->map(function ($r) {
        $status = ($r->is_active ?? 0) ? 'Active' : 'Inactive';
        $avatar = $r->avatar_path ? Storage::url($r->avatar_path) : null;
        return (object)[
            'id' => $r->student_number,
            'name' => $r->name,
            'email' => $r->email,
            'role' => 'Student Nurse',
            'status' => $status,
            'created_at' => $r->created_at,
            'avatar_url' => $avatar,
        ];
    });


        // --- Students — exclude archived (is_active = 0 means archived)
        $students = DB::table('students as s')
            ->leftJoin('users as u', 'u.email', '=', 's.email') // if you store avatar_path on users
            ->select([
                's.student_number',
                's.full_name as name',
                's.email',
                's.is_active',
                's.created_at',
                'u.avatar_path',
            ])
            ->where('s.is_active', 1) // only active for the main page
            ->orderByDesc('s.created_at')
            ->get()
            ->map(function ($r) {
                $status = ($r->is_active ?? 0) ? 'Active' : 'Inactive';
                $avatar = $r->avatar_path ? Storage::url($r->avatar_path) : null;

                return (object) [
                    'id' => $r->student_number, // shown as "ID: {student_number}"
                    'name' => $r->name,
                    'email' => $r->email,
                    'role' => 'Student Nurse',
                    'status' => $status,
                    'created_at' => $r->created_at,
                    'avatar_url' => $avatar,
                ];
            });

        // --- Merge, sort, paginate
        $all = $cis->merge($students)->sortByDesc('created_at')->values();
        $total = $all->count();
        $items = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => url()->current(),
            'query' => $request->query(),
        ]);

        return view('admin.admin-users', [
            'usersPage' => $paginator,
            'roleStyles' => $this->roleStyles,
        ]);
    }

    /**
     * GET /admin/users/archives
     * Archived list — mirrors your archives page table.
     * Faculty: status = 'archived' (+ optional archived_at)
     * Students: is_active = 0 (+ optional archived_at)
     */
    public function archives(Request $request)
    {
        $perPage = (int) ($request->integer('per_page') ?: 10);
        $page = (int) max(1, $request->integer('page') ?: 1);

// Archived CIs
$archivedCis = DB::table('faculty')
    ->select(['faculty_id','full_name as name','email','profile_image','status','created_at','archived_at'])
    ->whereNotNull('archived_at')
    ->orderByDesc('archived_at')
    ->get()
    ->map(function ($r) {
        $avatar = $r->profile_image ? Storage::url($r->profile_image) : null;
        return (object)[
            'id'          => $r->faculty_id,
            'name'        => $r->name,
            'email'       => $r->email,
            'role'        => 'Clinical Instructor',
            'status'      => 'Archived',
            'created_at'  => $r->created_at,
            'archived_at' => $r->archived_at,
            'avatar_url'  => $avatar,
        ];
    });

// Archived Students
$archivedStudents = DB::table('students as s')
    ->leftJoin('users as u', 'u.email', '=', 's.email')
    ->select(['s.student_number','s.full_name as name','s.email','s.is_active','s.created_at','u.avatar_path','s.archived_at'])
    ->whereNotNull('s.archived_at')
    ->orderByDesc('s.archived_at')
    ->get()
    ->map(function ($r) {
        $avatar = $r->avatar_path ? Storage::url($r->avatar_path) : null;
        return (object)[
            'id'          => $r->student_number,
            'name'        => $r->name,
            'email'       => $r->email,
            'role'        => 'Student Nurse',
            'status'      => 'Archived',
            'created_at'  => $r->created_at,
            'archived_at' => $r->archived_at,
            'avatar_url'  => $avatar,
        ];
    });


        // --- Archived Students (is_active = 0)
        $archivedStudentsQuery = DB::table('students as s')
            ->leftJoin('users as u', 'u.email', '=', 's.email')
            ->select([
                's.student_number',
                's.full_name as name',
                's.email',
                's.is_active',
                's.created_at',
                'u.avatar_path',
            ])
            ->where('s.is_active', 0);

        if ($this->studentsHasArchivedAt) {
            $archivedStudentsQuery->addSelect('s.archived_at');
        }

        $archivedStudents = $archivedStudentsQuery
            ->orderByDesc($this->studentsHasArchivedAt ? 's.archived_at' : 's.updated_at')
            ->get()
            ->map(function ($r) {
                $avatar = $r->avatar_path ? Storage::url($r->avatar_path) : null;

                return (object) [
                    'id' => $r->student_number,
                    'name' => $r->name,
                    'email' => $r->email,
                    'role' => 'Student Nurse',
                    'status' => 'Archived',
                    'created_at' => $r->created_at,
                    'archived_at' => property_exists($r, 'archived_at') ? $r->archived_at : null,
                    'avatar_url' => $avatar,
                ];
            });

        // Merge and paginate
        $all = $archivedCis->merge($archivedStudents)->sortByDesc(function ($r) {
            return $r->archived_at ?: $r->created_at;
        })->values();

        $total = $all->count();
        $items = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => url()->current(),
            'query' => $request->query(),
        ]);

        return view('admin-archives.admin-users-archives', [
            'archivedUsersPage' => $paginator,
            'roleStyles' => $this->roleStyles,
        ]);

    }

    /**
     * GET /admin/users/{id}
     * Return a single record (tries CI by faculty_id, then Student by student_number).
     * Ideal for powering the "VIEW" modal (returns JSON).
     */
    public function show($id)
    {
        // Try CI
        $ci = DB::table('faculty')->where('faculty_id', $id)->first();
        if ($ci) {
            $avatar = $ci->profile_image ? Storage::url($ci->profile_image) : null;
            return response()->json([
                'id' => $ci->faculty_id,
                'name' => $ci->full_name,
                'email' => $ci->email,
                'role' => 'Clinical Instructor',
                'status' => $ci->status === 'archived' ? 'Archived' : ($ci->status === 'approved' ? 'Active' : ucfirst($ci->status ?? 'Inactive')),
                'created_at' => $ci->created_at,
                'archived_at' => $this->facultyHasArchivedAt ? ($ci->archived_at ?? null) : null,
                'avatar_url' => $avatar,
            ]);
        }

        // Try Student
        $st = DB::table('students as s')
            ->leftJoin('users as u', 'u.email', '=', 's.email')
            ->select([
                's.student_number',
                's.full_name',
                's.email',
                's.is_active',
                's.created_at',
                'u.avatar_path',
            ])
            ->when($this->studentsHasArchivedAt, function ($q) {
                $q->addSelect('s.archived_at');
            })
            ->where('s.student_number', $id)
            ->first();

        if ($st) {
            $avatar = $st->avatar_path ? Storage::url($st->avatar_path) : null;
            return response()->json([
                'id' => $st->student_number,
                'name' => $st->full_name,
                'email' => $st->email,
                'role' => 'Student Nurse',
                'status' => ($st->is_active ?? 0) ? 'Active' : 'Archived',
                'created_at' => $st->created_at,
                'archived_at' => $this->studentsHasArchivedAt ? ($st->archived_at ?? null) : null,
                'avatar_url' => $avatar,
            ]);
        }

        abort(404);
    }

    /**
     * POST /admin/users/{id}/restore
     * CI: status -> approved, archived_at -> null (if exists)
     * ST: is_active -> 1, archived_at -> null (if exists)
     */
    public function restore($id)
    {
        // Restore CI
        $ci = DB::table('faculty')->where('faculty_id', $id)->first();
        if ($ci) {
            $payload = ['status' => 'approved'];
            if ($this->facultyHasArchivedAt) {
                $payload['archived_at'] = null;
            }
            DB::table('faculty')->where('faculty_id', $id)->update($payload);

            return back()->with('flash_success', 'Clinical Instructor restored.');
        }

        // Restore Student
        $st = DB::table('students')->where('student_number', $id)->first();
        if ($st) {
            $payload = ['is_active' => 1];
            if ($this->studentsHasArchivedAt) {
                $payload['archived_at'] = null;
            }
            DB::table('students')->where('student_number', $id)->update($payload);

            return back()->with('flash_success', 'Student restored.');
        }

        return back()->with('flash_error', 'Record not found.');
    }

    /**
     * DELETE /admin/users/{id}
     * Permanently delete the record.
     * CI: delete from faculty
     * ST: delete from students
     */
    public function destroy($id)
    {
        // Delete CI
        $deleted = DB::table('faculty')->where('faculty_id', $id)->delete();
        if ($deleted) {
            return back()->with('flash_success', 'Clinical Instructor permanently deleted.');
        }

        // Delete Student
        $deleted = DB::table('students')->where('student_number', $id)->delete();
        if ($deleted) {
            return back()->with('flash_success', 'Student permanently deleted.');
        }

        return back()->with('flash_error', 'Record not found.');
    }

public function archive($id)
{
    $now = now();

    // Try CI (faculty)
    $ci = DB::table('faculty')->where('faculty_id', $id)->first();
    if ($ci) {
        if (!Schema::hasColumn('faculty', 'archived_at')) {
            return response()->json(['ok' => false, 'message' => 'archived_at column missing on faculty.'], 422);
        }
        DB::table('faculty')->where('faculty_id', $id)->update([
            'archived_at' => $now,
        ]);

        return response()->json(['ok' => true, 'message' => 'User archived (CI).']);
    }

    // Try Student
    $st = DB::table('students')->where('student_number', $id)->first();
    if ($st) {
        if (!Schema::hasColumn('students', 'archived_at')) {
            return response()->json(['ok' => false, 'message' => 'archived_at column missing on students.'], 422);
        }
        DB::table('students')->where('student_number', $id)->update([
            'archived_at' => $now,
            'is_active'   => 0, // keep this if you also want to toggle access immediately
        ]);

        return response()->json(['ok' => true, 'message' => 'User archived (Student).']);
    }

    return response()->json(['ok' => false, 'message' => 'Record not found.'], 404);
}



    /* -----------------------------------------------------------------
       (Optional) If later you need an "archive" action from main page:
       - CI: set status='archived', archived_at=now()
       - ST: set is_active=0, archived_at=now()
       ----------------------------------------------------------------- */
    protected function archiveNow(): string
    {
        return Carbon::now()->toDateTimeString();
    }
}
