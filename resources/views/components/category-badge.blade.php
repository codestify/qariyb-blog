@props(['category'])

<span {{ $attributes->merge(['class' => "cat-{$category} px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider rounded-full backdrop-blur-sm"]) }}>
    {{ ucfirst($category) }}
</span>
