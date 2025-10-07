<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\{Drug, DrugMonograph, DrugDose, DrugInteraction};
use App\Services\RxNavClient;

class DrugGuideController extends Controller
{
    /** LIST */
    public function index(Request $req)
    {
        $q     = (string) $req->query('q', '');
        $class = (string) $req->query('class', '');
        $route = (string) $req->query('route', '');
        $age   = (string) $req->query('age', '');

        $query = Drug::query()
            ->with(['monograph:id,drug_id', 'doses:id,drug_id,population,route'])
            ->search($q)
            ->byClass($class ?: null);

        if ($route !== '') {
            $query->whereHas('doses', fn($w) => $w->where('route', $route));
        }
        if ($age !== '') {
            $query->whereHas('doses', fn($w) => $w->where('population', $age));
        }

        $paginator = $query->orderBy('generic_name')->paginate(12)->withQueryString();

        $items = $paginator->getCollection()->map(function (Drug $d) {
            return [
                'id'         => $d->id,
                'generic'    => $d->generic_name,
                'brands'     => $d->brand_names ?? [],
                'class'      => $d->atc_class,
                'high_alert' => false,
                'updated_at' => optional($d->updated_at)->toDateTimeString(),
                'warnings'   => [],
            ];
        });
        $paginator->setCollection($items);

        return view('faculty.ci-drug-guide-index', [
            'drugs' => $paginator,
            'filters' => [
                'q' => $q,
                'class' => $class,
                'route' => $route,
                'age' => $age,
                'classes' => ['Penicillin','Beta-blocker','ACE inhibitor','NSAID'],
                'routes'  => ['PO','IV','IM','Topical'],
                'ages'    => ['adult','pediatric','geriatric','neonate'],
            ],
            'canManage' => auth('faculty')->user()?->is_admin ?? false,
        ]);
    }

    /** SHOW */
    public function show(int $id)
    {
        $d = Drug::with(['monograph','doses','interactions'])->findOrFail($id);

        $drug = [
            'id'                       => $d->id,
            'generic'                  => $d->generic_name,
            'class'                    => $d->atc_class,
            'brands'                   => $d->brand_names ?? [],
            'pronunciation'            => null,
            'updated_at'               => optional($d->updated_at)->toDateTimeString(),
            'high_alert'               => false,
            'indications'              => $d->monograph->indications ?? '',
            'dosing'                   => $d->doses->map(fn($x)=>[
                                            'pop'=>$x->population,'route'=>$x->route,'text'=>$x->dose_text
                                        ])->values()->all(),
            'contraindications'        => $d->monograph->contraindications ?? '',
            'adverse_effects'          => $d->monograph->adverse_effects ?? '',
            'nursing_responsibilities' => $d->monograph->nursing_responsibilities ?? '',
            'patient_teaching'         => $d->monograph->patient_teaching ?? '',
            'monitoring'               => $d->monograph->monitoring_params ?? '',
            'interactions'             => $d->interactions->map(fn($ix)=>[
                                            'with'=>$ix->interacts_with,'severity'=>$ix->severity,'note'=>$ix->management
                                        ])->values()->all(),
            'iv_compat'                => [],
            'references'               => $d->monograph->references ?? [],
        ];

        return view('faculty.ci-drug-guide-show', compact('drug'));
    }

    /** CREATE FORM */
    public function create()
    {
        $this->authorize('create', Drug::class);

        return view('faculty.ci-drug-guide-form', [
            'mode' => 'create',
            'drug' => null,
            'classes' => ['Penicillin antibiotic','Cephalosporin antibiotic','NSAID','Beta-blocker','ACE inhibitor','PPI'],
            'routes'  => ['PO','IV','IM','Topical','SC','PR','SL','Inhalation'],
            'ages'    => ['adult','pediatric','geriatric','neonate'],
        ]);
    }

    /** STORE */
    public function store(Request $r)
    {
        $this->authorize('create', Drug::class);

        $data = $this->validated($r);

        DB::transaction(function () use ($data) {
            $drug = Drug::create([
                'generic_name'         => $data['generic_name'],
                'brand_names'          => $this->toArrayOrNull($data['brand_names'] ?? ''),
                'atc_class'            => $data['atc_class'] ?? null,
                'pregnancy_category'   => $data['pregnancy_category'] ?? null,
                'lactation_notes'      => $data['lactation_notes'] ?? null,
                'renal_adjust_notes'   => $data['renal_adjust_notes'] ?? null,
                'hepatic_adjust_notes' => $data['hepatic_adjust_notes'] ?? null,
                'is_core'              => (bool)($data['is_core'] ?? false),
                'last_reviewed_at'     => now(),
            ]);

            DrugMonograph::create([
                'drug_id'                  => $drug->id,
                'indications'              => $data['indications'] ?? null,
                'mechanism'                => $data['mechanism'] ?? null,
                'contraindications'        => $data['contraindications'] ?? null,
                'adverse_effects'          => $data['adverse_effects'] ?? null,
                'nursing_responsibilities' => $data['nursing_responsibilities'] ?? null,
                'patient_teaching'         => $data['patient_teaching'] ?? null,
                'monitoring_params'        => $data['monitoring_params'] ?? null,
                'references'               => $this->toArrayOrNull($data['references'] ?? '', ';'),
            ]);

            foreach (($data['doses'] ?? []) as $row) {
                DrugDose::create([
                    'drug_id'       => $drug->id,
                    'population'    => $row['population'],
                    'route'         => $row['route'] ?? null,
                    'form'          => $row['form'] ?? null,
                    'dose_text'     => $row['dose_text'],
                    'max_dose_text' => $row['max_dose_text'] ?? null,
                ]);
            }

            foreach (($data['interactions'] ?? []) as $ix) {
                DrugInteraction::create([
                    'drug_id'        => $drug->id,
                    'interacts_with' => $ix['with'],
                    'severity'       => $ix['severity'],
                    'mechanism'      => $ix['mechanism'] ?? null,
                    'management'     => $ix['management'] ?? null,
                ]);
            }
        });

        return redirect()->route('faculty.drug_guide.index')->with('ok', 'Drug created.');
    }

    /** EDIT FORM */
    public function edit(int $id)
    {
        $drug = Drug::with(['monograph','doses','interactions'])->findOrFail($id);
        $this->authorize('update', $drug);

        return view('faculty.ci-drug-guide-form', [
            'mode' => 'edit',
            'drug' => $drug,
            'classes' => ['Penicillin antibiotic','Cephalosporin antibiotic','NSAID','Beta-blocker','ACE inhibitor','PPI'],
            'routes'  => ['PO','IV','IM','Topical','SC','PR','SL','Inhalation'],
            'ages'    => ['adult','pediatric','geriatric','neonate'],
        ]);
    }

    /** UPDATE */
    public function update(Request $r, int $id)
    {
        $drug = Drug::with(['monograph','doses','interactions'])->findOrFail($id);
        $this->authorize('update', $drug);

        $data = $this->validated($r);

        DB::transaction(function () use ($drug, $data) {
            $drug->update([
                'generic_name'         => $data['generic_name'],
                'brand_names'          => $this->toArrayOrNull($data['brand_names'] ?? ''),
                'atc_class'            => $data['atc_class'] ?? null,
                'pregnancy_category'   => $data['pregnancy_category'] ?? null,
                'lactation_notes'      => $data['lactation_notes'] ?? null,
                'renal_adjust_notes'   => $data['renal_adjust_notes'] ?? null,
                'hepatic_adjust_notes' => $data['hepatic_adjust_notes'] ?? null,
                'is_core'              => (bool)($data['is_core'] ?? false),
                'last_reviewed_at'     => now(),
            ]);

            $drug->monograph()->updateOrCreate(
                ['drug_id' => $drug->id],
                [
                    'indications'              => $data['indications'] ?? null,
                    'mechanism'                => $data['mechanism'] ?? null,
                    'contraindications'        => $data['contraindications'] ?? null,
                    'adverse_effects'          => $data['adverse_effects'] ?? null,
                    'nursing_responsibilities' => $data['nursing_responsibilities'] ?? null,
                    'patient_teaching'         => $data['patient_teaching'] ?? null,
                    'monitoring_params'        => $data['monitoring_params'] ?? null,
                    'references'               => $this->toArrayOrNull($data['references'] ?? '', ';'),
                ]
            );

            $drug->doses()->delete();
            foreach (($data['doses'] ?? []) as $row) {
                $drug->doses()->create([
                    'population'    => $row['population'],
                    'route'         => $row['route'] ?? null,
                    'form'          => $row['form'] ?? null,
                    'dose_text'     => $row['dose_text'],
                    'max_dose_text' => $row['max_dose_text'] ?? null,
                ]);
            }

            $drug->interactions()->delete();
            foreach (($data['interactions'] ?? []) as $ix) {
                $drug->interactions()->create([
                    'interacts_with' => $ix['with'],
                    'severity'       => $ix['severity'],
                    'mechanism'      => $ix['mechanism'] ?? null,
                    'management'     => $ix['management'] ?? null,
                ]);
            }
        });

        return redirect()->route('faculty.drug_guide.show', $drug->id)->with('ok', 'Drug updated.');
    }

    /** ENRICH FROM RxNav (button on Edit page) */
    public function enrich(Request $req, int $id, RxNavClient $rx)
    {
        $drug = Drug::with(['interactions'])->findOrFail($id);
        $this->authorize('update', $drug);

        $overwriteBrands = (bool) $req->boolean('overwrite_brands');
        $overwriteIx     = (bool) $req->boolean('overwrite_interactions');

        try {
            $rxcui = $drug->rxcui ?: $rx->rxcuiFromName($drug->generic_name);
            if (!$rxcui) {
                return back()->with('err', "RxCUI not found for “{$drug->generic_name}”. Try adjusting the generic name.");
            }

            $brands = $rx->brandNames($rxcui);
            $pairs  = $rx->interactions($rxcui);

            DB::transaction(function () use ($drug, $rxcui, $brands, $pairs, $overwriteBrands, $overwriteIx) {
                // Brands: merge vs overwrite
                $mergedBrands = $overwriteBrands
                    ? collect($brands)
                    : collect($drug->brand_names ?? [])->merge($brands)->unique()->values();

                $drug->update([
                    'rxcui'            => $rxcui,
                    'brand_names'      => $mergedBrands->all() ?: null,
                    'last_reviewed_at' => now(),
                ]);

                // Interactions: overwrite or append; insert in chunks & trim long text
                if ($overwriteIx) {
                    $drug->interactions()->delete();
                }

                if (!empty($pairs)) {
                    $records = collect($pairs)->map(fn($p) => [
                        'drug_id'        => $drug->id,
                        'interacts_with' => $p['with'],
                        'severity'       => $p['severity'],
                        'mechanism'      => Str::limit((string)($p['mechanism'] ?? ''), 1000, ''),
                        'management'     => Str::limit((string)($p['management'] ?? ''), 2000, ''),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);

                    $records->chunk(50)->each(
                        fn($chunk) => DrugInteraction::insert($chunk->all())
                    );
                }
            });

            return redirect()
                ->route('faculty.drug_guide.edit', $drug->id)
                ->with('ok', "Enriched from RxNav • RxCUI {$rxcui} • ".count($brands)." brands • ".count($pairs)." interactions.");
        } catch (\Throwable $e) {
            return back()->with('err', 'Enrichment failed: '.$e->getMessage());
        }
    }

    /** Shared validation + helpers */
    private function validated(Request $r): array
    {
        return $r->validate([
            'generic_name'        => 'required|string|max:255',
            'brand_names'         => 'nullable|string',
            'atc_class'           => 'nullable|string|max:255',
            'pregnancy_category'  => 'nullable|string|max:10',
            'lactation_notes'     => 'nullable|string',
            'renal_adjust_notes'  => 'nullable|string',
            'hepatic_adjust_notes'=> 'nullable|string',
            'is_core'             => 'nullable|boolean',

            'indications'              => 'nullable|string',
            'mechanism'                => 'nullable|string',
            'contraindications'        => 'nullable|string',
            'adverse_effects'          => 'nullable|string',
            'nursing_responsibilities' => 'nullable|string',
            'patient_teaching'         => 'nullable|string',
            'monitoring_params'        => 'nullable|string',
            'references'               => 'nullable|string',

            'doses'                    => 'array',
            'doses.*.population'      => 'required|string|in:adult,pediatric,geriatric,neonate',
            'doses.*.route'           => 'nullable|string|max:50',
            'doses.*.form'            => 'nullable|string|max:100',
            'doses.*.dose_text'       => 'required|string',
            'doses.*.max_dose_text'   => 'nullable|string',

            'interactions'               => 'array',
            'interactions.*.with'        => 'required|string|max:255',
            'interactions.*.severity'    => 'required|string|in:minor,moderate,major',
            'interactions.*.mechanism'   => 'nullable|string',
            'interactions.*.management'  => 'nullable|string',
        ]);
    }

    private function toArrayOrNull(?string $s, string $sep = ',')
    {
        $s = trim((string)$s);
        if ($s === '') return null;
        $arr = collect(explode($sep, $s))
            ->map(fn($x) => trim($x))
            ->filter()
            ->values()
            ->all();
        return $arr ?: null;
    }
}
