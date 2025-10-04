<x-app-layout>
    @if (isset($doctor))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.personal_data') }}</h2>
                    @if ($doctor->user_id)
                        <x-field-with-icon icon="person-check-fill" :label="__('medical.doctor')" :value="$doctor->name . ' ' . $doctor->surname" :link="route('user.show', $doctor->user_id)" />
                    @else
                        <x-field-with-icon icon="person-check-fill" :label="__('medical.doctor')" :value="$doctor->name . ' ' . $doctor->surname" />
                    @endif
                    <x-field-with-icon icon="credit-card" :label="__('contact.idNumber')" :value="$doctor->idNumber" />
                    <x-field-with-icon icon="credit-card" :label="__('medical.license_number')" :value="$doctor->licenseNumber ?? __('medical.no_data')" />
                    <x-field-with-icon icon="heart-pulse-fill" :label="__('specialty.title')" :value="$doctor->specialty->name ?? __('medical.no_data')" />
                    <x-field-with-icon icon="telephone" :label="__('contact.phone')" :value="$doctor->phone" />
                    <x-field-with-icon icon="envelope" :label="__('contact.email')" :value="$doctor->email" />
                </div>
                <div class="card">
                    <h2>{{ __('medical.titles.creation') }}</h2>
                    <x-field-with-icon icon="person-badge" :label="__('medical.role')" :value="$doctor->role" />
                    <x-field-with-icon icon="circle-fill" :label="__('medical.status.title')" :value="$doctor->status ? __('medical.active') : __('medical.inactive')" />
                    <x-field-with-icon icon="calendar-plus" :label="__('medical.created_by')" :value="$doctor->createdBy->name . ' ' . $doctor->createdBy->surname" :link="route('user.show', $doctor->created_by)" />
                    <x-field-with-icon icon="calendar-plus" :label="__('medical.creation_date')" :value="\Carbon\Carbon::parse($doctor->created_at)->format('d/m/Y H:i')" />
                    <x-field-with-icon icon="calendar-check" :label="__('medical.updated_by')" :value="$doctor->updatedBy->name . ' ' . $doctor->updatedBy->surname" :link="route('user.show', $doctor->updated_by)" />
                    <x-field-with-icon icon="calendar-check" :label="__('medical.update_date')" :value="\Carbon\Carbon::parse($doctor->updated_at)->format('d/m/Y H:i')" />
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
