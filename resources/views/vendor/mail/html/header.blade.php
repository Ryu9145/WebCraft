{{-- HAPUS @props(['url']) --}}
<tr>
    <td class="header">
        {{-- HAPUS {{ $attributes }} di dalam tag <a> --}}
        <a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
            
            {{-- LOGIK: Cek apakah ada URL Logo di Config .env --}}
            @if(config('app.logo_url')) 
                <img src="{{ config('app.logo_url') }}" class="logo" alt="{{ config('app.name') }}" style="height: 40px; width: auto; border: 0;">
            @else
                {{-- FALLBACK: Teks Gaya Liquid Aurora (Putih + Cyan) --}}
                <span style="font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: 1px;">
                    Web<span style="color: #38bdf8;">Craft</span>
                </span>
            @endif
            
        </a>
    </td>
</tr>