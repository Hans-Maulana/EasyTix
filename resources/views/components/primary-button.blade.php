<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary-ez inline-flex items-center px-6 py-2 bg-gradient-to-r from-orange-500 to-red-600 border border-transparent rounded-full font-bold text-xs text-white uppercase tracking-widest hover:from-orange-600 hover:to-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105']) }}>
    {{ $slot }}
</button>
