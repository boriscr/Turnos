<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialty::create([
            'name' => 'Generalista',
            'description' => 'El médico generalista es el profesional capacitado para atender una amplia variedad de problemas de salud en pacientes de todas las edades. Su enfoque es integral, abarcando prevención, diagnóstico y tratamiento de enfermedades comunes, así como la derivación a especialistas cuando es necesario. Es el primer punto de contacto en el sistema de salud y juega un rol clave en el seguimiento continuo del paciente.',
            'status' => true,
        ]);

        Specialty::create([
            'name' => 'Odontología',
            'description' => 'La odontología se encarga del diagnóstico, tratamiento y prevención de enfermedades y condiciones que afectan los dientes, encías y estructuras bucales. Los odontólogos realizan procedimientos como limpiezas, extracciones, tratamientos de caries, ortodoncia y rehabilitación oral, contribuyendo a la salud general y calidad de vida del paciente.',
            'status' => true,
        ]);

        Specialty::create([
            'name' => 'Traumatología',
            'description' => 'La traumatología se especializa en el estudio, diagnóstico y tratamiento de lesiones del sistema musculoesquelético, incluyendo huesos, articulaciones, ligamentos y músculos. Los traumatólogos atienden fracturas, esguinces, luxaciones y patologías crónicas como la artrosis, y pueden realizar intervenciones quirúrgicas para recuperar la funcionalidad del paciente.',
            'status' => true,
        ]);

        Specialty::create([
            'name' => 'Pediatría',
            'description' => 'La pediatría se dedica al cuidado integral de la salud de niños y adolescentes, desde el nacimiento hasta la adolescencia. Los pediatras realizan controles de crecimiento, vacunación, diagnóstico de enfermedades infantiles y orientación a padres sobre el desarrollo físico y emocional de sus hijos.',
            'status' => true,
        ]);

        Specialty::create([
            'name' => 'Cardiología',
            'description' => 'La cardiología se enfoca en el diagnóstico y tratamiento de enfermedades del corazón y del sistema circulatorio. Los cardiólogos atienden afecciones como hipertensión, insuficiencia cardíaca, arritmias y enfermedades coronarias, utilizando estudios especializados como electrocardiogramas, ecocardiogramas y pruebas de esfuerzo.',
            'status' => true,
        ]);
    }
}
