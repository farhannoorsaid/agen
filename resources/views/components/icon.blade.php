@props(['name', 'class' => 'h-5 w-5'])

@php
    $c = $class;
    // Simple set of Heroicons (outline) mappings
    $icons = [
        'box' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7l9-4 9 4v6a2 2 0 01-1 1.732L12 21 4 14.732A2 2 0 013 13V7z"/></svg>',
        'folder' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7a2 2 0 012-2h3l2 2h7a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>',
        'plus' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>',
        'edit' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536M4 20l4-1 11-11a2.828 2.828 0 10-4-4L4 16v4z"/></svg>',
        'archive' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 10h6"/></svg>',
        'restore' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v6h6M21 17v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 7a9 9 0 10-9 9"/></svg>',
        'trash' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5-4h4l1 4H9l1-4z"/></svg>',
        'check' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>',
        'cart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 6h14"/></svg>',
        'bolt' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>',
        'download' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v12m0 0l4-4m-4 4l-4-4M21 21H3"/></svg>',
        'chart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 14V7M12 20V10M6 20v-6"/></svg>',
        'x' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>',
        'info' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9h.01M12 7a5 5 0 110 10 5 5 0 010-10zM11 11h1v4h1"/></svg>',
        'hourglass' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 2h12M6 22h12M8 6h8M8 18h8M6 2v6a6 6 0 004 5.657V12a6 6 0 00-4 5.657V22M18 2v6a6 6 0 01-4 5.657V12a6 6 0 014 5.657V22"/></svg>',
        'money' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="'.$c.'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8M8 12h8M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
    ];

    $svg = $icons[$name] ?? $icons['box'];
@endphp

{!! $svg !!}
