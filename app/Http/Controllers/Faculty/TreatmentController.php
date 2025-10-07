<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\StoreTreatmentRequest;
use App\Http\Requests\Faculty\UpdateTreatmentRequest;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\TreatmentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $treatments = TreatmentRecord::owned()
            ->with(['patient','encounter'])
            ->when($request->filled('q'), function($q) use ($request) {
                $needle = '%'.$request->q.'%';
                $q->where('procedure_name','like',$needle)
                  ->orWhereHas('patient', fn($qq)=>$qq->where('full_name','like',$needle));
            })
            ->orderByDesc('started_at')
            ->paginate(20)
            ->withQueryString();

        return view('faculty.chartings.treatment_index', compact('treatments'));
    }

    public function create()
    {
        return view('faculty.chartings.treatment_create');
    }

    public function store(StoreTreatmentRequest $request)
    {
        $fid = (int) auth('faculty')->id();
        $faculty = auth('faculty')->user();

        DB::transaction(function() use ($request, $fid, $faculty) {
            // quick patient
            $ln       = trim((string) $request->input('quick_patient.last_name'));
            $fn       = trim((string) $request->input('quick_patient.first_name'));
            $fullName = trim($ln.', '.$fn, ', ');
            $patient  = Patient::create([
                'full_name'   => $fullName,
                'hospital_no' => $request->input('quick_patient.hospital_no'),
            ]);

            // quick encounter
            $enc = Encounter::create([
                'patient_id' => $patient->id,
                'faculty_id' => $fid,
                'unit'       => $request->input('quick_encounter.unit'),
                'started_at' => $request->input('quick_encounter.started_at'),
                'remarks'    => $request->input('quick_encounter.remarks'),
            ]);

            // treatment
            TreatmentRecord::create([
                'faculty_id'       => $fid,
                'patient_id'       => $patient->id,
                'encounter_id'     => $enc->id,
                'procedure_name'   => $request->string('procedure_name'),
                'indication'       => $request->input('indication'),
                'consent_obtained' => (bool) $request->boolean('consent_obtained'),
                'sterile_technique'=> (bool) $request->boolean('sterile_technique'),
                'started_at'       => $request->input('started_at'),
                'ended_at'         => $request->input('ended_at'),
                'performed_by'     => $request->input('performed_by') ?: ($faculty?->display_name ?? 'CI'),
                'assisted_by'      => $request->input('assisted_by'),
                'outcome'          => $request->input('outcome'),
                'complications'    => $request->input('complications'),
                'remarks'          => $request->input('remarks'),
                'pre_notes'        => $request->input('pre_notes'),
                'post_notes'       => $request->input('post_notes'),
            ]);
        });

        return redirect()->route('faculty.chartings.treatment.index')
            ->with('status','Treatment record saved.');
    }

    public function show(TreatmentRecord $treatment)
    {
        abort_unless($treatment->faculty_id === auth('faculty')->id(), 403);
        $treatment->load(['patient','encounter']);
        return view('faculty.chartings.treatment_show', compact('treatment'));
    }

    public function edit(TreatmentRecord $treatment)
    {
        abort_unless($treatment->faculty_id === auth('faculty')->id(), 403);
        $treatment->load(['patient','encounter']);
        return view('faculty.chartings.treatment_edit', compact('treatment'));
    }

    public function update(UpdateTreatmentRequest $request, TreatmentRecord $treatment)
    {
        abort_unless($treatment->faculty_id === auth('faculty')->id(), 403);
        $treatment->update($request->validated());

        return redirect()->route('faculty.chartings.treatment.edit',$treatment)
            ->with('status','Treatment record updated.');
    }

    public function destroy(TreatmentRecord $treatment)
    {
        abort_unless($treatment->faculty_id === auth('faculty')->id(), 403);
        $treatment->delete();

        return redirect()->route('faculty.chartings.treatment.index')
            ->with('status','Treatment record deleted.');
    }
}
