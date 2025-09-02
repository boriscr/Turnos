<?php

namespace Database\Factories;

use App\Models\AvailableAppointment;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailableAppointmentFactory extends Factory
{
    protected $model = AvailableAppointment::class;

    public function definition()
    {
        return [
            'appointment_id' => Appointment::factory(),
            'doctor_id' => Doctor::factory(),
            'specialty_id' => Specialty::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'time' => $this->faker->time('H:i:s'),
            'available_spots' => 5,
            'reserved_spots' => 0,
        ];
    }
}
