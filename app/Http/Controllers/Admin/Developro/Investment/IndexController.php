<?php

namespace App\Http\Controllers\Admin\Developro\Investment;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvestmentFormRequest;
use App\Models\City;
use App\Models\Gallery;
use App\Models\Investment;
use App\Models\Property;
use App\Repositories\InvestmentRepository;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// CMS

class IndexController extends Controller
{
    private InvestmentRepository $repository;
    private InvestmentService $service;

    public function __construct(InvestmentRepository $repository, InvestmentService $service)
    {
//        $this->middleware('permission:investment-list|investment-create|investment-edit|investment-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:investment-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:investment-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:investment-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.developro.investment.index', ['list' => $this->repository->all()]);
    }

    public function create()
    {
        return view('admin.developro.investment.form', [
            'galleryList' => Gallery::pluck('name', 'id')->prepend('Brak', 0),
            'cardTitle' => 'Dodaj inwestycje',
            'backButton' => route('admin.developro.investment.index')
        ])->with('entry', Investment::make());
    }

    public function store(InvestmentFormRequest $request)
    {
        $investment = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->uploadThumb($request->name, $request->file('file'), $investment);
        }

        if ($request->hasFile('logo')) {
            $this->service->uploadLogo($request->name, $request->file('logo'), $investment);
        }

        if ($request->hasFile('header')) {
            $this->service->uploadHeader($request->name, $request->file('header'), $investment);
        }

        return redirect(route('admin.developro.investment.index'))->with('success', 'Inwestycja zapisana');
    }

    public function edit(int $id)
    {
        return view('admin.developro.investment.form', [
            'entry' => $this->repository->find($id),
            'galleryList' => Gallery::pluck('name', 'id')->prepend('Brak', 0),
            'cardTitle' => 'Edytuj inwestycję',
            'backButton' => route('admin.developro.investment.index')
        ]);
    }

    public function update(InvestmentFormRequest $request, int $id)
    {
        $investment = $this->repository->find($id);
        $this->repository->update($request->validated(), $investment);

        if ($request->hasFile('file')) {
            $this->service->uploadThumb($request->name, $request->file('file'), $investment, true);
        }

        if ($request->hasFile('logo')) {
            $this->service->uploadLogo($request->name, $request->file('logo'), $investment, true);
        }

        if ($request->hasFile('header')) {
            $this->service->uploadHeader($request->name, $request->file('header'), $investment, true);
        }

        if ($request->hasFile('file_brochure')) {
            $this->service->uploadBrochure($request->name, $request->file('file_brochure'), $investment, true);
        }

        return redirect(route('admin.developro.investment.index'))->with('success', 'Inwestycja zaktualizowana');
    }

    public function log(Investment $investment){
        return view('admin.developro.investment.log', ['investment' => $investment]);
    }
    public function events(Investment $investment){
        return view('admin.developro.investment.events', ['investment' => $investment]);
    }
    public function eventtable(Investment $investment, Request $request)
    {
        return $this->repository->getEventsAsTable($investment, $request);
    }
    public function datatable(Investment $investment, Request $request)
    {
        return $this->repository->getDataTable($investment, $request->input('minDate'), $request->input('maxDate'));
    }
    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json(['status' => 'deleted'], 201);
    }

//    public function show()
//    {
//
//        $inwestycje_powierzchnia = DB::table('inwestycje_powierzchnia')->get();
//
//        foreach ($inwestycje_powierzchnia as $key => $inwestycja){
//            $property = new Property();
//
//            $property->investment_id = $inwestycja->id_inwest;
//            $property->name = 'Dom '.$inwestycja->numer;
//            $property->name_list = 'Dom';
//            $property->number = $inwestycja->numer;
//            $property->area = $inwestycja->metry;
//            $property->rooms = $inwestycja->pokoje;
//            $property->status = $inwestycja->status;
//            $property->cords = $inwestycja->cords;
//            $property->html = $inwestycja->html;
//            $property->file_pdf = $inwestycja->pdf;
//            $property->number_order = $key + 1;
//
//            $property->save();
//        }
//
//    }
}
