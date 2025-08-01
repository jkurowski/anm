@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-home" aria-hidden="true"></i>Przeglądaj inwestycje</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="table-overflow">
                <table class="table mb-0" id="sortable" aria-describedby="Investment list">
                    <thead class="thead-default">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nazwa</th>
                            <th scope="col">Adres</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col">Typ</th>
                            <th scope="col">Data modyfikacji</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="content">
                    @foreach ($list as $index => $p)
                        <tr id="recordsArray_{{ $p->id }}">
                            <th class="position" scope="row">{{ $index+1 }}</th>
                            <td>
                                @if ($p->type == 1)
                                    <a href="{{route('admin.developro.investment.buildings.index', $p)}}">{{ $p->name }}</a>
                                @endif
                                @if ($p->type == 2)
                                    <a href="{{route('admin.developro.investment.floors.index', $p)}}">{{ $p->name }}</a>
                                @endif
                                @if ($p->type == 3)
                                    <a href="{{route('admin.developro.investment.houses.index', $p)}}">{{ $p->name }}</a>
                                @endif
                            </td>
                            <td>{{ $p->address }}</td>
                            <td><span class="badge inwest-list-status-{{ $p->status }}">{{ investmentStatus($p->status) }}</span></td>
                            <td>{{ investmentType($p->type) }}</td>
                            <td>{{ $p->updated_at }}</td>
                            <td class="option-120 text-end">
                                <div class="btn-group">
                                    @if($p->vox_url)
                                        <a href="{{route('admin.developro.vox.index', $p)}}"
                                           class="btn action-button me-1"
                                           data-bs-toggle="tooltip"
                                           data-placement="top"
                                           data-bs-title="Aktualizacja VOX">
                                            <i class="fe-download-cloud" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                    <a href="{{route('admin.developro.investment.log', $p)}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Pokaż aktywność"><i class="fe-activity"></i></a>

                                    <a
                                        href="{{route('admin.developro.investment.plan.index', $p)}}"
                                        class="btn action-button me-1"
                                        data-bs-toggle="tooltip"
                                        data-placement="top"
                                        data-bs-title="Plan inwestycji">
                                        <i class="fe-image" aria-hidden="true"></i>
                                    </a>
                                    @if ($p->type == 1)
                                        <a
                                            href="{{route('admin.developro.investment.buildings.index', $p)}}"
                                            class="btn action-button me-1"
                                            data-bs-toggle="tooltip" data-placement="top"
                                            data-bs-title="Lista budynków">
                                            <i class="fe-grid" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    @if ($p->type == 2)
                                        <a
                                            href="{{route('admin.developro.investment.floors.index', $p)}}"
                                            class="btn action-button me-1"
                                            data-bs-toggle="tooltip"
                                            data-placement="top"
                                            data-bs-title="Lista kondygnacji">
                                            <i class="fe-layers" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    @if ($p->type == 3)
                                        <a
                                            href="{{route('admin.developro.investment.houses.index', $p)}}"
                                            class="btn action-button me-1"
                                            data-bs-toggle="tooltip"
                                            data-placement="top"
                                            data-bs-title="Lista domów">
                                            <i class="fe-archive" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                    <a
                                        href="{{route('admin.developro.investment.edit', $p)}}"
                                        class="btn action-button me-1"
                                        data-bs-toggle="tooltip"
                                        data-placement="top"
                                        data-bs-title="Edytuj">
                                        <i class="fe-edit" aria-hidden="true"></i>
                                    </a>
                                    <form method="POST" action="{{route('admin.developro.investment.destroy', $p)}}" class="d-none">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń inwestycje" data-id="{{ $p->id }}"><i class="fe-trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group form-group-submit d-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{route('admin.developro.investment.create')}}" class="btn btn-primary">Dodaj inwestycję</a>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
