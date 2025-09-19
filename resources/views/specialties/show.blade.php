<x-app-layout>
    @if (isset($specialty))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <p><b>{{ __('specialty.title') }}: </b>{{ $specialty->name }}</p>
                    <p><b>{{ __('specialty.description') }}:</b> {{ $specialty->description ?? __('medical.no_data') }}
                    </p>
                    <p><b>{{ __('medical.status.title') }}:</b>
                        {{ $specialty->status ? __('medical.active') : __('medical.inactive') }}</p>
                    <p><b>{{ __('medical.creation_date') }}:</b> {{ $specialty->created_at }}</p>
                    <p><b>{{ __('medical.update_date') }}:</b> {{ $specialty->updated_at }}</p>

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
                                <td>
                                    {{ $doctor->status ? __('medical.active') : __('medical.inactive') }}</td>
                                <td> <a href="{{ route('doctor.show', $doctor->id) }}" class="btn btn-view"><i
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
