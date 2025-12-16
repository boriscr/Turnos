@props([
    'role' => false,
    'type' => false,
    'gender' => false,
    'id' => false,
    'name' => false,
    'surname' => false,
    'specialty_id' => false,
    'specialty_name' => false,
    'appointment_id' => false,
    'appointment_name' => false,
    'item_1' => false,
    'item_2' => false,
])



<div class="profile-container profile-containe-position">
    @if ($role != 'user')
        <img src="{{ $role === 'doctor'
            ? 'https://www.nicepng.com/png/detail/867-8678512_doctor-icon-physician.png'
            : 'https://www.shutterstock.com/image-vector/doctor-health-worker-icon-on-260nw-439462456.jpg' }}"
            alt="img-profile" class="profile-img">
    @elseif ($role === 'user')
        <img src="{{ $gender === 'Female'
            ? 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTzPRQ6LprnPzvvP-_vVO_nhSokwda8CMsnwQ&s'
            : ($gender === 'Male'
                ? 'https://cdn-icons-png.flaticon.com/512/56/56832.png'
                : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ5IZmAwxm1Smo0A8S0I8pXmaCLI-y976QbzA&s') }}"
            alt="img-profile" class="profile-img">
    @endif
    <div class="profile-id">
        @if ($role === 'doctor')
            <p class="profile-name">{{ $role === 'doctor' ?? __('medical.dr') }}
                <a href="{{ route('doctors.show', $id) }}">
                    {{ $name . ' ' . $surname }}
                    <i class="bi bi-eye"></i>
                </a>
            </p>
            <small>
                <a href="{{ route('specialties.show', $specialty_id) }}">
                    {{ $specialty_name }}
                    <i class="bi bi-eye"></i>
                </a> |
                <a href="{{ route('appointments.show', $appointment_id) }}">
                    {{ $appointment_name }}
                    <i class="bi bi-eye"></i>
                </a>
            </small>
        @else
            <p class="profile-name">
                @if (($role === 'patient' && $type === 'self') || ($role ==='user' && $type === 'third_party'))
                    <a href="{{ route('users.show', $id) }}">
                        {{ $name . ' ' . $surname }}
                        <i class="bi bi-eye"></i>
                    </a>
                @else
                    {{ $name . ' ' . $surname }}
                @endif
            </p>
            <small>{{ $item_1 }} |
                {{ $item_2 }}
            </small>
        @endif
    </div>
</div>
