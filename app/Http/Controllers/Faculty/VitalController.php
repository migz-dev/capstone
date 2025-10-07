<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\StoreVitalRequest;
use App\Http\Requests\Faculty\UpdateVitalRequest;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VitalController extends Controller
{
    public function index(Request $request)
    {
        $vitals = Vital::owned()
            ->with(['encounter.patient'])
            ->when($request->integer('encounter_id'), fn ($q, $id) => $q->where('encounter_id', $id))
            ->when($request->date('from'), fn ($q, $d) => $q->where('taken_at', '>=', $d))
            ->when($request->date('to'), fn ($q, $d) => $q->where('taken_at', '<=', $d))
            ->orderByDesc('taken_at')
            ->paginate(20)
            ->withQueryString();

        return view('faculty.chartings.vital_signs_index', compact('vitals'));
    }

    public function create()
    {
        return view('faculty.chartings.vital_signs_create');
    }

    public function store(StoreVitalRequest $request)
    {
        $facultyId = (int) auth('faculty')->id();

        $vital = DB::transaction(function () use ($request, $facultyId) {
            // Use existing encounter if provided (and owned by CI)
            $encounterId = (int) $request->input('encounter_id', 0);
            if ($encounterId > 0) {
                $encounter = Encounter::where('id', $encounterId)
                    ->where('faculty_id', $facultyId)
                    ->firstOrFail();
            } else {
                // Build Patient (your table has full_name)
                $ln = trim((string) $request->input('quick_patient.last_name'));
                $fn = trim((string) $request->input('quick_patient.first_name'));
                $fullName = trim($ln . ', ' . $fn, ', ');

                $patient = Patient::create([
                    'full_name' => $fullName,
                    // optional: 'hospital_no','sex','dob','notes'
                ]);

                // Create Encounter (map form "notes" -> DB "remarks")
                $encounter = Encounter::create([
                    'patient_id'   => $patient->id,
                    'faculty_id'   => $facultyId,
                    'unit'         => $request->input('quick_encounter.unit'),
                    'started_at'   => $request->input('quick_encounter.started_at') ?: now(),
                    'ended_at'     => $request->input('quick_encounter.ended_at'),
                    'remarks'      => $request->input('quick_encounter.remarks')   // <- DB column
                                     ?? $request->input('quick_encounter.notes'), // backward compatibility
                    'attending_md' => $request->input('quick_encounter.attending_md'),
                    'admission_dt' => $request->input('quick_encounter.admission_dt'),
                    'discharge_dt' => $request->input('quick_encounter.discharge_dt'),
                ]);
            }

            // Create Vital
            $data = $request->safe()->only([
                'taken_at','temp_c','pulse_bpm','resp_rate',
                'bp_systolic','bp_diastolic','spo2','pain_scale','remarks',
            ]);
            $data['faculty_id']   = $facultyId;
            $data['encounter_id'] = $encounter->id;

            return Vital::create($data);
        });

        return redirect()
            ->route('faculty.chartings.vital_signs.show', $vital)
            ->with('status', 'Vital signs saved.');
    }

    public function show(Vital $vital)
    {
        $this->authorize('view', $vital);
        $vital->loadMissing(['encounter.patient']);
        return view('faculty.chartings.vital_signs_show', compact('vital'));
    }

    public function edit(Vital $vital)
    {
        $this->authorize('update', $vital);
        $vital->loadMissing(['encounter.patient']);
        return view('faculty.chartings.vital_signs_edit', compact('vital'));
    }

    public function update(UpdateVitalRequest $request, Vital $vital)
    {
        $this->authorize('update', $vital);

        $vital->update($request->safe()->only([
            'taken_at','temp_c','pulse_bpm','resp_rate',
            'bp_systolic','bp_diastolic','spo2','pain_scale','remarks',
        ]));

        return redirect()
            ->route('faculty.chartings.vital_signs.edit', $vital)
            ->with('status', 'Vital signs updated.');
    }

    public function destroy(Vital $vital)
    {
        $this->authorize('delete', $vital);
        $vital->delete();

        return redirect()
            ->route('faculty.chartings.vital_signs.index')
            ->with('status', 'Vital signs deleted.');
    }
}
