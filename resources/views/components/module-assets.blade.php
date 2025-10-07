@props(['module'])

@php
    $moduleName = $module->getName();
    $jsPath = 'Modules/' . $moduleName . '/resources/assets/js/app.js';
@endphp

@vite($jsPath)
