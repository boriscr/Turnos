<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>Usuarios creados</h1>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('medical.id') }}</th>
                        <th>{{ __('contact.name_and_surname') }}</th>
                        <th class="option-movil">{{ __('contact.idNumber') }}</th>
                        <th class="option-movil">{{ __('medical.role') }}</th>
                        <th>{{ __('medical.status') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name . ' ' . $user->surname }}</td>
                            <td class="option-movil">{{ $user->idNumber }}</td>
                            <td class="option-movil">
                                {{ $user->getRoleNames()->first() === 'user' ? __('medical.user') : ($user->getRoleNames()->first() === 'doctor' ? __('medical.doctor') : __('medical.admin')) }}
                                </p>
                            </td>
                            <td>{{ $user->status ? __('medical.active') : __('medical.inactive') }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('user.show', $user->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">{{ __('button.view') }}</b></a>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b
                                        class="accionesMovil">{{ __('button.edit') }}</b></a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
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

        </div>
    </div>
</x-app-layout>
