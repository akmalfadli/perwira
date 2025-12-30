@php
    $themeVersion = 'v2508.0.0';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title', 'Website Resmi ' . ucfirst(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']))
    </title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        @include('theme::commons.meta')
        @include('theme::commons.source_css')
        @include('theme::commons.source_js')
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link rel="stylesheet" href="{{ theme_asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
</head>
@php
    $post = $single_artikel;
@endphp
<body class="w-full bg-white">
    <div class="max-w-6xl mx-auto mb-2">
        @include('theme::commons.loading_screen')
        {{-- @include('theme::partials.header') --}}
        @include('theme::partials.hero')
        
        @yield('layout')
         @if (request()->path() === '/' || request()->path() === '')
            <div class="px-2 md:px-6 lg:px-2">
                <div class="flex flex-col md:flex-row gap-8 mt-8">
                    @include('theme::partials.history')
                    @include('theme::partials.location')
                </div>
                
                <div class="flex flex-col md:flex-row gap-8 mt-16">
                    @include('theme::partials.development')
                    @include('theme::partials.vision')
                </div>
                
                @include('theme::partials.statistics')
                @include('theme::partials.articles')
                @include('theme::partials.officials')
            </div>
        @endif
    </div>
        
    @include('theme::partials.footer')
    @stack('scripts')
    <script src="{{ theme_asset('js/script.min.js') }}&{{ $themeVersion }}"></script>
    <script type="text/javascript">
        function cookiesEnabled() {
            document.cookie = "testcookie=1";
            const enabled = document.cookie.indexOf("testcookie=") !== -1;
            document.cookie = "testcookie=1; Max-Age=0";
            return enabled;
        }
        
        function getCookie(name) {
            const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? decodeURIComponent(match[2]) : null;
        }
        
        // Cek aktivasi tema
        (function() {
            if (!cookiesEnabled()) {
                const enable = confirm("Cookies dinonaktifkan di browser Anda.\nAktifkan cookies untuk melanjutkan.\n\nApakah Anda ingin mengaktifkan cookies sekarang?");
                
                if (enable) {
                    alert("Silakan aktifkan cookies di pengaturan browser Anda, lalu muat ulang halaman ini.");
                    location.reload();
                } else {
                    alert("Anda tidak dapat melanjutkan menggunakan tema tanpa mengaktifkan cookies.");
                    document.body.innerHTML = "<div style='text-align:center;margin-top:50px;font-family:sans-serif;'><h2>⚠️ Cookies tidak aktif</h2><p>Aktifkan cookies untuk melanjutkan menggunakan tema ini.</p></div>";
                    return;
                }
            }
        
            const productKey = "1b7b39be-a750-48ad-9090-629b1c6fd6e9";
            const cookieValue = getCookie('pemesanan-tema');
            let tema = null;
        
            if (cookieValue) {
                try {
                    tema = JSON.parse(cookieValue);
                } catch (e) {
                    console.error('Cookie pemesanan-tema tidak valid:', e);
                }
            }
        
            if (
                typeof tema === "undefined" ||
                tema === null ||
                typeof productKey === "undefined" ||
                !JSON.stringify(tema).includes(productKey)
            ) {
                const baseUrl = typeof SITE_URL !== "undefined" ? SITE_URL : "/";
                window.location.href = baseUrl + "aktivasi-tema";
            }
        })();

        function formatRupiah(angka, prefix = 'Rp ') {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '') + ',00';
        }
    </script>
    <script>
        lucide.createIcons()
    </script>
    @stack('scripts')

    
</body>
</html>