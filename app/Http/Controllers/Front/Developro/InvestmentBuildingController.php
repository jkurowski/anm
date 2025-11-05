<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Repositories\InvestmentRepository;
use Illuminate\Http\Request;

use App\Models\Building;
use App\Models\Investment;

class InvestmentBuildingController extends Controller
{
    private $repository;
    private $pageId;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 6;
    }

    public function index($buildingSlug, Request $request)
    {
        $investment = $this->repository->find(1);
        $building = Building::whereSlug($buildingSlug)->first();

        $investment_room = $investment->load(array(
            'buildingRooms' => function($query) use ($building, $request)
            {
                $query->orderByRaw("FIELD(type_vox, 1, 8, 12, 11, 4)");
                $query->orderBy('number');

                $query->where('properties.building_id', $building->id);
                if ($request->input('s_pokoje')) {
                    $query->where('rooms', $request->input('s_pokoje'));
                }
                if ($request->input('status')) {
                    $query->where('status', $request->input('status'));
                }
                if ($request->input('s_metry')) {
                    $area_param = explode('-', $request->input('s_metry'));
                    $min = $area_param[0];
                    $max = $area_param[1];
                    $query->whereBetween('area', [$min, $max]);
                }
                if ($request->input('sort')) {
                    $order_param = explode(':', $request->input('sort'));
                    $column = $order_param[0];
                    $direction = $order_param[1];
                    $query->orderBy($column, $direction);
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
            'buildingFloors' => function($query) use ($building)
            {
                $query->where('building_id', $building->id);
            }
        ));

        $page = Page::where('id', $this->pageId)->first();
        return view('front.investment_building.index', [
            'investment' => $investment_room,
            'building' => $building,
            'page' => $page,
            'properties' => $investment->buildingRooms
        ]);
    }

    public function show($id)
    {
        //
    }
}
