@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-maroon focus:ring-maroon/20 rounded-xl shadow-sm']) }}>
