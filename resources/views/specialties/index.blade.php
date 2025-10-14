<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.specialty_list') }}</h1>
            <x-form.search resource="specialties" :placeholder="__('button.search.placeholderName')"/>
            <div class="options full-center">
                <button class="btn-add">
                    <a href="{{ route('specialties.create') }}">{{ __('specialty.btn_name') }}</a>
                </button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="option-movil">{{ __('medical.id') }}</th>
                        <th>{{ __('specialty.title') }}</th>
                        <th>{{ __('medical.status.title') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($specialties as $specialty)
                        <tr>
                            <td class="option-movil">{{ $specialty->id }}</td>
                            <td>{{ $specialty->name }}</td>
                            <td class="{{ $specialty->status ? 'existing' : 'no_data' }}">
                                {{ $specialty->status ? __('medical.active') : __('medical.inactive') }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('specialties.show', $specialty->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">{{ __('button.view') }}</b></a>
                                <a href="{{ route('specialties.edit', $specialty->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b
                                        class="accionesMovil">{{ __('button.edit') }}</b></a>
                                <form action="{{ route('specialties.destroy', $specialty->id) }}" method="POST"
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
            <div class="pagination">
                {{ $specialties->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
