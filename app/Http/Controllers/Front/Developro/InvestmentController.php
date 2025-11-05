<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

// CMS
use App\Models\Page;
use App\Repositories\InvestmentRepository;

class InvestmentController extends Controller
{
    private $repository;
    private $pageId;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 6;
    }

    public function index(Request $request)
    {
        $investment = $this->repository->find(1);

        $investment_room = $investment->load(array(
            'properties' => function ($query) use ($request) {

                // sortowanie najpierw po type
                $query->orderByRaw("FIELD(type_vox, 1, 8, 12, 11, 4)");
                $query->orderBy('number');

                if ($request->input('s_pokoje')) {
                    $query->where('rooms', $request->input('s_pokoje'));
                }
                if ($request->input('s_pietro')) {
                    $query->where('floor_id', $request->input('s_pietro'));
                }
                if ($request->input('s_metry')) {
                    $area_param = explode('-', $request->input('s_metry'));
                    $min = $area_param[0];
                    $max = $area_param[1];
                    $query->whereBetween('area', [$min, $max]);
                }
                if ($request->input('s_dodatkowe')) {
                    $amenities = explode(',', $request->input('s_dodatkowe'));
                    $query->where(function ($subQuery) use ($amenities) {
                        foreach ($amenities as $amenity) {
                            switch ($amenity) {
                                case '1':
                                    $subQuery->whereNotNull('balcony_area');
                                    break;
                                case '2':
                                    $subQuery->whereNotNull('garden_area');
                                    break;
                                case '3':
                                    $subQuery->whereNotNull('loggia_area');
                                    break;
                                case '4':
                                    $subQuery->whereNotNull('terrace_area');
                                    break;
                            }
                        }
                    });
                }
            },
            'properties.floor'
        ));

        $properties = $investment_room->properties;

        $page = Page::where('id', $this->pageId)->first();
        return view('front.developro.investment.index', [
            'investment' => $investment,
            'properties' => $properties,
            'uniqueRooms' => $this->repository->getUniqueRooms($investment_room->properties),
            'page' => $page
        ]);
    }

    public function search(Request $request)
    {
        $query = Property::query();

        // Apply filters based on request parameters
        if ($request->has('rooms') && !empty($request->input('rooms'))) {
            $query->where('rooms', $request->input('rooms'));
        }

        if ($request->has('area') && !empty($request->input('area'))) {
            $query->where('area', $request->input('area'));
        }

        if ($request->has('floor') && !empty($request->input('floor'))) {
            $query->whereHas('floor', function ($q) use ($request) {
                $q->where('id', $request->input('floor'));
            });
        }

        // Get the filtered properties
        $properties = $query->get();

        // Get distinct values for each filter based on filtered properties
        $areas = $properties->pluck('area')->unique()->sort()->values();
        $rooms = $properties->pluck('rooms')->unique()->sort()->values();
        $floors = $properties->pluck('floor.id', 'floor.number')->sort();

        return response()->json([
            'properties' => $properties,
            'filters' => [
                'areas' => $areas,
                'rooms' => $rooms,
                'floors' => $floors,
            ],
        ]);
    }
}
