@props(['name', 'size' => 20])

@php
    $icons = [
        'home' => 'house',
        'bubble' => 'messages-square',
        'chat' => 'message-circle',
        'shield' => 'shield-check',
        'chart' => 'layout-dashboard',
        'bell' => 'bell',
        'search' => 'search',
        'heart' => 'heart',
        'flag' => 'flag',
        'plus' => 'plus',
        'arrow-right' => 'arrow-right',
        'send' => 'send',
        'check' => 'check',
        'alert' => 'triangle-alert',
        'lock' => 'lock',
        'lock-open' => 'lock-open',
        'user' => 'user',
        'users' => 'users',
        'x' => 'x',
        'chevron-right' => 'chevron-right',
        'chevron-down' => 'chevron-down',
        'more' => 'ellipsis',
        'clock' => 'clock',
        'life-buoy' => 'life-buoy',
        'face' => 'scan-face',
        'mail' => 'mail',
        'download' => 'download',
        'logout' => 'log-out',
        'settings' => 'settings',
        'calendar' => 'calendar',
        'file' => 'file-text',
        'clipboard' => 'clipboard-list',
        'user-check' => 'user-check',
        'eye' => 'eye',
        'phone' => 'phone',
        'history' => 'history',
        'check-circle' => 'circle-check',
        'inbox' => 'inbox',
    ];
@endphp

<i
    data-lucide="{{ $icons[$name] ?? $name }}"
    width="{{ $size }}"
    height="{{ $size }}"
    {{ $attributes->class('shrink-0') }}
    aria-hidden="true"
></i>
