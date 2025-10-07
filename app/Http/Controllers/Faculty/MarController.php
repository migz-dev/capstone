<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\StoreMarRequest;
use App\Http\Requests\Faculty\UpdateMarRequest;
use App\Models\Encounter;
use App\Models\MarRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarController extends Controller
{
    public function index(Request $request)
    {
        $mar = MarRecord::owned()
            ->with(['patient','encounter'])
            ->when($request->filled('q'), function($q) use ($request) {
                $needle = '%'.$request->q.'%';
                $q->where('medication','like',$needle)
                  ->orWhereHas('patient', fn($qq)=>$qq->where('full_name','like',$needle));
            })
            ->orderByDesc('administered_at')
            ->paginate(20)
            ->withQueryString();

        return view('faculty.chartings.mar_index', compact('mar'));
    }

    public function create()
    {
        return view('faculty.chartings.mar_create');
    }

    public function store(StoreMarRequest $request)
    {
        $facultyId = (int) auth('faculty')->id();

        $mar = DB::transaction(function() use ($request, $facultyId) {

            // 1) Create/resolve Patient (quick)
            $ln       = trim((string) $request->input('quick_patient.last_name'));
            $fn       = trim((string) $request->input('quick_patient.first_name'));
            $fullName = trim($ln.', '.$fn, ', ');

            $patient = Patient::create([
                'full_name'  => $fullName,
                'hospital_no'=> $request->input('quick_patient.hospital_no'),
                // 'sex' => $request->input('quick_patient.sex') // add when UI has it
            ]);

            // 2) Create Encounter (quick)
            $encounter = Encounter::create([
                'patient_id' => $patient->id,
                'faculty_id' => $facultyId,
                'unit'       => $request->input('quick_encounter.unit'),
                'started_at' => $request->input('quick_encounter.started_at'),
                'notes'      => null, // your table uses 'remarks' — set via next line
                'remarks'    => $request->input('quick_encounter.remarks'),
            ]);

            // 3) Create MAR
            return MarRecord::create([
                'faculty_id'      => $facultyId,
                'patient_id'      => $patient->id,
                'encounter_id'    => $encounter->id,
                'medication'      => $request->string('medication'),
                'dose_amount'     => $request->input('dose_amount'),
                'dose_unit'       => $request->input('dose_unit'),
                'route'           => $request->input('route'),
                'frequency'       => $request->input('frequency'),
                'is_prn'          => (bool) $request->boolean('is_prn'),
                'prn_reason'      => $request->input('prn_reason'),
                'administered_at' => $request->input('administered_at'),
                'omitted_reason'  => $request->input('omitted_reason'),
                'effects'         => $request->input('effects'),
                'remarks'         => $request->input('remarks'),
            ]);
        });

        return redirect()->route('faculty.chartings.mar.index')
            ->with('status', 'MAR entry saved.');
    }

    public function show(MarRecord $mar)
    {
        abort_unless($mar->faculty_id === auth('faculty')->id(), 403);
        $mar->load(['patient','encounter']);
        return view('faculty.chartings.mar_show', compact('mar'));
    }

    public function edit(MarRecord $mar)
    {
        abort_unless($mar->faculty_id === auth('faculty')->id(), 403);
        $mar->load(['patient','encounter']);
        return view('faculty.chartings.mar_edit', compact('mar'));
    }

    public function update(UpdateMarRequest $request, MarRecord $mar)
    {
        abort_unless($mar->faculty_id === auth('faculty')->id(), 403);

        $mar->update($request->validated());

        return redirect()->route('faculty.chartings.mar.edit', $mar)
            ->with('status', 'MAR entry updated.');
    }

    public function destroy(MarRecord $mar)
    {
        abort_unless($mar->faculty_id === auth('faculty')->id(), 403);
        $mar->delete();

        return redirect()->route('faculty.chartings.mar.index')
            ->with('status', 'MAR entry deleted.');
    }
}
