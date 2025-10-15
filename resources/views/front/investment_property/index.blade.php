@extends('layouts.page', ['body_class' => 'investments'])

@section('meta_title', $page->title  .' - '. $building->name .' - '. $investment->floor->name .' - '. $property->name)
@section('seo_description', $page->meta_description)
@section('content')
    <main class="my-5">
        <!-- Breadcrumb -->
        <div class="container">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Strona główna</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/apartamenty">Apartamenty</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('front.developro.investment.building', ['buildingSlug' => $building->slug]) }}">{{$building->name}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route('front.developro.investment.floor', [$building->slug, $property->floor, Str::slug($property->floor->name)])}}">{{$property->floor->name}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{$property->name}}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- END -> Breadcrumb -->

        <!-- Apartment Card -->
        <section class="apartment">
            <div class="container">
                <div id="property-nav" class="d-flex gap-3 gap-sm-5 flex-sm-row justify-content-between align-items-center margin-wrapper-smallest margin-wrapper-smallest-bottom-sm">
                    @if($prev)
                    <a href="{{ route('front.developro.investment.property', [
                                                        $prev->building->slug,
                                                        Str::slug($prev->floor->name),
                                                        $prev,
                                                        Str::slug($prev->name),
                                                        floorLevel($prev->floor->number, true),
                                                        number2RoomsName($prev->rooms, true),
                                                        round(floatval($prev->area), 2).'-m2'
                                                    ]) }}" class="btn btn-main-inverted">
                        <img class="pe-4 disable" src="{{ asset('images/arrow_left.svg') }}" height="15" alt="strzałka"/>
                        poprzednie
                    </a>
                    @endif
                    <a href="{{route('front.developro.investment.floor', [$property->building->slug, $property->floor, Str::slug($property->floor->name)])}}" class="text-uppercase text-black link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-primary fs-5"
                    >plan piętra</a>
                    @if($next)
                    <a href="{{ route('front.developro.investment.property', [
                                                        $next->building->slug,
                                                        Str::slug($next->floor->name),
                                                        $next,
                                                        Str::slug($next->name),
                                                        floorLevel($next->floor->number, true),
                                                        number2RoomsName($next->rooms, true),
                                                        round(floatval($next->area), 2).'-m2'
                                                    ]) }}" class="btn btn-main">
                        następne
                        <img class="ps-4" src="{{ asset('images/arrow-right.svg') }}" height="15.644" alt="strzałka" height="15.644" alt="strzałka"/>
                    </a>
                    @endif
                </div>
                <div class="container order-2 margin-wrapper-xxs margin-wrapper-sm-xl">
                    <div class="row about">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-5 mb-5 mb-sm-0">
                                    <article class="about-common d-flex flex-column justify-content-between h-100" data-aos="fade-up">
                                        <div class="d-flex flex-column gap-5">
                                            <div>
                                                <!-- ICON on to of a header title  -->
                                                <img
                                                        src="{{ asset('images/logo-small.svg') }}"
                                                        alt="Apartamenty Nowe Miasto logo"
                                                        width="54"
                                                        height="33"
                                                        class="img-fluid about--icon"
                                                        loading="lazy"
                                                        decoding="async"
                                                />
                                                <!-- TITLE -->
                                                <h2 class="header-1 px-0 mx-0">
                                                    {{$property->name_list}}
                                                    <span> {{$property->number}} </span>
                                                </h2>
                                                <!-- SUBTITLE -->
                                                {!! roomPageStatusBadge($property->status) !!}
                                            </div>
                                            <div class="row mb-3">
                                                @if($property->price_brutto && $property->status == 1)
                                                    <div class="col-12 col-sm-6 @if($property->highlighted) promotion-price order-2 text-center text-sm-end @endif">
                                                        <span class="fs-2 d-block"><strong>@money($property->price_brutto)</strong></span>
                                                        <span class="d-block">@money(($property->price_brutto / $property->area)) / m<sup>2</sup></span>
                                                    </div>
                                                @endif
                                                @if($property->promotion_price && $property->price_brutto && $property->highlighted && $property->status == 1)
                                                    <div class="col-12 col-sm-6 @if($property->highlighted) order-1 @endif">
                                                        <span class="fs-2 d-block" style="color:#d92727"><strong>@money($property->promotion_price)</strong></span>
                                                        <span class="d-block" style="color:#d92727">@money(($property->promotion_price / $property->area)) / m<sup>2</sup></span>
                                                    </div>
                                                @endif
                                                @if($property->priceHistory->count() == 0 && $property->status == 1)
                                                    <div class="col-12 mt-2">
                                                        <p>Cena nie zmieniła się od 11.09.2025 r.</p>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($property->priceHistory->count() > 0 && $property->status == 1)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <table class="table m-0">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Stara cena</th>
                                                                <th>Nowa cena</th>
                                                                <th>Data zmiany</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($property->priceHistory as $history)
                                                                <tr>
                                                                    <td>
                                                                        @if($history->price_before_gross < $history->price_gross)
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                                                                 fill="none" stroke="#ae1515" stroke-width="2"
                                                                                 stroke-linecap="round" stroke-linejoin="round"
                                                                                 class="feather feather-arrow-up-right">
                                                                                <line x1="7" y1="17" x2="17" y2="7" />
                                                                                <polyline points="7 7 17 7 17 17" />
                                                                            </svg>
                                                                        @else
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                                                 viewBox="0 0 24 24" stroke="#00a304" stroke-width="2"
                                                                                 stroke-linecap="round" stroke-linejoin="round"
                                                                                 class="feather feather-arrow-down-right">
                                                                                <line x1="7" y1="7" x2="17" y2="17"></line>
                                                                                <polyline points="17 7 17 17 7 17"></polyline>
                                                                            </svg>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{ $history->price_before_gross }} zł
                                                                        <small>{{ $history->price_before_per_mkw }} zł/m<sup>2</sup></small>
                                                                    </td>
                                                                    <td>
                                                                        {{ $history->price_gross }} zł
                                                                        <small>{{ $history->price_per_mkw }} zł/m<sup>2</sup></small>
                                                                    </td>
                                                                    <td>
                                                                        {{ $history->formatted_date_modified }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="d-flex flex-column gap-2 w-75">
                                                @if($property->rooms > 0)
                                                <div class="d-flex justify-content-between">
                                                    <span>pokoje:</span>
                                                    <span> {{$property->rooms}}</span>
                                                </div>
                                                @endif
                                                @if($property->area > 0)
                                                <div class="d-flex justify-content-between">
                                                    <span>metraż:</span>
                                                    <span> {{$property->area}} m<sup>2</sup></span>
                                                </div>
                                                @endif
                                                @if($property->balcony_area)
                                                <div class="d-flex justify-content-between">
                                                    <span>balkon:</span>
                                                    <span> {{$property->balcony_area}} m<sup>2</sup></span>
                                                </div>
                                                @endif
                                                @if($property->garden_area)
                                                    <div class="d-flex justify-content-between">
                                                        <span>ogródek:</span>
                                                        <span> {{$property->garden_area}} m<sup>2</sup></span>
                                                    </div>
                                                @endif
                                                @if($property->loggia_area)
                                                    <div class="d-flex justify-content-between">
                                                        <span>loggia:</span>
                                                        <span> {{$property->loggia_area}} m<sup>2</sup></span>
                                                    </div>
                                                @endif
                                                @if($property->terrace_area)
                                                    <div class="d-flex justify-content-between">
                                                        <span>taras:</span>
                                                        <span> {{$property->terrace_area}} m<sup>2</sup></span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-5 align-items-sm-center flex-column flex-sm-row">
                                            <a href="#contact" class="btn btn-main w-fit-content col-6 order-1">
                                                ZAPYTAJ O MIESZKANIE
                                                <img class="ps-4" src="{{ asset('images/arrow-right.svg') }}" height="15.644" alt="strzałka" height="15"/>
                                            </a>
                                        </div>
                                        <div class="row mt-4 align-items-sm-center flex-column flex-sm-row">
                                            @if($property->file_pdf)
                                                <a class="col order-0 order-lg-3 pb-4 pb-lg-0 text-black link-offset-2 link-underline link-underline-opacity-0 ms-auto link-underline-opacity-100-hover" href="{{ asset('/investment/property/pdf/'.$property->file_pdf) }}" target="_blank">POBIERZ KARTĘ .PDF</a>
                                            @endif
                                            @if($property->file_3d)
                                                <a class="col order-0 order-lg-3 pb-4 pb-lg-0 text-black link-offset-2 link-underline link-underline-opacity-0 ms-auto link-underline-opacity-100-hover" href="{{ asset('/investment/property/pdf/'.$property->file_3d) }}" target="_blank">POBIERZ KARTĘ 3D</a>
                                            @endif
                                            @if($brochureLink)
                                                    <a class="col order-0 order-lg-3 pb-4 pb-lg-0 text-black link-offset-2 link-underline link-underline-opacity-0 ms-auto link-underline-opacity-100-hover" href="{{ $brochureLink }}" target="_blank">PROSPEKT INFORMACYJNY</a>
                                            @endif
                                        </div>
                                    </article>
                                </div>
                                <div class="col-md-6 offset-md-1 position-relative about-common--img-box pt-0 pt-sm-5 pt-xl-0">
                                    @if ($property->file)
                                        <a href="{{ asset('/investment/property/' . $property->file) }}" class="swipebox">
                                            <picture>
                                                <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/' . $property->file_webp) }}">
                                                <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/' . $property->file) }}">
                                                <img src="{{ asset('/investment/property/thumbs/' . $property->file) }}" alt="{{ $property->name }}" class="w-100">
                                            </picture>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END -> Apartment Card -->

        <!-- CONTACT FORM COMMON -->
        <section class="margin-wrapper-xs" id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <img
                                src="{{ asset('images/fence-horizontal.svg') }}"
                                width="169"
                                height="72"
                                alt=""
                                class="apartments--fence-horizontal"
                                loading="lazy"
                                decoding="async"
                                data-aos="fade-up"
                                data-aos-offset="120"
                                data-aos-delay="100"
                        />
                        <!-- ICON on to of a header title  -->
                        <img
                                src="{{ asset('images/logo-small.svg') }}"
                                alt="Apartamenty Nowe Miasto logo"
                                width="54"
                                height="33"
                                class="img-fluid about--icon"
                                loading="lazy"
                                decoding="async"
                                data-aos="fade-up"
                                data-aos-offset="120"
                                data-aos-delay="100"
                        />
                        <!-- HEADER -->
                        <h1
                                class="header-1 px-0 mx-0"
                                data-aos="fade-left"
                                data-aos-delay="200"
                                data-aos-offset="120"
                        >
                            Kontakt
                        </h1>

                        <!-- SUBTITLE -->
                        <span
                                class="subtitle-1"
                                data-aos="fade-left"
                                data-aos-delay="500"
                                data-aos-offset="120"
                        >Apartamenty Nowe miasto</span
                        >

                        <!-- PARAGRAPH -->
                        <div
                                class="row pt-4"
                                data-aos="fade-left"
                                data-aos-delay="400"
                                data-aos-offset="120"
                        >
                            <div class="col col-lg-6 offset-lg-3">
                                <p class="fw-medium">
                                    Jeżeli masz jakiekolwiek pytania dotyczące naszych nowych
                                    apartamentów, z przyjemnością pomożemy. Nasz zespół jest
                                    gotowy, aby dostarczyć wszelkie potrzebne informacje i
                                    rozwiać Twoje wątpliwości.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col col-lg-6 offset-lg-3">
                        @include('front.contact.form', ['page_name' => $property->name])
                    </div>
                </div>
            </div>
        </section>
        <!-- END -> CONTACT FORM  COMMON -->

        <!-- APARMENT TYPES -->

        <section class="margin-wrapper-xs position-relative">
            <div class="container">
                <div class="ap-types">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div>
                                <!-- ICON on to of a header title  -->
                                <img
                                        src="{{ asset('images/logo-small.svg') }}"
                                        alt="Apartamenty Nowe Miasto logo"
                                        width="54"
                                        height="33"
                                        class="img-fluid about--icon"
                                        loading="lazy"
                                        decoding="async"
                                />
                                <!-- HEADER -->
                                <h3 class="text-uppercase header-1 fs-xl-3 px-0 mx-0">
                                    SPRAWDŹ INNE <br />
                                    TYPY MIESZKAŃ
                                </h3>
                                <!-- SUBTITLE -->
                                <span class="text-uppercase subtitle-1"
                                >APARTAMENTY NOWE MIASTO</span
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row gy-3 gx-lg-3 gx-xl-4">
                        <div
                                class="col-6 col-md-6 col-lg-3"
                                data-aos="fade-right"
                                data-aos-delay="200"
                                data-aos-offset="120"
                        >
                            <a href="/apartamenty?s_pokoje=1" class="ap-card-link">
                                <div class="ap-card" data-order="1">
                                    <div class="box">
                                        <div class="type">1-pokojowe</div>
                                        <div class="squares">29 m<sup>2</sup></div>
                                    </div>
                                    <div class="btn-container">
                                        <button class="btn btn-main btn-main-white" type="button">
                                            <span class="pe-0 pe-sm-4"> sprawdź </span>
                                            <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="15.644"
                                                    height="15.644"
                                                    viewBox="0 0 15.644 15.644"
                                            >
                                                <path
                                                        id="arrow_downward_FILL0_wght100_GRAD0_opsz48"
                                                        d="M7.5,0V14.418L.467,7.355,0,7.822l7.822,7.822,7.822-7.822-.467-.467L8.143,14.418V0Z"
                                                        transform="translate(0 15.644) rotate(-90)"
                                                        fill="#fff"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div
                                class="col-6 col-md-6 col-lg-3"
                                data-aos="fade-right"
                                data-aos-delay="400"
                                data-aos-offset="120"
                        >
                            <a href="/apartamenty?s_pokoje=2" class="ap-card-link">
                                <div class="ap-card" data-order="2">
                                    <div class="box">
                                        <div class="type">2-pokojowe</div>
                                        <div class="squares">32-47 m<sup>2</sup></div>
                                    </div>
                                    <div class="btn-container">
                                        <button class="btn btn-main btn-main-white" type="button">
                                            <span class="pe-0 pe-sm-4"> sprawdź </span>
                                            <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="15.644"
                                                    height="15.644"
                                                    viewBox="0 0 15.644 15.644"
                                            >
                                                <path
                                                        id="arrow_downward_FILL0_wght100_GRAD0_opsz48"
                                                        d="M7.5,0V14.418L.467,7.355,0,7.822l7.822,7.822,7.822-7.822-.467-.467L8.143,14.418V0Z"
                                                        transform="translate(0 15.644) rotate(-90)"
                                                        fill="#fff"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div
                                class="col-6 col-md-6 col-lg-3"
                                data-aos="fade-right"
                                data-aos-delay="600"
                                data-aos-offset="120"
                        >
                            <a href="/apartamenty?s_pokoje=3" class="ap-card-link">
                                <div class="ap-card" data-order="3">
                                    <div class="box">
                                        <div class="type">3-pokojowe</div>
                                        <div class="squares">50-68 m<sup>2</sup></div>
                                    </div>
                                    <div class="btn-container">
                                        <button class="btn btn-main btn-main-white" type="button">
                                            <span class="pe-0 pe-sm-4"> sprawdź </span>
                                            <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="15.644"
                                                    height="15.644"
                                                    viewBox="0 0 15.644 15.644"
                                            >
                                                <path
                                                        id="arrow_downward_FILL0_wght100_GRAD0_opsz48"
                                                        d="M7.5,0V14.418L.467,7.355,0,7.822l7.822,7.822,7.822-7.822-.467-.467L8.143,14.418V0Z"
                                                        transform="translate(0 15.644) rotate(-90)"
                                                        fill="#fff"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div
                                class="col-6 col-md-6 col-lg-3"
                                data-aos="fade-right"
                                data-aos-delay="800"
                                data-aos-offset="120"
                        >
                            <a href="/apartamenty?s_pokoje=4" class="ap-card-link">
                                <div class="ap-card" data-order="4">
                                    <div class="box">
                                        <div class="type">4-pokojowe</div>
                                        <div class="squares">61-78 m<sup>2</sup></div>
                                    </div>
                                    <div class="btn-container">
                                        <button class="btn btn-main btn-main-white" type="button">
                                            <span class="pe-0 pe-sm-4"> sprawdź </span>
                                            <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="15.644"
                                                    height="15.644"
                                                    viewBox="0 0 15.644 15.644"
                                            >
                                                <path
                                                        id="arrow_downward_FILL0_wght100_GRAD0_opsz48"
                                                        d="M7.5,0V14.418L.467,7.355,0,7.822l7.822,7.822,7.822-7.822-.467-.467L8.143,14.418V0Z"
                                                        transform="translate(0 15.644) rotate(-90)"
                                                        fill="#fff"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row text-center mt-5">
                        <div class="col-12 col-sm-6 offset-sm-3">
                            <a class="cta-link" href="/apartamenty">
                                zobacz wszystkie
                                <img
                                        class="ps-4"
                                        src="{{ asset('images/arrow-right.svg') }}"
                                        height="15.644"
                                        alt="strzałka"
                                />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="anim-bounce-box-2">
                <div class="bounce"></div>
            </div>
        </section>
        <!-- END -> APARMENT TYPES -->
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('/js/validation.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/pl.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".validateForm").validationEngine({
                validateNonVisibleFields: true,
                updatePromptsPosition: true,
                promptPosition: "topRight:-137px"
            });
        });
        @if (session('success') || session('warning'))
        $(window).load(function() {
            const aboveHeight = $('header').outerHeight();
            $('html, body').stop().animate({
                scrollTop: $('.alert').offset().top - aboveHeight
            }, 1500, 'easeInOutExpo');
        });
        @endif
    </script>
@endpush
