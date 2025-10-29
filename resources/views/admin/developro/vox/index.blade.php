@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        Ilosć: {{ count($parsedData) }}
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Inwestycja</th>
                <th>Typ</th>
                <th>Lokal</th>
                <th>Status</th>
                <th>Piętro</th>
                <th>Pokoje</th>
                <th>Powierzchnia (m²)</th>
                <th>Powierzchnia szukana</th>
                <th>Pytaj o cenę</th>
                <th>Cena netto (PLN)</th>
                <th>Cena brutto (PLN)</th>
                <th>Historia</th>
                <th>Promocja</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($parsedData as $item)
                @if($item['type']['id'] <> 144444)
                    <tr>
                        <td>{{ $item['id'] }}</td>
                        <td>{{ $investment->name }} - id: {{ $investment->id }}</td>
                        <td>{{ $item['type']['name'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{!! voxStatus($item['status']['id']) !!}</td>
                        <td>{{ $item['floor'] }}</td>
                        <td>{{ $item['rooms'] }}</td>
                        <td>{{ $item['area'] }}</td>
                        <td>{{ round($item['area']) }}</td>
                        <td>{{ ($item['ask_for_price'] ?: 'Brak') }}</td>
                        <td>{{ $item['price']['net'] }}</td>
                        <td>{{ $item['price']['gross'] }}</td>
                        <td><a href="{{ $item['price_change_history'] }}" target="_blank">Zobacz</a></td>
                        <td>{!! status((int)$item['promotion']['is_promoted']) !!}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" class="text-center">No data available</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
