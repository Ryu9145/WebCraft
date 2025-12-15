@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        {{-- Mengirim parameter url secara eksplisit --}}
        @component('mail::header', ['url' => config('app.url') ?? url('/')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body (Isi Pesan Utama) --}}
    {{ $slot }}

    {{-- Subcopy (Link alternatif jika tombol tidak bisa diklik) --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent