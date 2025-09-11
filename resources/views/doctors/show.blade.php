<x-app-layout>
    @if (isset($doctor))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.personal_data') }}</h2>
                    <p><b> {{ __('contact.name_and_surname') }}: </b>{{ $doctor->name . ' ' . $doctor->surname }}</p>
                    <p><b> {{ __('contact.idNumber') }}: </b>{{ $doctor->idNumber }}</p>
                    <p><b>{{ __('medical.profile') }}:</b>
                        @if ($doctor->user_id)
                            <a href="{{ route('user.show', $doctor->user_id) }}"> <i
                                    class="bi bi-eye">{{ __('button.view') }} </i>
                            </a>
                        @else
                            {{ __('medical.no_profile') }}
                        @endif
                    </p>
                    <p><b> {{ __('medical.license_number') }}:
                        </b>{{ $doctor->licenseNumber ?? __('medical.no_data') }}
                    </p>
                    <p><b> {{ __('specialty.title') }}: </b>{{ $doctor->specialty->name ?? __('medical.no_data') }}
                    </p>
                    <p><b> {{ __('contact.email') }}: </b>{{ $doctor->email }}</p>
                    <p><b> {{ __('contact.phone') }}: </b>{{ $doctor->phone }}</p>
                </div>
                <div class="card">
                    <h2>{{ __('medical.titles.creation') }}</h2>
                    <p><b> {{ __('medical.role') }}: </b>{{ $doctor->role }}</p>
                    <p><b> {{ __('medical.status.title') }}:
                        </b>
                        <span class="{{ $doctor->status ? 'btn-success' : 'inactive' }}">
                            {{ $doctor->status ? __('medical.active') : __('medical.inactive') }}
                        </span>
                    </p>
                    <p><b>{{ __('medical.created_by') }}:
                        </b>{{ $doctor->createdBy->name . ' ' . $doctor->createdBy->surname }}
                        <a href="{{ route('user.show', $doctor->create_by) }}"><i
                                class="bi bi-eye">{{ __('button.view') }} </i></a>
                    </p>
                    <p><b> {{ __('medical.creation_date') }}: </b>{{ $doctor->created_at }}</p>
                    <p><b>{{ __('medical.updated_by') }}:
                        </b>{{ $doctor->updatedBy->name . ' ' . $doctor->updatedBy->surname }}
                        <a href="{{ route('user.show', $doctor->update_by) }}"><i
                                class="bi bi-eye">{{ __('button.view') }} </i></a>
                    </p>
                    <p><b> {{ __('medical.update_date') }}: </b>{{ $doctor->updated_at }}</p>
                </div>
            </div>
            <div class="options full-center">
                <a href="{{ route('doctor.edit', $doctor->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">{{ __('button.edit') }}</i></a>
                <form action="{{ route('doctor.destroy', $doctor->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete delete-btn"><i
                            class="bi bi-trash-fill">{{ __('button.delete') }}</i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
