@extends('layouts.page', ['body_class' => 'completed-page'])

@section('meta_title', $page->title)
@section('seo_title', $page->meta_title)
@section('seo_description', $page->meta_description)

@section('content')
    <main class="my-3 my-lg-5">
        <!-- Breadcrumb -->
        <div class="container">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Strona główna</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Apartamenty
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- END -> Breadcrumb -->

        <section
                class="section apartments margin-wrapper-smallest"
                data-aos="fade-down"
        >
            <!-- WITH FORM -->
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('/investment/plan/webp/194312_apartamenty-nowe-miasto.webp') }}" alt="{{$investment->name}}" id="invesmentplan" usemap="#invesmentplan">
                        <map name="invesmentplan">
                            @if($investment->buildings)
                                @foreach($investment->buildings as $building)
                                    <area
                                            shape="poly"
                                            href="{{ route('front.developro.investment.building', ['buildingSlug' => $building->slug]) }}"
                                            alt="{{$building->slug}}"
                                            data-item="{{$building->id}}" title="{{$building->name}}"
                                            data-roomnumber="{{$building->number}}"
                                            data-roomtype="{{$building->typ}}"
                                            data-roomstatus="{{$building->status}}"
                                            coords="@if($building->html) {{cords($building->html)}} @endif">
                                @endforeach
                            @endif
                        </map>
                    </div>
                </div>
                <div class="row">
                    <div class="px-0 col-md-12 col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
                        <form class="apartments-form" method="get" action="">
                            <select
                                    name="s_pokoje"
                                    id=""
                                    class="form-select apartments-form-select"
                                    aria-label="Pokoje"
                            >
                                <option value="">pokoje</option>
                                <option value="1" @if(request()->input('s_pokoje') == 1) selected @endif>1 pokój</option>
                                <option value="2" @if(request()->input('s_pokoje') == 2) selected @endif>2 pokoje</option>
                                <option value="3" @if(request()->input('s_pokoje') == 3) selected @endif>3 pokoje</option>
                                <option value="4" @if(request()->input('s_pokoje') == 4) selected @endif>4 pokoje</option>
                            </select>
                            <select
                                    name="s_metry"
                                    id=""
                                    class="form-select apartments-form-select"
                                    aria-label="Metraż"
                            >
                                <option value="">Metraż</option>
                                {!! area2Select($investment->area_range) !!}
                            </select>
                            <!-- To handle this dropdown change JS in main.js file -->
                            <div class="dropdown dropdown-checkbox">
                                <button class="form-select apartments-form-select text-start" type="button" id="multiSelectDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Udogodnienia
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="multiSelectDropdown">
                                    <li>
                                        <label><input type="checkbox" value="1"/> Balkon</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" value="2"/> Ogród</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" value="3"/> Loggia</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" value="4"/> Taras</label>
                                    </li>
                                </ul>
                            </div>
                            <input type="hidden" id="s_dodatkowe" name="s_dodatkowe" />

                            <button class="btn btn-casual d-flex align-items-center gap-2" type="submit">
                                Szukaj<img src="{{ asset('images/search.svg') }}" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- GRID ELEMENTS -->
            <div class="container">
                <div class="row">
                    <div
                            class="col-12 col-md-11 d-flex justify-content-end mt-5 mt-md-0"
                    >
                        <div class="change-flow">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="23"
                                    height="20"
                                    viewBox="0 0 23 20"
                                    id="grid-btn-1"
                                    class="active"
                            >
                                <g
                                        id="Group_771"
                                        data-name="Group 771"
                                        transform="translate(-1323 -1097)"
                                >
                                    <rect
                                            id="Rectangle_533"
                                            data-name="Rectangle 533"
                                            width="23"
                                            height="4"
                                            transform="translate(1323 1113)"
                                    />
                                    <rect
                                            id="Rectangle_534"
                                            data-name="Rectangle 534"
                                            width="23"
                                            height="4"
                                            transform="translate(1323 1105)"
                                    />
                                    <rect
                                            id="Rectangle_535"
                                            data-name="Rectangle 535"
                                            width="23"
                                            height="4"
                                            transform="translate(1323 1097)"
                                    />
                                </g>
                            </svg>
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 20 20"
                                    class="inactive"
                                    id="grid-btn-2"
                            >
                                <g
                                        id="Group_770"
                                        data-name="Group 770"
                                        transform="translate(-1291 -1097)"
                                >
                                    <rect
                                            id="Rectangle_536"
                                            data-name="Rectangle 536"
                                            width="4"
                                            height="4"
                                            transform="translate(1307 1105)"
                                    />
                                    <rect
                                            id="Rectangle_539"
                                            data-name="Rectangle 539"
                                            width="4"
                                            height="4"
                                            transform="translate(1307 1097)"
                                    />
                                    <rect
                                            id="Rectangle_542"
                                            data-name="Rectangle 542"
                                            width="4"
                                            height="4"
                                            transform="translate(1307 1113)"
                                    />
                                    <rect
                                            id="Rectangle_537"
                                            data-name="Rectangle 537"
                                            width="4"
                                            height="4"
                                            transform="translate(1299 1105)"
                                    />
                                    <rect
                                            id="Rectangle_541"
                                            data-name="Rectangle 541"
                                            width="4"
                                            height="4"
                                            transform="translate(1299 1097)"
                                    />
                                    <rect
                                            id="Rectangle_544"
                                            data-name="Rectangle 544"
                                            width="4"
                                            height="4"
                                            transform="translate(1299 1113)"
                                    />
                                    <rect
                                            id="Rectangle_538"
                                            data-name="Rectangle 538"
                                            width="4"
                                            height="4"
                                            transform="translate(1291 1105)"
                                    />
                                    <rect
                                            id="Rectangle_540"
                                            data-name="Rectangle 540"
                                            width="4"
                                            height="4"
                                            transform="translate(1291 1097)"
                                    />
                                    <rect
                                            id="Rectangle_543"
                                            data-name="Rectangle 543"
                                            width="4"
                                            height="4"
                                            transform="translate(1291 1113)"
                                    />
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-xl-10 offset-xl-1">
                        <!-- GRID ROWS -->

                        <ul class="row apartments-grid gy-4 gx-lg-0 mt-4 mt-sm-0 justify-content-lg-start" id="grid-rows">
                            <!-- DOSTĘPNE  -->
                            @foreach($properties as $p)
                            <li class="col-12 box" data-aos-offset="-120">

                                {!! roomStatusBadge($p->status) !!}

                                <div class="d-flex justify-content-center align-items-start gap-3 gap-lg-6">

                                    <span class="text-uppercase">{{ $p->name_list }} <br />{{ $p->number }}</span>

                                    <a href="{{ route('front.developro.investment.property', [
                                                        $p->building->slug,
                                                        Str::slug($p->floor->name),
                                                        $p,
                                                        Str::slug($p->name),
                                                        floorLevel($p->floor->number, true),
                                                        number2RoomsName($p->rooms, true),
                                                        round(floatval($p->area), 2).'-m2'
                                                    ]) }}">
                                        @if($p->file)
                                            <picture>
                                                <source type="image/webp" srcset="/investment/property/list/webp/{{$p->file_webp}}">
                                                <source type="image/jpeg" srcset="/investment/property/list/{{$p->file}}">
                                                <img src="/investment/property/list/{{$p->file}}" alt="{{$p->name}}" loading="lazy" decoding="async" width="83" height="58">
                                            </picture>
                                        @endif
                                    </a>
                                </div>

                                <div class="d-flex justify-content-around gap-3 gap-lg-6 text-capitalize">
                                    <span> pokoje {{ $p->rooms }}</span>
                                    <span class="line">&nbsp;</span>
                                    <span> metraż: {{ $p->area }}m<sup>2</sup></span>
                                </div>

                                <a href="{{ route('front.developro.investment.property', [
                                                        $p->building->slug,
                                                        Str::slug($p->floor->name),
                                                        $p,
                                                        Str::slug($p->name),
                                                        floorLevel($p->floor->number, true),
                                                        number2RoomsName($p->rooms, true),
                                                        round(floatval($p->area), 2).'-m2'
                                                    ]) }}" class="btn btn-main">zobacz <img class="ps-4" src="{{ asset('images/arrow-right.svg') }}" height="15.644" alt="strzałka w prawo"/></a>
                            </li>
                            @endforeach
                        </ul>
                        <!-- GRID TAILS -->
                        <ul class="row apartments-grid-tails gy-4 mt-4 mt-sm-0 invisible" id="grid-tails">
                            @foreach($properties as $p)
                            <li class="col-12 col-sm-6 col-lg-4">
                                <div class="box" data-aos="fade-right" data-aos-delay="100" data-aos-offset="-120">
                                    {!! roomStatusBadge($p->status) !!}
                                    <div class="d-flex justify-content-center align-items-start gap-3">
                                    <span class="text-uppercase">{{ $p->name_list }} <br />{{ $p->number }}</span>
                                        <a href="{{ route('front.developro.investment.property', [
                                                        $p->building->slug,
                                                        Str::slug($p->floor->name),
                                                        $p,
                                                        Str::slug($p->name),
                                                        floorLevel($p->floor->number, true),
                                                        number2RoomsName($p->rooms, true),
                                                        round(floatval($p->area), 2).'-m2'
                                                    ]) }}">
                                            @if($p->file)
                                                <picture>
                                                    <source type="image/webp" srcset="/investment/property/list/webp/{{$p->file_webp}}">
                                                    <source type="image/jpeg" srcset="/investment/property/list/{{$p->file}}">
                                                    <img src="/investment/property/list/{{$p->file}}" alt="{{$p->name}}" loading="lazy" decoding="async" width="83" height="58">
                                                </picture>
                                            @endif
                                        </a>
                                    </div>

                                    <div class="d-flex justify-content-center gap-3 text-capitalize">
                                        <span> pokoje {{ $p->rooms }} </span>
                                        <span class="line">&nbsp;</span>
                                        <span> metraż: {{ $p->area }}m<sup>2</sup> </span>
                                    </div>

                                    <a href="{{ route('front.developro.investment.property', [
                                                        $p->building->slug,
                                                        Str::slug($p->floor->name),
                                                        $p,
                                                        Str::slug($p->name),
                                                        floorLevel($p->floor->number, true),
                                                        number2RoomsName($p->rooms, true),
                                                        round(floatval($p->area), 2).'-m2'
                                                    ]) }}" class="btn btn-main"> zobacz <img class="ps-4" src="{{ asset('images/arrow-right.svg') }}" height="15.644" alt="Zobacz mieszkanie {{ $p->number }}" />
                                    </a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
@push('scripts')
    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/plan.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/css/developro.min.css') }}" rel="stylesheet">
@endpush
