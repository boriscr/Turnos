<style>
    .app-icon {
        width: 64px;
        height: 64px;
    }
</style>
<picture>
    <source srcset="{{ asset('images/app-icon.png') }}" type="image/png">
    <img class="app-icon" src="{{ asset('images/app-icon.png') }}" alt="{{ config('app.name') }}">
</picture>
