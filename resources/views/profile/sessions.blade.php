<x-app-layout>
    <div class="content-wrapper">
        <x-form.titles :value="__('medical.titles.sessions')" size="show" />
        <div class="main-table full-center">
            <div class="container-form full-center">
                <div class="container-form full-center">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('medical.session.device') }}</th>
                                <th>{{ __('medical.session.ip_address') }}</th>
                                <th>{{ __('medical.session.last_activity') }}</th>
                                <th>{{ __('medical.status.title') }}</th>
                                <th>{{ __('medical.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sesionesFormateadas as $sesion)
                                <tr>
                                    <td>
                                        @if (str_contains($sesion['navegador'], 'Windows') || str_contains($sesion['navegador'], 'OS X'))
                                            <span class="mr-2">💻</span>
                                        @else
                                            <span class="mr-2">📱</span>
                                        @endif
                                        {{ $sesion['navegador'] }}
                                    </td>
                                    <td>{{ $sesion['ip'] }}</td>
                                    <td>{{ $sesion['ultima_actividad'] }}</td>
                                    <td>
                                        @if ($sesion['actual'])
                                            <span
                                                class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                {{ __('medical.session.this_device') }}
                                            </span>
                                        @else
                                            <span
                                                class="text-gray-400 italic">{{ __('medical.session.active_session') }}</span>
                                        @endif
                                    </td>
                                    <td class="full-center">
                                        @if (!$sesion['actual'])
                                            <form action="{{ route('profile.sessions.destroy', $sesion['id']) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Estás seguro de que deseas cerrar esta sesión?')"
                                                style="display:inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-delete delete-btn-session">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span
                                                class="text-gray-400 italic">{{ __('medical.session.active_session') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <h5 colspan="7" class="text-center">{{ __('medical.no_data') }}</h5>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
