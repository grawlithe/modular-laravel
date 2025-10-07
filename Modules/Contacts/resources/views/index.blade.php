<x-contacts::layouts.master>
    {{-- This is the container where your Vue app will mount --}}
    <div id="contact-manager-app"></div>

    {{-- Link to your compiled module JS asset --}}
    @vite('Modules/Contacts/resources/assets/js/app.js')
</x-contacts::layouts.master>
