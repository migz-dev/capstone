<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\StoreNursesNoteRequest;
use App\Http\Requests\Faculty\UpdateNursesNoteRequest;
use App\Models\NursesNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursesNoteController extends Controller
{
    /* ----------------------------------------------------------
     | List
     * ---------------------------------------------------------- */
    public function index(Request $request)
    {
        $notes = NursesNote::owned()
            ->with(['encounter.patient'])
            ->when($request->filled('format'), fn ($q) => $q->where('format', $request->string('format')))
            ->when($request->date('from'),   fn ($q, $d) => $q->where('noted_at', '>=', $d))
            ->when($request->date('to'),     fn ($q, $d) => $q->where('noted_at', '<=', $d))
            ->when($request->integer('encounter_id'), fn ($q, $id) => $q->where('encounter_id', $id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $needle = '%'.$request->string('q').'%';
                $q->where(function ($qq) use ($needle) {
                    $qq->orWhere('patient_name', 'like', $needle)   // ← include free-text patient name
                       ->orWhere('narrative',   'like', $needle)
                       ->orWhere('dar_data',    'like', $needle)
                       ->orWhere('dar_action',  'like', $needle)
                       ->orWhere('dar_response','like', $needle)
                       ->orWhere('soapie_s',    'like', $needle)
                       ->orWhere('soapie_o',    'like', $needle)
                       ->orWhere('soapie_a',    'like', $needle)
                       ->orWhere('soapie_p',    'like', $needle)
                       ->orWhere('soapie_i',    'like', $needle)
                       ->orWhere('soapie_e',    'like', $needle)
                       ->orWhere('pie_p',       'like', $needle)
                       ->orWhere('pie_i',       'like', $needle)
                       ->orWhere('pie_e',       'like', $needle)
                       ->orWhere('remarks',     'like', $needle);
                });
            })
            ->orderByDesc('noted_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('faculty.chartings.nn_index', compact('notes'));
    }

    /* ----------------------------------------------------------
     | Create
     * ---------------------------------------------------------- */
    public function create()
    {
        return view('faculty.chartings.nn_create');
    }

    /* ----------------------------------------------------------
     | Store
     * ---------------------------------------------------------- */
    public function store(StoreNursesNoteRequest $request)
    {
        $data = $this->extractPayload($request);
        $data['faculty_id'] = Auth::guard('faculty')->id();

        $note = NursesNote::create($data);

        return redirect()
            ->route('faculty.chartings.nurses_notes.show', $note->id)
            ->with('status', 'Nurse’s note saved.');
    }

    /* ----------------------------------------------------------
     | Show
     * ---------------------------------------------------------- */
    public function show(NursesNote $note)
    {
        $this->ensureOwnership($note);

        return view('faculty.chartings.nn_show', [
            'id'   => $note->id,
            'note' => $note->loadMissing('encounter.patient'),
        ]);
    }

    /* ----------------------------------------------------------
     | Edit
     * ---------------------------------------------------------- */
    public function edit(NursesNote $note)
    {
        $this->ensureOwnership($note);

        return view('faculty.chartings.nn_edit', [
            'id'   => $note->id,
            'note' => $note->loadMissing('encounter.patient'),
        ]);
    }

    /* ----------------------------------------------------------
     | Update
     * ---------------------------------------------------------- */
    public function update(UpdateNursesNoteRequest $request, NursesNote $note)
    {
        $this->ensureOwnership($note);

        $data = $this->extractPayload($request);
        $note->update($data);

        return redirect()
            ->route('faculty.chartings.nurses_notes.show', $note->id)
            ->with('status', 'Nurse’s note updated.');
    }

    /* ----------------------------------------------------------
     | Destroy
     * ---------------------------------------------------------- */
    public function destroy(NursesNote $note)
    {
        $this->ensureOwnership($note);

        $note->delete();

        return redirect()
            ->route('faculty.chartings.nurses_notes.index')
            ->with('status', 'Nurse’s note deleted.');
    }

    /* ==========================================================
     | Helpers
     * ========================================================== */

    /**
     * Normalize the payload based on selected format.
     * Only relevant fields are kept; others are explicitly nulled.
     */
    protected function extractPayload(Request $request): array
    {
        $format = (string) $request->input('format', 'narrative');

        // Common fields
        $data = [
            'patient_name' => $request->input('patient_name'), // ← free-text label
            'encounter_id' => $request->input('encounter_id'), // optional (may be null)
            'noted_at'     => $request->input('noted_at'),
            'format'       => $format,
            'remarks'      => $request->input('remarks'),
        ];

        // Reset all format-specific fields first (avoid leftovers on update)
        $data += [
            'narrative'     => null,

            'dar_data'      => null,
            'dar_action'    => null,
            'dar_response'  => null,

            'soapie_s'      => null,
            'soapie_o'      => null,
            'soapie_a'      => null,
            'soapie_p'      => null,
            'soapie_i'      => null,
            'soapie_e'      => null,

            'pie_p'         => null,
            'pie_i'         => null,
            'pie_e'         => null,
        ];

        // Fill only the active format fields
        switch ($format) {
            case 'dar':
                $data['dar_data']     = $request->input('dar_data');
                $data['dar_action']   = $request->input('dar_action');
                $data['dar_response'] = $request->input('dar_response');
                break;

            case 'soapie':
                $data['soapie_s'] = $request->input('soapie_s');
                $data['soapie_o'] = $request->input('soapie_o');
                $data['soapie_a'] = $request->input('soapie_a');
                $data['soapie_p'] = $request->input('soapie_p');
                $data['soapie_i'] = $request->input('soapie_i');
                $data['soapie_e'] = $request->input('soapie_e');
                break;

            case 'pie':
                $data['pie_p'] = $request->input('pie_p');
                $data['pie_i'] = $request->input('pie_i');
                $data['pie_e'] = $request->input('pie_e');
                break;

            default: // narrative
                $data['narrative'] = $request->input('narrative');
                $data['format']    = 'narrative';
        }

        return $data;
    }

    /** Defense-in-depth: ensure the note belongs to the logged-in CI. */
    protected function ensureOwnership(NursesNote $note): void
    {
        abort_unless(
            $note->faculty_id === Auth::guard('faculty')->id(),
            403,
            'You do not have access to this note.'
        );
    }
}