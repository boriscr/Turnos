<x-app-layout>
    <div class="content-wrapper">
        <h1>{{ __('medical.titles.details') }}</h1>
        <div class="section-container full-center">
            <div class="card">
                <h2>{{ __('medical.titles.personal_data') }}</h2>
                <p><b>{{ __('contact.name_and_surname') }}: </b>{{ $user->name . ' ' . $user->surname }}</p>
                <p><b>{{ __('contact.idNumber') }}: </b>{{ $user->idNumber }}</p>
                <p><b>{{ __('contact.birthdate') }}: </b>{{ \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') }}
                </p>
                <p><b>{{ __('contact.age') }}: </b>{{ \Carbon\Carbon::parse($user->birthdate)->age }}</p>
                <p><b>{{ __('contact.gender') }}: </b> {{ $user->gender }}</p>
            </div>

            <div class="card">
                <h2>{{ __('contact.address') }}</h2>
                <p><b>{{ __('contact.country') }}: </b> {{ $user->country }}</p>
                <p><b>{{ __('contact.province') }}: </b>{{ $user->province }}</p>
                <p><b>{{ __('contact.city') }}: </b>{{ $user->city }}</p>
                <p><b>{{ __('contact.address') }}: </b>{{ $user->address }}</p>
            </div>

            <div class="card">
                <h2>{{ __('medical.titles.contact_details') }}</h2>
                <p><b>{{ __('contact.email') }}: </b>{{ $user->email }}</p>
                <p><b>{{ __('contact.phone') }}: </b>{{ $user->phone }}</p>
            </div>

            <div class="card">
                <h2>{{ __('medical.titles.reservations') }}</h2>
                <p><b>{{ __('medical.unassisted_reservations') }}:
                    </b>{{ $user->faults === 0 ? __('medical.none') : $user->faults }}</p>
            </div>

            <div class="card">
                <h2>{{ __('medical.titles.creation') }}</h2>
                <p><b>{{ __('medical.role') }}:</b> {{ $user->getRoleNames()->first() }}</p>
                <p><b>{{ __('medical.status') }}:
                    </b>{{ $user->status ? __('medical.active') : __('medical.inactive') }}</p>
                <p><b> {{ __('medical.creation_date') }}:
                    </b>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</p>
                @if (!empty($user->updatedBy->name))
                    <p><b>{{ __('medical.updated_by') }}:
                        </b>{{ $user->updatedBy->name . ' ' . $user->updatedBy->surname }}
                        <a href="{{ route('user.show', $user->update_by) }}"><i
                                class="bi bi-eye">{{ __('button.view') }}
                            </i></a>
                    </p>
                @else
                    <p><b>{{ __('medical.updated_by') }}:
                        </b>{{ __('medical.no_data') }}
                    </p>
                @endif
                <p><b> {{ __('medical.update_date') }}:</b>
                    {{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') }}</p>
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
