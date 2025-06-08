<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'px-4 py-2 bg-white border border-white rounded-md font-semibold text-xs text-black uppercase tracking-widest text-center hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>
