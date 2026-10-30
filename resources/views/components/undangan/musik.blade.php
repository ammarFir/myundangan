{{-- @props: data undangan dikirim dari tema yang manggil komponen ini --}}
@props(['undangan'])

@php
    // ambil link musik dari database, jadi string kosong kalau belum diisi
    $musikUrl = trim($undangan->musik ?? '');
    $musikTipe = null;   // nanti isinya 'youtube', 'audio', atau null (tidak ada musik)
    $musikSumber = null; // youtube: ID video, audio: link file yang bisa langsung diputar

    if ($musikUrl !== '') {
        // YouTube: cocokkan pola watch?v=, youtu.be/, shorts/, atau embed/, lalu ambil ID video (11 karakter)
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|embed/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $musikUrl, $cocok)) {
            $musikTipe = 'youtube';
            $musikSumber = $cocok[1];
        }
        // Google Drive: ambil ID file dari pola /file/d/ID/ atau ?id=ID, lalu ubah jadi link unduh langsung
        elseif (preg_match('~drive\.google\.com/(?:file/d/|open\?id=|uc\?(?:.*&)?id=)([A-Za-z0-9_-]+)~', $musikUrl, $cocok)) {
            $musikTipe = 'audio';
            $musikSumber = 'https://drive.google.com/uc?export=download&id=' . $cocok[1];
        }
        // selain itu, anggap link file audio langsung (mp3 dan sejenisnya)
        else {
            $musikTipe = 'audio';
            $musikSumber = $musikUrl;
        }
    }
@endphp

{{-- musik file audio: elemen tersembunyi tanpa controls, loop supaya lagu mengulang terus --}}
@if ($musikTipe === 'audio')
    <audio id="musik-audio" src="{{ $musikSumber }}" loop preload="auto"></audio>
@endif

{{-- musik dari YouTube: player disembunyiin, yang dipakai cuma suaranya --}}
@if ($musikTipe === 'youtube')
    {{-- wadah player, digeser jauh keluar layar dan gak bisa diklik --}}
    <div class="fixed w-px h-px overflow-hidden opacity-0 pointer-events-none" style="left: -9999px;">
        <div id="yt-musik"></div>
    </div>

    <script>
        // muat library resmi YouTube IFrame API
        var ytTag = document.createElement('script');
        ytTag.src = 'https://www.youtube.com/iframe_api';
        document.head.appendChild(ytTag);

        // dipanggil otomatis oleh library YouTube setelah selesai dimuat
        window.onYouTubeIframeAPIReady = function () {
            // simpan player di window.ytMusik supaya bisa dipanggil dari fungsi pemutar di bawah
            window.ytMusik = new YT.Player('yt-musik', {
                height: '1',
                width: '1',
                videoId: '{{ $musikSumber }}', // ID video hasil ekstrak di blok PHP bagian atas file
                playerVars: {
                    loop: 1,                        // ulang terus
                    playlist: '{{ $musikSumber }}', // YouTube butuh ini supaya loop jalan untuk 1 video
                    controls: 0,                    // sembunyiin kontrol
                    playsinline: 1                  // jangan paksa fullscreen di iPhone
                }
            });
        };
    </script>
@endif

{{-- fungsi pemutar global: dipanggil tombol "Buka Undangan" milik tema manapun --}}
<script>
    window.mulaiMusikUndangan = function () {
        // musik file audio: coba putar, kalau browser menolak abaikan saja supaya tidak muncul error
        var audio = document.getElementById('musik-audio');
        if (audio) {
            audio.play().catch(function () {});
        }
        // musik YouTube: kalau player sudah siap, mulai putar
        if (window.ytMusik && window.ytMusik.playVideo) {
            window.ytMusik.playVideo();
        }
    };
</script>