@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-4 py-2.5 w-full bg-slate-950 border border-yellow-500 border-opacity-25 text-slate-100 placeholder-slate-500 rounded-lg shadow-sm focus:border-yellow-500 focus:border-opacity-60 focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-20 focus:outline-none transition ease-in-out duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
