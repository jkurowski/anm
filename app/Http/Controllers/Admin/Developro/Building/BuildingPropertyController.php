<?php

namespace App\Http\Controllers\Admin\Developro\Building;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyFormRequest;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use App\Repositories\PropertyRepository;
use App\Services\PropertyService;
use Illuminate\Support\Facades\Session;

class BuildingPropertyController extends Controller
{
    private $repository;
    private $service;

    public function __construct(PropertyRepository $repository, PropertyService $service)
    {
//        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:box-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:box-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:box-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(Investment $investment, Building $building, Floor $floor)
    {
        $list = $investment->load(array(
            'floorRooms' => function($query) use ($floor)
            {
                $query->where('floor_id', $floor->id);
            }
        ));

        return view('admin.developro.investment_building_property.index', [
            'investment' => $investment,
            'building' => $building,
            'floor' => $floor,
            'list' => $list,
            'count_property_status' => $list->floorRooms->countBy('status')
        ]);
    }

    public function create(Investment $investment, Building $building, Floor $floor)
    {

        return view('admin.developro.investment_building_property.form', [
            'cardTitle' => 'Dodaj mieszkanie',
            'backButton' => route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor]),
            'floor' => $floor,
            'building' => $building,
            'investment' => $investment,
        ])->with('entry', Property::make());
    }

    public function store(PropertyFormRequest $request, Investment $investment, Building $building, Floor $floor)
    {
        $validatedData = $request->validated();
        if (isset($validatedData['additional']) && is_array($validatedData['additional'])) {
            $validatedData['additional'] = json_encode($validatedData['additional']);
        }

        // Merge the validated data with additional IDs
        $mergedData = array_merge($validatedData, [
            'investment_id' => $investment->id,
            'building_id' => $building->id,
            'floor_id' => $floor->id
        ]);

        // Create the property
        $property = $this->repository->create($mergedData);

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $property);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $property);
        }

        if ($request->hasFile('file_3d')) {
            $this->service->upload3d($request->name, $request->file('file_3d'), $property);
        }

        return redirect()->route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor])->with('success', 'Powierzchnia zapisana');
    }

    public function edit(Investment $investment, Building $building, Floor $floor, Property $property)
    {

        return view('admin.developro.investment_building_property.form', [
            'cardTitle' => 'Edytuj mieszkanie',
            'backButton' => route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor]),
            'floor' => $floor,
            'building' => $building,
            'investment' => $investment,
            'entry' => $property,
        ]);
    }

    public function update(PropertyFormRequest $request, Investment $investment, Building $building, Floor $floor, Property $property)
    {
        $this->repository->update($request->validated(), $property);

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $property, true);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $property, true);
        }

        if ($request->hasFile('file_3d')) {
            $this->service->upload3d($request->name, $request->file('file_3d'), $property, true);
        }

        return redirect()->route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor])->with('success', 'Powierzchnia zaktualizowana');
    }

    public function destroy(Investment $investment, Building $building, Floor $floor, Property $property)
    {
        $this->repository->delete($property->id);
        return response()->json('Deleted');
    }
}
