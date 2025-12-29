<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Http\Requests\GenderUpdate;

class GenderController extends Controller
{
    public function edit()
    {
        try {
            $genders = Gender::select('name','status')->get();
            //Traducir los géneros
            $genders->transform(function ($gender) {
                $gender->translated_name = __('genders.' . $gender->name);
                return $gender;
            });
            return view('dashboard.gender', compact('genders'));
        } catch (\Exception $e) {
            return back()->withErrors('Error al cargar los géneros: ' . $e->getMessage());
        }
    }
    public function update(GenderUpdate $request)
    {
        try {
            $validated = $request->validated();
            //lista completa de géneros en la base de datos
            $genders = Gender::All();
            foreach ($genders as $gender) {
                //si el género no está en la solicitud, establecer su estado en false
                if (!array_key_exists($gender->name, $validated)) {
                    $gender->status = false;
                    $gender->save();
                }
            }
            foreach ($validated as $name => $status) {
                $gender = Gender::where('name', $name)->first();
                if ($gender) {
                    $gender->status = $status ?? true;
                    $gender->save();
                }
            }
            //Si no hay ningun genero seleccionado, activar 'Prefer_not_to_say' por defecto
            if (Gender::where('status', true)->count() == 0) {
                $gender = Gender::where('name', 'Prefer_not_to_say')->first();
                if ($gender) {
                    $gender->status = true;
                    $gender->save();
                }
            }
            //Mensaje de éxito
            session()->flash('success', [
                'title' => 'Actualizado!',
                'text' => 'Los géneros han sido actualizados con éxito.',
                'icon' => 'success',
            ]);
            return redirect()->route('dashboard.genders.edit');
        } catch (\Exception $e) {
            return back()->withErrors('Error al actualizar los géneros: ' . $e->getMessage());
        }
    }
}
