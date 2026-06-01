<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 border border-yellow-500 border-opacity-30 rounded-lg font-semibold text-sm text-slate-200 uppercase tracking-widest hover:bg-slate-700 hover:border-opacity-60 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-slate-950 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
