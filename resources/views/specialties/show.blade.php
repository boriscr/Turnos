<x-app-layout>
    @if (isset($specialty))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <x-field-with-icon icon="heart-pulse-fill" :label="__('specialty.title')" :value="$specialty->name" />
                    <x-field-with-icon icon="book-fill" :label="__('specialty.description')" :value="$specialty->description ?? __('medical.no_data')" />
                    <x-field-with-icon icon="circle-fill" :label="__('medical.status.title')" :value="$specialty->status ? __('medical.active') : __('medical.inactive')" />
                    <x-field-with-icon icon="calendar-plus" :label="__('medical.creation_date')" :value="$specialty->created_at" />
                    <x-field-with-icon icon="calendar-check" :label="__('medical.update_date')" :value="$specialty->updated_at" />
                    <div class="options full-center">
                        <a href="{{ route('specialty.edit', $specialty->id) }}" class="btn-edit"><i
                                class="bi bi-pencil-fill">{{ __('button.edit') }}</i></a>
                        <form action="{{ route('specialty.destroy', $specialty->id) }}" method="POST"
                            class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-delete delete-btn"><i
                                    class="bi bi-trash-fill">{{ __('button.delete') }}</i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="main-table full-center">
            <div class="container-form full-center">
                <h1>{{ __('medical.titles.doctor_show_title') }}</h1>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('medical.id') }}</th>
                            <th>{{ __('contact.name_and_surname') }}</th>
                            <th>{{ __('contact.idNumber') }}</th>
                            <th>{{ __('medical.license_number') }}</th>
                            <th>{{ __('medical.status.title') }}</th>
                            <th>{{ __('medical.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->id }}</td>
                                <td>{{ $doctor->name . ' ' . $doctor->surname }}</td>
                                <td>{{ $doctor->idNumber }}</td>
                                <td>{{ $doctor->licenseNumber }}</td>
                                <td class="{{ $doctor->status ? 'existing' : 'no_data' }}">
                                    {{ $doctor->status ? __('medical.active') : __('medical.inactive') }}</td>
                                <td> <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-view"><i
                                            class="bi bi-eye"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <br>
    <div class="pagination full-center">
        {{ $doctors->links() }}
    </div>
</x-app-layout>
