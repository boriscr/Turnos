<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;

class HomeController extends Controller
{
    public function index()
    {
        $specialties = Specialty::select('name', 'description')->get();


        session()->flash('warning', [
            'title' => '¡Atención: Modo Demostración!',
            'text'  => 'Esta aplicación está <strong>en construcción</strong> y es solo una <strong>vista de demostración</strong>. Puede que <strong>no esté disponible en cualquier momento</strong>, y algunas funciones pueden no funcionar de la forma esperada.',
            'text2' => 'Los datos ingresados serán eliminados automáticamente en 30 días o antes. Las secciones marcadas como <span class="featureHome">FEATURE</span> no están disponibles.',
            'text3' => 'Agradecemos enormemente tu ayuda <strong>reportando los errores</strong> encontrados al desarrollador.',
            'icon'  => 'warning',
            'confirmButtonText' => 'Entendido y Acepto',
            'allowOutsideClick' => false,
        ]);

        return view('home', compact('specialties'));
    }
}
