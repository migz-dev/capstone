<?php
// app/Http/Controllers/Faculty/NcpController.php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\StoreNcpRequest;
use App\Http\Requests\Faculty\UpdateNcpRequest;
use App\Models\NursingCarePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NcpController extends Controller
{
    public function index(Request $request)
    {
        $plans = NursingCarePlan::owned()
            ->when($request->filled('q'), function ($q) use ($request) {
                $needle = '%'.$request->string('q').'%';
                $q->where(function ($qq) use ($needle) {
                    $qq->where('patient_name', 'like', $needle)
                       ->orWhere('dx_primary', 'like', $needle)
                       ->orWhere('goal_short', 'like', $needle)
                       ->orWhere('interventions', 'like', $needle)
                       ->orWhere('evaluation', 'like', $needle)
                       ->orWhere('remarks', 'like', $needle);
                });
            })
            ->orderByDesc('noted_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('faculty.chartings.ncp_index', compact('plans'));
    }

    public function create()
    {
        return view('faculty.chartings.ncp_create');
    }

    public function store(StoreNcpRequest $request)
    {
        $data = $request->validated();
        $data['faculty_id'] = Auth::guard('faculty')->id();

        $plan = NursingCarePlan::create($data);

        return redirect()
            ->route('faculty.chartings.ncp.show', $plan)
            ->with('status', 'NCP saved.');
    }

    public function show(NursingCarePlan $plan)
    {
        $this->ensureOwnership($plan);
        return view('faculty.chartings.ncp_show', compact('plan'));
    }

    public function edit(NursingCarePlan $plan)
    {
        $this->ensureOwnership($plan);
        return view('faculty.chartings.ncp_edit', compact('plan'));
    }

    public function update(UpdateNcpRequest $request, NursingCarePlan $plan)
    {
        $this->ensureOwnership($plan);
        $plan->update($request->validated());

        return redirect()
            ->route('faculty.chartings.ncp.show', $plan)
            ->with('status', 'NCP updated.');
    }

    public function destroy(NursingCarePlan $plan)
    {
        $this->ensureOwnership($plan);
        $plan->delete();

        return redirect()
            ->route('faculty.chartings.ncp.index')
            ->with('status', 'NCP deleted.');
    }

    protected function ensureOwnership(NursingCarePlan $plan): void
    {
        abort_unless($plan->faculty_id === Auth::guard('faculty')->id(), 403);
    }
}
