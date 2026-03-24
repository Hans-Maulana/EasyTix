<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-danger-ez inline-flex items-center px-6 py-2 bg-gradient-to-r from-red-600 to-red-800 border border-transparent rounded-full font-bold text-xs text-white uppercase tracking-widest hover:from-red-500 hover:to-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105']) }}>
    {{ $slot }}
</button>
