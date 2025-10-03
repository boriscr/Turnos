<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.doctor_index_title') }}</h1>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('medical.id') }}</th>
                        <th>{{ __('contact.name_and_surname') }}</th>
                        <th class="option-movil">{{ __('contact.idNumber') }}</th>
                        <th>{{ __('specialty.title') }}</th>
                        <th class="option-movil">{{ __('medical.role') }}</th>
                        <th class="option-movil">{{ __('medical.status.title') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->id }}</td>
                            <td>{{ $doctor->name . ' ' . $doctor->surname }}</td>
                            <td class="option-movil">{{ $doctor->idNumber }}</td>
                            <td class="{{ $doctor->specialty->name ?? 'no_data' }}">
                                {{ $doctor->specialty->name ?? __('medical.no_data') }}</td>
                            <td class="option-movil">{{ $doctor->role }}</td>
                            <td class="option-movil {{ $doctor->status ? 'existing' : 'no_data' }}">
                                {{ $doctor->status ? __('medical.active') : __('medical.inactive') }}
                            </td>
                            <td class="acciones full-center">
                                <a href="{{ route('doctor.show', $doctor->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">{{ __('button.view') }}</b></a>
                                <a href="{{ route('doctor.edit', $doctor->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b
                                        class="accionesMovil">{{ __('button.edit') }}</b></a>

                                <form class="delete-form" action="{{ route('doctor.destroy', $doctor->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete delete-btn">
                                        <i class="bi bi-trash-fill"></i><b
                                            class="accionesMovil">{{ __('button.delete') }}</b>
                                    </button>
                                </form>
                            </td>
                            <td class="accionesMovil">
                                <button type="button" class="accionesMovilBtn">
                                    <i class="bi bi-gear"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
