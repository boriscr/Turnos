<!-- resources/views/dashboard/design.blade.php -->
@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="">
        <x-form.titles :value="__('navbar.settings')" size="edit-create" />
        <x-form.titles :value="__('dashboard.genders')" type="subtitle" />
        <div class="container-form full-center">
            <form action="{{ route('dashboard.genders.update') }}" method="post">
                @csrf
                @method('PUT')
                <div class="item-colores">
                    <div class="card">
                        @foreach ($genders as $gender)
                            <x-form.text-input type="checkbox" name="{{ $gender->name }}"
                                label="{{ $gender->translated_name }}" value="1"
                                checkedValue="{{ $gender->status ? 'checked' : '' }}" />
                        @endforeach
                    </div>
                </div>
                <hr>
                <br>
                <x-primary-button>
                    {{ __('medical.update') }}
                </x-primary-button>
            </form>
        </div>
    </div>
@endsection
