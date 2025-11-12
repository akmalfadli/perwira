@if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @if (strtolower($widget['judul']) == "visi misi")
                <div class="w-full md:w-1/2">
                    <h2 class="text-2xl font-bold">Visi Misi Desa</h2>
                    <h3 class="text-green-600 font-semibold mb-4">Cita Cita Desa</h3>
                    @php
                        
                        $url = '';
                        preg_match_all('/\[(https?:\/\/[^\]]+)\]/', $widget['isi'], $matches);

                        if (!empty($matches[1])) {
                            // Ambil URL pertama
                            $url = $matches[1][0]; 
                        }
                        $cleanText = preg_replace('/\[.*?\]/', '', $widget['isi']);
                        
                        $visimisi = explode(';', $cleanText);
                        
                    @endphp

                    @foreach ($visimisi as $isi)
                        <p class="text-sm text-justify text-gray-700 mb-2">
                            {!! potong_teks(html_entity_decode($isi), 450) !!} 
                            {{ strlen($isi) > 450 ? '...' : '' }}
                        </p>
                    @endforeach
                    <a href={{$url}}>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors mt-3">
                            Lihat Selengkapnya
                        </button>
                    </a>
                </div>
            @endif
        @endforeach
 @endif