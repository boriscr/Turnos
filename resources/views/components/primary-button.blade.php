<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'primary-btn transition ease-in-out duration-150',
    ]) }}>
    {{ $slot }}
</button>
