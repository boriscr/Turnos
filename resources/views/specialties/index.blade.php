<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.specialty_list') }}</h1>
            <div class="options full-center">
                <button class="btn-add">
                    <a href="{{ route('specialty.create') }}">{{ __('specialty.btn_name') }}</a>
                </button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('medical.id') }}</th>
                        <th>{{ __('specialty.title') }}</th>
                        <th>{{ __('medical.status.title') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($specialties as $specialty)
                        <tr>
                            <td>{{ $specialty->id }}</td>
                            <td>{{ $specialty->name }}</td>
                            <td class="{{ $specialty->status ? 'existing' : 'no_data' }}">
                                {{ $specialty->status ? __('medical.active') : __('medical.inactive') }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('specialty.show', $specialty->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">{{ __('button.view') }}</b></a>
                                <a href="{{ route('specialty.edit', $specialty->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b
                                        class="accionesMovil">{{ __('button.edit') }}</b></a>
                                <form action="{{ route('specialty.destroy', $specialty->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-delete delete-btn"><i
                                            class="bi bi-trash-fill"></i><b
                                            class="accionesMovil">{{ __('button.delete') }}</b></button>
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
            <br>
            <div class="pagination full-center">
                {{ $specialties->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
