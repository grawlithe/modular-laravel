<x-contacts::layouts.master>
    <h1>Hello World</h1>

    <p>Module: {!! config('contacts.name') !!}</p>

    {{-- This is the container where your Vue app will mount --}}
    <div id="contact-manager-app">
        </div>

    {{-- Link to your compiled module JS asset --}}
    @vite('Modules/Contacts/Resources/assets/js/app.js')
</x-contacts::layouts.master>
