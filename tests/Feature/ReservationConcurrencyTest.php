<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AvailableAppointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function no_sobrevende_un_turno_con_dos_reservas_concurrentes()
    {
        /** @var \App\Models\User $user1 */
        $user1 = User::factory()->create();
        /** @var \App\Models\User $user2 */
        $user2 = User::factory()->create();

        /** @var \App\Models\AvailableAppointment $appointment */
        $appointment = AvailableAppointment::factory()->create([
            'available_spots' => 1,
            'reserved_spots'  => 0,
        ]);

        // Primera petición (usuario 1)
        $response1 = $this->actingAs($user1)
            ->post(route('reservations.store'), [
                'appointment_id' => $appointment->id,
                'specialty_id'   => $appointment->specialty_id,
            ]);

        // Refrescamos el modelo para leer los cambios hechos por la primera petición
        $appointment->refresh();

        // Segunda petición (usuario 2)
        $response2 = $this->actingAs($user2)
            ->post(route('reservations.store'), [
                'appointment_id' => $appointment->id,
                'specialty_id'   => $appointment->specialty_id,
            ]);

        // Aserciones
        $this->assertDatabaseCount('reservations', 1);

        $this->assertDatabaseHas('available_appointments', [
            'id'              => $appointment->id,
            'available_spots' => 0,
            'reserved_spots'  => 1,
        ]);
    }
}
