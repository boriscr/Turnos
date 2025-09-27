<x-app-layout>
    <div class="content-wrapper">
        <h1>{{ __('medical.titles.details') }}</h1>
        <div class="section-container full-center">
            <div class="card">
                <h2>{{ __('medical.titles.personal_data') }}</h2>
                <x-field-with-icon icon="person" :label="__('contact.name_and_surname')" :value="$user->name . ' ' . $user->surname" />
                <x-field-with-icon icon="credit-card" :label="__('contact.idNumber')" :value="$user->idNumber" />
                <x-field-with-icon icon="gift" :label="__('contact.birthdate')" :value="\Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') .
                    ' (' .
                    \Carbon\Carbon::parse($user->birthdate)->age .
                    ' años)'" />
                <x-field-with-icon icon="gender-ambiguous" :label="__('contact.gender')" :value="$user->gender" />
            </div>

            <div class="card">
                <h2>{{ __('contact.address') }}</h2>
                <x-field-with-icon icon="globe" :label="__('contact.country')" :value="$user->country" />
                <x-field-with-icon icon="geo-alt" :label="__('contact.province')" :value="$user->province" />
                <x-field-with-icon icon="building" :label="__('contact.city')" :value="$user->city" />
                <x-field-with-icon icon="house-door" :label="__('contact.address')" :value="$user->address" />
            </div>

            <div class="card">
                <h2>{{ __('medical.titles.contact_details') }}</h2>
                <x-field-with-icon icon="telephone" :label="__('contact.phone')" :value="$user->phone" />
                <x-field-with-icon icon="envelope" :label="__('contact.email')" :value="$user->email" />
            </div>

            <div class="card">
                <h2>{{ __('medical.titles.reservations') }}</h2>
                <x-field-with-icon icon="x" :label="__('medical.unassisted_reservations')" :value="$user->faults === 0 ? __('medical.none') : $user->faults" />
            </div>

            <div class="card">
                <h2>{{ __('medical.titles.creation') }}</h2>
                <x-field-with-icon icon="person-badge" :label="__('medical.role')" :value="$user->translated_role" />
                <x-field-with-icon icon="circle-fill" :label="__('medical.status.title')" :value="$user->status ? __('medical.active') : __('medical.inactive')" />
                <x-field-with-icon icon="calendar-plus" :label="__('medical.creation_date')" :value="\Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i')" />

                @if (!empty($user->updatedById->name))
                    <x-field-with-icon icon="calendar-check" :label="__('medical.updated_by')" :value="$user->updatedById->name . ' ' . $user->updatedById->surname" :link="route('user.show', $user->updated_by)" />
                @else
                    <x-field-with-icon icon="calendar-check" :label="__('medical.updated_by')" :value="__('medical.no_data')" />
                @endif
                <x-field-with-icon icon="calendar-check" :label="__('medical.update_date')" :value="\Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i')" />
            </div>
        </div>

        <div class="options full-center">
            <a href="{{ route('user.edit', $user->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">{{ __('button.edit') }}</i></a>
            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-delete delete-btn"><i
                        class="bi bi-trash-fill">{{ __('button.delete') }}</i> </button>
            </form>
        </div>
    </div>
</x-app-layout>
