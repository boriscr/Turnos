<x-app-layout>
    @if (isset($doctor))
        <div class="content-wrapper">
            <x-form.titles :value="__('medical.titles.details')" size="show" />
            <div class="status-box {{ $doctor->status ? 'status-active' : 'status-inactive' }}">
                <span> {{ $doctor->status ? __('medical.active') : __('medical.inactive') }}</span>
            </div>
            <div class="section-container full-center">
                <div class="card">
                    <x-form.titles :value="__('medical.titles.personal_data')" type="subtitle" />
                    @if ($doctor->user_id)
                        <x-field-with-icon icon="person-check-fill" :label="__('medical.doctor')" :value="$doctor->name . ' ' . $doctor->surname"
                            :link="route('users.show', $doctor->user_id)" />
                    @else
                        <x-field-with-icon icon="person-fill" :label="__('medical.doctor')" :value="$doctor->name . ' ' . $doctor->surname" />
                    @endif
                    <x-field-with-icon icon="person-vcard-fill" :label="__('contact.idNumber')" :value="$doctor->idNumber" />
                    <x-field-with-icon icon="person-vcard-fill" :label="__('medical.license_number')" :value="$doctor->licenseNumber ?? __('medical.no_data')" />
                    <x-field-with-icon icon="heart-pulse-fill" :label="__('specialty.title')" :value="$doctor->specialty->name ?? __('medical.no_data')" />
                    <x-field-with-icon icon="person-badge-fill" :label="__('medical.role')" :value="$doctor->role" />
                </div>
                <div class="card">
                    <x-form.titles :value="__('medical.titles.contact_details')" type="subtitle" />
                    <x-field-with-icon icon="telephone-fill" :label="__('contact.phone')" :value="$doctor->phone" />
                    <x-field-with-icon icon="envelope-fill" :label="__('contact.email')" :value="$doctor->email" />
                </div>
                @if ($doctor->created_by != null)
                    <div class="card">
                        <x-form.titles :value="__('medical.titles.creation')" type="subtitle" />
                        <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.created_by')" :value="$doctor->createdBy->name . ' ' . $doctor->createdBy->surname"
                            :link="route('users.show', $doctor->created_by)" />
                        <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.creation_date')" :value="\Carbon\Carbon::parse($doctor->created_at)->format('d/m/Y H:i')" />
                        <x-field-with-icon icon="calendar-range-fill" :label="__('medical.updated_by')" :value="$doctor->updatedBy->name . ' ' . $doctor->updatedBy->surname"
                            :link="route('users.show', $doctor->updated_by)" />
                        <x-field-with-icon icon="calendar-range-fill" :label="__('medical.update_date')" :value="\Carbon\Carbon::parse($doctor->updated_at)->format('d/m/Y H:i')" />
                    </div>
                @endif
            </div>
            <div class="options full-center">
                <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">{{ __('button.edit') }}</i></a>
                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="delete-form">
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
