@if ($widgetAktif)
    @foreach ($widgetAktif as $widget)
        @php
            $judul_widget = [
                'judul_widget' => str_replace(
                    'Desa',
                    ucwords(setting('sebutan_desa')),
                    strip_tags($widget['judul'])
                ),
            ];
        @endphp

        @if (strtolower($widget['judul']) == "pengembangan")
            <div class="w-full md:w-1/2 relative">
                <div class="border-2 border-dashed border-green-600 rounded-lg p-6 relative">
                    <div class="absolute -top-3 left-4 bg-white px-2">
                        <h2 class="text-xl font-bold">Arah Pengembangan Desa</h2>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        {{ ucfirst(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }} akan membangun desa yang berkelanjutan, maju dan sejahtera
                    </p>

                    @php
                        $items = explode(",", $widget['isi']);
                    @endphp

                    <div class="space-y-2">
                        @foreach($items as $item)
                            <div class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mt-0.5"></i>
                                <p class="text-sm">{{ trim($item) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-around mt-8">
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="home" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Infrastruktur</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="heart-handshake" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Ekonomi</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="tree-pine" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Lingkungan</p>
                        </div>
                        
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="book-open" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Pendidikan</p>
                        </div>
                        
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
