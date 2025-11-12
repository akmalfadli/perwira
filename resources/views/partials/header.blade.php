{{-- resources/views/partials/header.blade.php
<header class="bg-green-700 text-white">
    <div class="py-3 px-4 md:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-15 h-8 flex items-center justify-center">
                    <a href="{{ ci_route() }}" class="block">
                        <figure>
                            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ ucfirst(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']) }}" class="h-12 mx-auto pb-2">
                        </figure>
                    </a>
                </div>
                <div class="mb-2">
                    <p class="text-sm font-semibold">{{ ucfirst(setting('sebutan_desa')) }}</p>
                    <p class="text-sm font-semibold -mt-1">{{ ucwords($desa['nama_desa']) }}</p>
                </div>
            </div>
            @include('theme::commons.main_menu')
            @include('theme::commons.mobile_menu')
        </div>
    </div>
</header> --}}