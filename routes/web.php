<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/**
 * Models (used by inline route-model binding closures)
 */
use App\Models\User;
use App\Models\Vital;
use App\Models\NursesNote;
use App\Models\IntakeOutput;

/**
 * Controllers
 */
// Shared Auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterWithRegcardController;
use App\Http\Controllers\Auth\FacultyLoginController;
use App\Http\Controllers\Auth\FacultyRegisterController;
use App\Http\Controllers\Auth\AdminLoginController;

// Student
use App\Http\Controllers\Student\RegcardUploadController;
use App\Http\Controllers\Student\SettingsController;

// Faculty (CI)
use App\Http\Controllers\Faculty\SettingsController as FacultySettingsController;
use App\Http\Controllers\Faculty\ProfileController;
use App\Http\Controllers\Faculty\ProceduresController;
use App\Http\Controllers\Faculty\DrugGuideController;
use App\Http\Controllers\Faculty\VitalController;
use App\Http\Controllers\Faculty\MarController;
use App\Http\Controllers\Faculty\TreatmentController;
use App\Http\Controllers\Faculty\IntakeOutputController;
use App\Http\Controllers\Faculty\NursesNoteController;
use App\Http\Controllers\Faculty\NcpController;
use App\Http\Controllers\Faculty\VitalsEntryController; // optional

// Admin
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\FacultyApprovalsPageController;
use App\Http\Controllers\Admin\AdminProcedureController; // NEW: Admin ⇄ Procedures

// Files
use App\Http\Controllers\FileStreamController;

/**
 * Middleware
 */
use App\Http\Middleware\EnsureStudentActiveForCurrentTerm;
use App\Http\Middleware\EnsureFacultyApproved;

/*
|--------------------------------------------------------------------------
| Feature flags
|--------------------------------------------------------------------------
*/
$designMode = (bool) env('DESIGN_MODE', true); // set to false in production

/*
|--------------------------------------------------------------------------
| Landing / Home (guard-aware)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::guard('admin')->check())   return redirect()->route('admin.dashboard');
    if (Auth::guard('faculty')->check()) return redirect()->route('faculty.dashboard');
    if (Auth::check())                   return redirect()->route('student.dashboard');
    return view('index');
})->name('home');

Route::view('/try', 'try')->name('try');

/*
|--------------------------------------------------------------------------
| DESIGN MODE: Static Student Pages (no DB/guards)
|--------------------------------------------------------------------------
*/
if ($designMode) {
    Route::prefix('student')->name('student.')->group(function () {
        Route::view('/dashboard',     'student.dashboard')->name('dashboard');
        Route::view('/exams',         'student.student-exams')->name('exams');
        Route::view('/leaderboards',  'student.students-leaderboards')->name('leaderboards');
        Route::view('/feedback',      'student.students-feedback')->name('feedback');
        Route::view('/settings',      'student.students-settings')->name('settings');

        // Procedures (static samples)
        Route::view('/procedures',                      'student.student-procedures')->name('procedures');
        Route::view('/procedures/hand-hygiene',         'student.student-open-guide')->name('procedures.open-guide');
        Route::view('/procedures/hand-hygiene/practice','student.student-practice-guide')->name('procedures.practice');

        Route::view('/return-demo', 'student.student-return-demo')->name('return-demo');

        // Convenience
        Route::redirect('/', '/student/dashboard')->name('index');
    });

    // Local helper: preview student dashboard as logged-in user
    if (app()->environment('local')) {
        Route::get('/dev/dashboard', function () {
            $user = User::first() ?? User::make(['name' => 'Designer', 'email' => 'designer@local']);
            Auth::login($user);
            return view('student.dashboard');
        })->name('dev.student.dashboard');
    }
}

/*
|--------------------------------------------------------------------------
| Student Auth (guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::view('/login',           'auth.login')->name('login');
    Route::view('/register',        'auth.register')->name('register');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

    Route::post('/login',    [LoginController::class, 'store'])->middleware('throttle:login')->name('login.store');
    Route::post('/register', [RegisterWithRegcardController::class, 'store'])->name('register.store');
});

// Student logout
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth:web')->name('logout');

/*
|--------------------------------------------------------------------------
| Student: Revalidation (signed-in but may NOT be active)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    Route::view('/student/regcard/revalidate', 'auth.studentregcard-revalidate')->name('student.regcard.revalidate');
    Route::post('/student/regcard/upload', [RegcardUploadController::class, 'store'])->name('student.regcard.upload');
});

/*
|--------------------------------------------------------------------------
| Student: Settings (profile/password/avatar)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    Route::post(  '/student/settings/profile',  [SettingsController::class, 'updateProfile'])->name('student.settings.profile');
    Route::post(  '/student/settings/password', [SettingsController::class, 'updatePassword'])->name('student.settings.password');
    Route::delete('/student/settings/avatar',   [SettingsController::class, 'removeAvatar'])->name('student.settings.avatar.remove');
});

/*
|--------------------------------------------------------------------------
| Student: Core dashboard route when DESIGN_MODE = false
|--------------------------------------------------------------------------
*/
if (!$designMode) {
    Route::prefix('student')->name('student.')->middleware(['auth:web', EnsureStudentActiveForCurrentTerm::class])->group(function () {
        Route::view('/dashboard', 'student.dashboard')->name('dashboard');
    });
}

/*
|--------------------------------------------------------------------------
| File streaming (public disk) – Procedures PDFs (any role)
| Only the filename part is accepted for safety.
|--------------------------------------------------------------------------
*/
Route::get('/files/procedures/{path}', [FileStreamController::class, 'procedure'])
    ->where('path', '[A-Za-z0-9][A-Za-z0-9._-]*')
    ->name('files.procedure');

/*
|--------------------------------------------------------------------------
| Faculty (Clinical Instructor) – Auth + App (separate guard)
|--------------------------------------------------------------------------
*/
Route::prefix('faculty')->name('faculty.')->group(function () {

    // Guest faculty
    Route::middleware('guest:faculty')->group(function () {
        Route::view('/login',    'auth.faculty-login')->name('login');
        Route::view('/register', 'auth.register-faculty')->name('register');

        Route::post('/login',    [FacultyLoginController::class, 'store'])->middleware('throttle:login')->name('login.store');
        Route::post('/register', [FacultyRegisterController::class, 'store'])->name('register.store');
    });

    // Signed-in but NOT approved yet
    Route::middleware('auth:faculty')->group(function () {
        Route::view('/pending', 'faculty.pending')->name('pending');
    });

    // Approved faculty area
    Route::middleware(['auth:faculty', EnsureFacultyApproved::class])->group(function () {

        // Dashboard
        Route::view('/dashboard', 'faculty.dashboard')->name('dashboard');

        // Procedures (authoring + viewing)
        Route::controller(ProceduresController::class)->prefix('procedures')->name('procedures.')->group(function () {
            Route::get( '/',            'index' )->name('index');    // Library
            Route::get( '/create',      'create')->name('create');   // New form
            Route::post('/',            'store' )->name('store');    // Create
            Route::get( '/import',      'import')->name('import');   // Import UI

            // Specific routes BEFORE generic {slug}
            Route::get( '/{slug}/assist','assist')->name('assist');
            Route::get( '/{slug}/edit',  'edit'  )->name('edit');
            Route::post('/{slug}',       'update')->name('update');

            // Generic show LAST
            Route::get( '/{slug}',       'show'  )->name('show');
        });

        // Legacy "Skills" → Procedures
        Route::redirect('/skills', '/faculty/procedures')->name('skills.index');

        // Drug Guide
        Route::prefix('drug-guide')->name('drug_guide.')->group(function () {
            Route::get( '/',          [DrugGuideController::class, 'index'])->name('index');
            Route::get( '/create',    [DrugGuideController::class, 'create'])->name('create');
            Route::post('/',          [DrugGuideController::class, 'store'])->name('store');
            Route::get( '/{id}/edit', [DrugGuideController::class, 'edit'])->whereNumber('id')->name('edit');
            Route::put( '/{id}',      [DrugGuideController::class, 'update'])->whereNumber('id')->name('update');
            Route::post('/{id}/enrich',[DrugGuideController::class, 'enrich'])->whereNumber('id')->name('enrich');
            Route::get( '/{id}',      [DrugGuideController::class, 'show'])->whereNumber('id')->name('show');
        });

        // Encounters (stub)
        Route::view('/encounters/open', 'faculty.encounters-open')->name('encounters.open');

        // Chartings hub + per-type pages
        Route::prefix('chartings')->name('chartings.')->group(function () {

            // Hub
            Route::view('/', 'faculty.chartings')->name('index');

            // Legacy generic /create → hub
            Route::redirect('/create', '/faculty/chartings')->name('create.redirect');

            // 1) Nurse’s Notes
            Route::prefix('nurses-notes')->name('nurses_notes.')->group(function () {
                // Bind {note} to owned records
                Route::bind('note', function ($value) {
                    return NursesNote::where('id', $value)
                        ->where('faculty_id', auth('faculty')->id())
                        ->firstOrFail();
                });

                Route::get( '/',          [NursesNoteController::class, 'index'])->name('index');
                Route::get( '/create',    [NursesNoteController::class, 'create'])->name('create');
                Route::post('/',          [NursesNoteController::class, 'store'])->name('store');
                Route::get( '/{note}',    [NursesNoteController::class, 'show'])->whereNumber('note')->name('show');
                Route::get( '/{note}/edit',[NursesNoteController::class, 'edit'])->whereNumber('note')->name('edit');
                Route::put( '/{note}',    [NursesNoteController::class, 'update'])->whereNumber('note')->name('update');
                Route::delete('/{note}',  [NursesNoteController::class, 'destroy'])->whereNumber('note')->name('destroy');
            });

            // 2) Vital Signs
            Route::prefix('vital-signs')->name('vital_signs.')->group(function () {
                // Bind {vital} to owned records
                Route::bind('vital', function ($value) {
                    return Vital::where('id', $value)
                        ->where('faculty_id', auth('faculty')->id())
                        ->firstOrFail();
                });

                Route::get( '/',          [VitalController::class, 'index'])->name('index');
                Route::get( '/create',    [VitalController::class, 'create'])->name('create');
                Route::post('/',          [VitalController::class, 'store'])->name('store');
                Route::get( '/{vital}',   [VitalController::class, 'show'])->whereNumber('vital')->name('show');
                Route::get( '/{vital}/edit',[VitalController::class, 'edit'])->whereNumber('vital')->name('edit');
                Route::put( '/{vital}',   [VitalController::class, 'update'])->whereNumber('vital')->name('update');
                Route::delete('/{vital}', [VitalController::class, 'destroy'])->whereNumber('vital')->name('destroy');
            });

            // 3) MAR
            Route::prefix('mar')->name('mar.')->controller(MarController::class)->group(function () {
                Route::get( '/',        'index')->name('index');
                Route::get( '/create',  'create')->name('create');
                Route::post('/',        'store')->name('store');
                Route::get( '/{mar}',   'show')->whereNumber('mar')->name('show');
                Route::get( '/{mar}/edit','edit')->whereNumber('mar')->name('edit');
                Route::put( '/{mar}',   'update')->whereNumber('mar')->name('update');
                Route::delete('/{mar}', 'destroy')->whereNumber('mar')->name('destroy');
            });

            // 4) Intake & Output (I&O)
            Route::prefix('intake-output')->name('io.')->group(function () {
                // Bind {io} to owned records
                Route::bind('io', function ($value) {
                    return IntakeOutput::where('id', $value)
                        ->where('faculty_id', auth('faculty')->id())
                        ->firstOrFail();
                });

                Route::get( '/',          [IntakeOutputController::class, 'index'])->name('index');
                Route::get( '/create',    [IntakeOutputController::class, 'create'])->name('create');
                Route::post('/',          [IntakeOutputController::class, 'store'])->name('store');
                Route::get( '/{io}',      [IntakeOutputController::class, 'show'])->whereNumber('io')->name('show');
                Route::get( '/{io}/edit', [IntakeOutputController::class, 'edit'])->whereNumber('io')->name('edit');
                Route::put( '/{io}',      [IntakeOutputController::class, 'update'])->whereNumber('io')->name('update');
                Route::delete('/{io}',    [IntakeOutputController::class, 'destroy'])->whereNumber('io')->name('destroy');
            });

            // 5) Treatment / Procedure
            Route::prefix('treatment')->name('treatment.')->controller(TreatmentController::class)->group(function () {
                Route::get( '/',            'index')->name('index');
                Route::get( '/create',      'create')->name('create');
                Route::post('/',            'store')->name('store');
                Route::get( '/{treatment}', 'show')->whereNumber('treatment')->name('show');
                Route::get( '/{treatment}/edit','edit')->whereNumber('treatment')->name('edit');
                Route::put( '/{treatment}', 'update')->whereNumber('treatment')->name('update');
                Route::delete('/{treatment}','destroy')->whereNumber('treatment')->name('destroy');
            });

            // 6) Nursing Care Plan (NCP)
            Route::prefix('ncp')->name('ncp.')->controller(NcpController::class)->group(function () {
                Route::get( '/',       'index')->name('index');
                Route::get( '/create', 'create')->name('create');
                Route::post('/',       'store')->name('store');
                Route::get( '/{plan}', 'show')->name('show');
                Route::get( '/{plan}/edit','edit')->name('edit');
                Route::put( '/{plan}', 'update')->name('update');
                Route::delete('/{plan}','destroy')->name('destroy');
            });

            // Optional exports placeholder
            Route::view('/export', 'faculty.chartings')->name('export');
        });

        // Optional profile (if separate from settings)
        if (class_exists(ProfileController::class)) {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        }

        // Settings (name/avatar/password)
        Route::view(   '/settings',           'faculty.settings')->name('settings');
        Route::post(   '/settings/profile',   [FacultySettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::delete( '/settings/avatar',    [FacultySettingsController::class, 'removeAvatar'])->name('settings.avatar.remove');
        Route::post(   '/settings/password',  [FacultySettingsController::class, 'updatePassword'])->name('settings.password');
    });

    // Faculty logout
    Route::post('/logout', [FacultyLoginController::class, 'destroy'])->middleware('auth:faculty')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin – Auth + Dashboard + Users + Settings + Approvals + Procedures
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest admin
    Route::middleware('guest:admin')->group(function () {
        Route::view('/login', 'auth.admin-login')->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->middleware('throttle:login')->name('login.store');
    });

    // Authenticated admin
    Route::middleware('auth:admin')->group(function () {
        Route::view('/dashboard', 'admin.admin-dashboard')->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');

        // Static admin UIs
        Route::view('/announcements', 'admin.admin-announcements')->name('announcements.index');
        Route::view('/courses',       'admin.admin-courses')->name('offerings.index');
        Route::view('/return-demo',   'admin.admin-return-demo')->name('return-demo.index');
        Route::view('/reports',       'admin.admin-reports')->name('reports.index');
        Route::view('/approvals',     'admin.admin-approvals')->name('approvals.index');

        // Settings
        Route::get(   '/settings',          [AdminSettingsController::class, 'edit'])->name('settings');
        Route::post(  '/settings/avatar',   [AdminSettingsController::class, 'uploadAvatar'])->name('settings.avatar.upload');
        Route::delete('/settings/avatar',   [AdminSettingsController::class, 'removeAvatar'])->name('settings.avatar.remove');

        // Users (REAL DATA)
        Route::get('/users',            [AdminUsersController::class, 'index'])->name('users.index');
        Route::get('/users/archives',   [AdminUsersController::class, 'archives'])->name('users.archives');

        // User actions (ID is alphanumeric + _ -)
        $idPattern = '[A-Za-z0-9\-_]+';
        Route::post(  '/users/{id}/archive', [AdminUsersController::class, 'archive'])->where('id', $idPattern)->name('users.archive');
        Route::get(   '/users/{id}',         [AdminUsersController::class, 'show'])   ->where('id', $idPattern)->name('users.show');
        Route::post(  '/users/{id}/restore', [AdminUsersController::class, 'restore'])->where('id', $idPattern)->name('users.restore');
        Route::delete('/users/{id}',         [AdminUsersController::class, 'destroy'])->where('id', $idPattern)->name('users.destroy');
        Route::post(  '/users/{id}/destroy', [AdminUsersController::class, 'destroy'])->where('id', $idPattern)->name('users.destroy.post');

        /*
        |--------------------------------------------------------------
        | Admin ⇄ Procedures (REAL DATA)
        |--------------------------------------------------------------
        */
    Route::get( '/procedures',                  [AdminProcedureController::class, 'index'])->name('procedures.index');
    Route::get( '/procedures/create',           [AdminProcedureController::class, 'create'])->name('procedures.create'); // keep BEFORE {procedure}
    Route::post('/procedures',                  [AdminProcedureController::class, 'store'])->name('procedures.store');

    // If you want to be extra-safe against path collisions, add a slug pattern:
    // $slug = '[A-Za-z0-9][A-Za-z0-9\-]*';

    Route::get( '/procedures/{procedure}',      [AdminProcedureController::class, 'show'])->name('procedures.show');     // ->where('procedure', $slug)
    Route::get( '/procedures/{procedure}/edit', [AdminProcedureController::class, 'edit'])->name('procedures.edit');     // ->where('procedure', $slug)
    Route::put( '/procedures/{procedure}',      [AdminProcedureController::class, 'update'])->name('procedures.update'); // ->where('procedure', $slug)

    Route::patch('/procedures/{procedure}/publish',   [AdminProcedureController::class, 'publish'])->name('procedures.publish');     // ->where('procedure', $slug)
    Route::patch('/procedures/{procedure}/unpublish', [AdminProcedureController::class, 'unpublish'])->name('procedures.unpublish'); // ->where('procedure', $slug)

    Route::delete('/procedures/{procedure}',    [AdminProcedureController::class, 'destroy'])->name('procedures.destroy'); // ->where('procedure', $slug)

    // Legacy redirect from old Resources page
    Route::redirect('/resources', '/admin/procedures')->name('resources.index');
});
});

/*
|--------------------------------------------------------------------------
| Convenience redirect for /home
|--------------------------------------------------------------------------
*/
if ($designMode) {
    Route::redirect('/home', '/student/dashboard');
} else {
    Route::redirect('/home', '/student/dashboard')->middleware(['auth:web', EnsureStudentActiveForCurrentTerm::class]);
}

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(fn () => redirect()->route('home'));
