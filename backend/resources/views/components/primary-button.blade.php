<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-maroon border border-transparent rounded-full font-semibold text-sm text-white uppercase tracking-widest hover:bg-maroon-dark focus:bg-maroon-dark active:bg-maroon-darker focus:outline-none focus:ring-2 focus:ring-maroon/30 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
