<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 border border-red-400 border-opacity-40 rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 hover:border-opacity-60 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-slate-950 active:bg-red-800 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
