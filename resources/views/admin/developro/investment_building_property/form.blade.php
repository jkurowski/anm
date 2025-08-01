@extends('admin.layout')
@section('content')
    @if(Route::is('admin.developro.investment.building.floor.properties.edit'))
        <form method="POST" action="{{route('admin.developro.investment.building.floor.properties.update', [$investment, $building, $floor, $entry])}}" enctype="multipart/form-data" class="mappa">
            {{method_field('PUT')}}
            @else
                <form method="POST" action="{{route('admin.developro.investment.building.floor.properties.store', [$investment, $building, $floor])}}" enctype="multipart/form-data" class="mappa">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card">
                            <div class="card-head container">
                                <div class="row">
                                    <div class="col-12 pl-0">
                                        <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.floors.index', $investment)}}">{{$investment->name}}</a><span class="d-inline-flex me-2 ms-2">-</span><a href="{{route('admin.developro.investment.building.floors.index', [$investment, $building])}}">{{$building->name}}</a><span class="d-inline-flex me-2 ms-2">-</span><a href="{{route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor])}}">{{$floor->name}}</a><span class="d-inline-flex me-2 ms-2">-</span>{{ $cardTitle }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body">
                                <div class="mappa-tool">
                                    <div class="mappa-workspace">
                                        <div id="overflow" style="overflow:auto;width:100%;">
                                            <canvas class="mappa-canvas"></canvas>
                                        </div>
                                        <div class="mappa-toolbars">
                                            <ul class="mappa-drawers list-unstyled mb-0">
                                                <li><input type="radio" name="tool" value="polygon" id="new" class="addPoint input_hidden"/><label for="new" data-toggle="tooltip" data-placement="top" class="actionBtn tip addPoint" title="Służy do dodawanie nowego elementu"><i class="fe-edit-2"></i> Dodaj punkt</label></li>
                                            </ul>
                                            <ul class="mappa-points list-unstyled mb-0">
                                                <li><input checked="checked" type="radio" name="tool" id="move" value="arrow" class="movePoint input_hidden"/><label for="move" class="actionBtn tip movePoint" data-toggle="tooltip" data-placement="top" title="Służy do przesuwania punktów"><i class="fe-move"></i> Przesuń / Zaznacz</label></li>
                                                <li><input type="radio" name="tool" value="delete" id="delete" class="deletePoint input_hidden"/><label for="delete" class="actionBtn tip deletePoint" data-toggle="tooltip" data-placement="top" title="Służy do usuwana punków"><i class="fe-trash-2"></i> Usuń punkt</label></li>
                                            </ul>
                                            <ul class="mappa-list list-unstyled mb-0"></ul>
                                            <ul class="mappa-points list-unstyled mb-0">
                                                <li><a href="#" id="toggleparam" class="actionBtn tip toggleParam" data-toggle="tooltip" data-placement="top" title="Służy do pokazywania/ukrywania parametrów"><i class="fe-repeat"></i> Pokaż / ukryj parametry</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body control-col12">
                                <div class="toggleRow w-100">
                                    <div class="row w-100 form-group">
                                        @include('form-elements.mappa', ['label' => 'Współrzędne punktów', 'name' => 'cords', 'value' => $entry->cords, 'rows' => 10, 'class' => 'mappa-html'])
                                    </div>
                                    <div class="row w-100 form-group mb-5">
                                        @include('form-elements.mappa', ['label' => 'Współrzędne punktów HTML', 'name' => 'html', 'value' => $entry->html, 'rows' => 10, 'class' => 'mappa-area'])
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-4 mb-4">
                                                @include('form-elements.html-select', ['label' => 'Widoczne', 'name' => 'active', 'selected' => $entry->active, 'select' => [
                                                       '1' => 'Tak',
                                                       '0' => 'Nie'
                                                       ]
                                                   ])
                                            </div>
                                            <div class="col-4 mb-4">
                                                @include('form-elements.html-select', ['label' => 'Typ powierzchni', 'name' => 'type', 'selected' => $entry->type, 'select' => [
                                                    '1' => 'Mieszkanie / Apartament',
                                                    '2' => 'Komórka lokatorska',
                                                    '3' => 'Miejsce parkingowe'
                                                    ]
                                                ])
                                            </div>
                                            <div class="col-4 mb-4">
                                                @include('form-elements.html-select', [
                                                     'label' => 'Lokal usługowy',
                                                     'name' => 'comercial_area',
                                                     'selected' => $entry->comercial_area,
                                                     'select' => [
                                                         '0' => 'Nie',
                                                         '1' => 'Tak'
                                                 ]])
                                            </div>
                                            <div class="col-6 mb-3">
                                                @include('form-elements.html-select', [
                                                    'label' => 'Status',
                                                    'name' => 'status',
                                                    'selected' => $entry->status,
                                                    'select' => [
                                                        '1' => 'Na sprzedaż',
                                                        '2' => 'Rezerwacja',
                                                        '3' => 'Sprzedane',
                                                        '4' => 'Wynajęte'
                                                ]])
                                            </div>

                                            <div class="col-6 mb-3">
                                                @include('form-elements.html-select-multiple', [
                                                    'label' => 'Atrybuty',
                                                    'name' => 'additional',
                                                    'selected' => json_decode($entry->additional),
                                                    'select' => [
                                                        '1' => 'Gotowe do odbioru',
                                                        '2' => 'Atrakcyjny wygląd',
                                                        '3' => 'Duży taras / balkon',
                                                        '4' => 'Ogródek zewnętrzny',
                                                        '5' => 'Dodatkowe WC',
                                                        '6' => 'Osobna garderoba'
                                                ]])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6 mb-4">
                                                @include('form-elements.input-text', ['label' => 'Nazwa', 'sublabel'=> 'Pełna nazwa', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                            </div>

                                            <div class="col-6 mb-4">
                                                @include('form-elements.input-text', ['label' => 'Nazwa na liście', 'sublabel'=> 'Mieszkanie, Apartament itp', 'name' => 'name_list', 'value' => $entry->name_list, 'required' => 1])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6">
                                                @include('form-elements.input-text', ['label' => 'Numer', 'sublabel'=> 'Tylko numer, bez nazwy', 'name' => 'number', 'value' => $entry->number, 'required' => 1])
                                            </div>
                                            <div class="col-6">
                                                @include('form-elements.input-text', ['label' => 'Kolejność na liście', 'sublabel'=> 'Tylko liczby', 'name' => 'number_order', 'value' => $entry->number_order, 'required' => 1])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                @include('form-elements.html-input-text', [
                                                    'label' => 'Numer ID VOX',
                                                    'name' => 'vox_id',
                                                    'value' => $entry->vox_id
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-4">
                                                @include('form-elements.html-select', ['label' => 'Pokoje', 'name' => 'rooms', 'selected' => $entry->rooms, 'select' => [
                                                  '1' => '1',
                                                  '2' => '2',
                                                  '3' => '3',
                                                  '4' => '4',
                                                  '5' => '5',
                                                  '6' => '6'
                                                  ]
                                              ])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.input-text', ['label' => 'Powierzchnia', 'name' => 'area', 'value' => $entry->area, 'required' => 1])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.html-select', [
                                                     'label' => 'Aneks / kuchnia',
                                                     'name' => 'kitchen type',
                                                     'selected' => $entry->kitchen_type,
                                                     'select' => [
                                                         '1' => 'Aneks',
                                                         '2' => 'Kuchnia'
                                                 ]])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row w-100 form-group">

                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Cena', 'sublabel'=> 'Tylko liczby', 'name' => 'price', 'value' => $entry->price])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Ogródek', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'garden_area', 'value' => $entry->garden_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Balkon', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'balcony_area', 'value' => $entry->balcony_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Balkon 2', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'balcony_area_2', 'value' => $entry->balcony_area_2])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Taras', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'terrace_area', 'value' => $entry->terrace_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Loggia', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'loggia_area', 'value' => $entry->loggia_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Spiżarnia', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'storeroom', 'value' => $entry->storeroom])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Termin oddania', 'name' => 'deadline', 'value' => $entry->deadline])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text-count', ['label' => 'Nagłówek strony', 'sublabel'=> 'Meta tag - title', 'name' => 'meta_title', 'value' => $entry->meta_title, 'maxlength' => 60])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text-count', ['label' => 'Opis strony', 'sublabel'=> 'Meta tag - description', 'name' => 'meta_description', 'value' => $entry->meta_description, 'maxlength' => 158])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file', [
                                        'label' => 'Plan mieszkania',
                                        'sublabel' => '(wymiary: '.config('images.property_plan.width').'px / '.config('images.property_plan.height').'px)',
                                        'name' => 'file',
                                        'file' => $entry->file,
                                        'file_preview' => config('images.property.preview_file_path')
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file-pdf', [
                                        'label' => 'Plan .pdf',
                                        'sublabel' =>
                                        'Plan do pobrania',
                                        'name' => 'file_pdf',
                                        'file' => $entry->file_pdf,
                                        'file_preview' => config('images.property.preview_pdf_path')
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file-pdf', [
                                        'label' => 'Plan 3d .pdf',
                                        'sublabel' =>
                                        'Plan do pobrania',
                                        'name' => 'file_3d',
                                        'file' => $entry->file_3d,
                                        'file_preview' => config('images.property.preview_pdf_path')
                                    ])
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="floor_id" value="{{ $floor->id }}">
                        <input type="hidden" name="building_id" value="{{ $building->id }}">
                        <input type="hidden" name="investment_id" value="{{ $investment->id }}">
                        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                    </div>
                </form>
                @endsection
@push('scripts')
<script src="{{ asset('/js/plan/underscore.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/plan/mappa-backbone.js') }}" charset="utf-8"></script>
<script type="text/javascript">
    const map = {
        "name":"imagemap",
        "areas":[{!! $entry->cords !!}]
    };
    $(document).ready(function() {
        const mapview = new MapView({el: '.mappa'}, map);
        @if($floor->file)
        mapview.loadImage('/investment/floor/{{$floor->file}}');
        @endif
    });
</script>
@endpush
