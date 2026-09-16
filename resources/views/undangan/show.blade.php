@extends('layouts.site')

@section('title', $undangan->nama_pria . ' & ' . $undangan->nama_wanita)

@section('content')

    <div class="max-w-2xl px-4 py-16 mx-auto text-center">

        {{-- nama pasangan --}}
        <h1 class="font-serif text-4xl font-bold text-gray-900">
            {{ $undangan->nama_pria }} & {{ $undangan->nama_wanita }}
        </h1>

        {{-- tanggal acara, cuma ditampilin kalau ada isinya --}}
        @if ($undangan->tanggal_acara)
            <p class="mt-4 text-gray-600">
                {{-- format() mengubah tanggal jadi format Indonesia yang enak dibaca --}}
                {{ \Carbon\Carbon::parse($undangan->tanggal_acara)->translatedFormat('l, d F Y') }}
                @if ($undangan->waktu_acara)
                    — {{ \Carbon\Carbon::parse($undangan->waktu_acara)->format('H:i') }} WIB
                @endif
            </p>
        @endif

        {{-- lokasi acara --}}
        @if ($undangan->lokasi)
            <div class="mt-8">
                <h2 class="font-semibold text-gray-900">Lokasi</h2>
                <p class="mt-1 text-gray-600">{{ $undangan->lokasi }}</p>
                @if ($undangan->alamat)
                    <p class="text-sm text-gray-500">{{ $undangan->alamat }}</p>
                @endif
                @if ($undangan->link_maps)
                    <a href="{{ $undangan->link_maps }}" target="_blank"
                        class="inline-block px-4 py-2 mt-3 text-sm font-semibold text-gray-900 border border-gray-300 rounded-full hover:bg-gray-50">
                        Lihat di Google Maps
                    </a>
                @endif
            </div>
        @endif

        {{-- link live streaming, kalau ada --}}
        @if ($undangan->link_streaming)
            <div class="mt-8">
                <a href="{{ $undangan->link_streaming }}" target="_blank"
                    class="inline-block px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                    Tonton Live Streaming
                </a>
            </div>
        @endif

    </div>

@endsection