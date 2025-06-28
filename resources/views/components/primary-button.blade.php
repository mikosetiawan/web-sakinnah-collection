<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block bg-[#d4af37] text-white px-6 py-2 rounded-lg hover:bg-[#b8972e] transition-colors mb-4']) }}>
    {{ $slot }}
</button>
