<button {{ $attributes->merge(['type' => 'button', 'class' => 'secondary-btn full-center']) }}>
    {{ $slot }}
</button>
