@props(['name' => 'generic'])

@php
    $baseClass = 'ui-icon inline-block shrink-0 align-middle text-gray-900';
@endphp

<svg {{ $attributes->merge(['class' => trim($baseClass . ' ' . ($attributes->get('class') ?? ''))]) }} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
    @switch($name)
        @case('dashboard')
        @case('home')
            <path d="M12 3.2 2.8 10a1 1 0 0 0-.4.8V21a1 1 0 0 0 1 1h6.1a1 1 0 0 0 1-1v-5.2h3V21a1 1 0 0 0 1 1h6.1a1 1 0 0 0 1-1V10.8a1 1 0 0 0-.4-.8L12 3.2Z" />
            @break
        @case('book')
        @case('course')
            <path d="M6 3.5A2.5 2.5 0 0 1 8.5 1H20a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H8.5A2.5 2.5 0 0 1 6 17.5v-14Z" />
            <path d="M8.5 3A1.5 1.5 0 0 0 7 4.5v13A1.5 1.5 0 0 0 8.5 19H19V3H8.5Z" fill="#fff" opacity="0.08" />
            @break
        @case('users')
            <path d="M9 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm7 1a3 3 0 1 0-3-3 3 3 0 0 0 3 3Zm-7 2c-3.9 0-7 1.7-7 4v2h14v-2c0-2.3-3.1-4-7-4Zm7 0a7.8 7.8 0 0 0-2.2.3A5.6 5.6 0 0 1 18 19v2h6v-2c0-2.1-2.7-4-6-4Z" />
            @break
        @case('grades')
        @case('check')
            <path d="M20 6.5 9.5 17 4 11.5 5.4 10.1l4.1 4.1L18.6 5.1 20 6.5Z" />
            @break
        @case('report')
            <path d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm8 1.5V8h4.5" />
            <path d="M8 12h8v1.5H8V12Zm0 4h8v1.5H8V16Zm0-8h4v1.5H8V8Z" />
            @break
        @case('settings')
            <path d="M19.1 12.9a7.9 7.9 0 0 0 .1-1 7.9 7.9 0 0 0-.1-1l2-1.6a1 1 0 0 0 .2-1.2l-1.9-3.3a1 1 0 0 0-1.2-.4l-2.4 1a8 8 0 0 0-1.8-1L13.7 2a1 1 0 0 0-1-.8h-3.4a1 1 0 0 0-1 .8l-.4 2.4a8 8 0 0 0-1.8 1l-2.4-1a1 1 0 0 0-1.2.4L.9 8.4a1 1 0 0 0 .2 1.2l2 1.6a7.9 7.9 0 0 0-.1 1 7.9 7.9 0 0 0 .1 1l-2 1.6a1 1 0 0 0-.2 1.2l1.9 3.3a1 1 0 0 0 1.2.4l2.4-1c.6.4 1.2.7 1.8 1l.4 2.4a1 1 0 0 0 1 .8h3.4a1 1 0 0 0 1-.8l.4-2.4c.6-.2 1.2-.6 1.8-1l2.4 1a1 1 0 0 0 1.2-.4l1.9-3.3a1 1 0 0 0-.2-1.2l-2-1.6ZM12 16a4 4 0 1 1 4-4 4 4 0 0 1-4 4Z" />
            @break
        @case('folder')
            <path d="M4 5a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v2H4V5Zm0 4h18v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9Z" />
            @break
        @case('file')
        @case('document')
            <path d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm8 1.5V8h4.5" />
            @break
        @case('eye')
            <path d="M12 5c5.5 0 9.6 3.7 10.9 6.2a1.5 1.5 0 0 1 0 1.6C21.6 15.3 17.5 19 12 19S2.4 15.3 1.1 12.8a1.5 1.5 0 0 1 0-1.6C2.4 8.7 6.5 5 12 5Zm0 2.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5Zm0 2A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z" />
            @break
        @case('download')
            <path d="M11 2h2v10l3.5-3.5L18 10l-6 6-6-6 1.5-1.5L11 12V2Zm-7 16h16v2H4z" />
            @break
        @case('upload')
            <path d="M11 22h2V12l3.5 3.5L18 14l-6-6-6 6 1.5 1.5L11 12v10ZM4 3h16v2H4z" />
            @break
        @case('lock')
            <path d="M12 2a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V7a5 5 0 0 0-5-5Zm-3 8V7a3 3 0 1 1 6 0v3Z" />
            @break
        @case('clipboard')
            <path d="M9 2h6a2 2 0 0 1 2 2h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1a2 2 0 0 1 2-2Zm1 2a1 1 0 0 0-1 1h6a1 1 0 0 0-1-1Z" />
            <path d="M8 10h8v2H8Zm0 4h8v2H8Z" />
            @break
        @case('warning')
            <path d="M12 3 1.7 21h20.6L12 3Zm1 13h-2v-2h2Zm0-4h-2V8h2Z" />
            @break
        @case('back')
            <path d="M10 6 4 12l6 6v-4h10v-4H10V6Z" />
            @break
        @case('close')
            <path d="M6.3 5.3 5.3 6.3 10.9 12l-5.6 5.7 1 1 5.7-5.6 5.6 5.6 1-1L12.9 12l5.7-5.7-1-1-5.6 5.6-5.7-5.6Z" />
            @break
        @case('plus')
            <path d="M11 4h2v6h6v2h-6v6h-2v-6H5v-2h6V4Z" />
            @break
        @case('calendar')
            <path d="M7 2h2v2h6V2h2v2h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3V2Zm13 8H4v10h16V10Z" />
            @break
        @case('person')
            <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.4 0-8 2-8 4.5V21h16v-2.5c0-2.5-3.6-4.5-8-4.5Z" />
            @break
        @default
            <path d="M12 2l9 4.7v10.6L12 22 3 17.3V6.7L12 2Z" />
    @endswitch
</svg>
