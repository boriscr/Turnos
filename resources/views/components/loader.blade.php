<div class="active loader-overlay" id="globalLoader" style="width: {{ $width }}px; height: {{ $height }}px;">
    <div class="modern-loader">
        <div class="loader-content">
            <img src="{{asset('images/specialiste.gif')}}" alt="especialista logo" class="loader-logo" />
            <div class="loader-spinner {{ $type }}"></div>
            <p class="loader-text">{{ $message }}</p>
        </div>
    </div>
</div>