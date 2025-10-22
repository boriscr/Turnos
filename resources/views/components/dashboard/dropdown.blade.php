<div class="dropdown">
    <button class="dropdown-btn">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <div class="dropdown-content">
        <x-dashboard.options href="#" icon="display-fill" label="General" />
        <x-dashboard.options href="{{ route('dashboard.design') }}" icon="palette-fill" label="Diseño" />
        <x-dashboard.options href="{{ route('dashboard.design') }}" icon="calendar3-fill" label="Turnos" />
        <x-dashboard.options href="{{ route('dashboard.design') }}" icon="shield-fill" label="Privacidad" />
    </div>
</div>
