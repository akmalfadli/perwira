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
        @include('theme::partials.header')
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
    <script type="text/javascript">
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
        // Initialize Lucide icons
        lucide.createIcons()
    </script>
</body>
</html>