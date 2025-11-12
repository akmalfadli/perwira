<div class="w-full md:w-1/2">
    <h2 class="text-2xl font-bold mb-3">Lokasi Kami</h2>
    <p class="text-sm text-gray-700 mb-3">
        {{ ucwords(setting('sebutan_kecamatan')) }} {{ $desa['nama_kecamatan'] }} {{ ucwords(setting('sebutan_kabupaten')) }} {{ $desa['nama_kabupaten'] }} Provinsi {{ $desa['nama_propinsi'] }}
    </p>
    
    <div class="relative w-full h-[250px] bg-gray-200 z-[9] rounded-lg overflow-hidden">
        <div id="map_canvas" class="w-full h-full"></div>

        <div class="absolute bottom-4 right-4 bg-white p-3 rounded-lg shadow-lg z-[999] pointer-events-auto">
            <div class="flex items-center gap-2">
                <div class="bg-green-100 rounded-full p-1">
                    <i data-lucide="users" class="h-5 w-5 text-green-700"></i>
                </div>
                <div>
                    <a href="https://www.openstreetmap.org/#map=15/{{ $data_config['lat'] }}/{{ $data_config['lng'] }}" target="_blank" class="text-xs font-bold">Kantor {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}</a>
                    <p class="text-xs text-gray-500">{{ $desa['alamat_kantor'] }}</p>
                </div>
            </div>
        </div> 
    </div>
</div>


<!-- Custom Modal for Video Popup -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 z-[10000] flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 relative">
        <!-- Close Button -->
        <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Modal Content -->
        <div class="p-2">
            <div class="text-center">
                <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
                    <h3 class="text-md font-semibold text-white text-center">
                        CCTV Pelayanan Desa
                    </h3>
                </div>
                <div class="h-1 bg-green-500 mb-2"></div>
                <div class="video-container">
                    <video id="cctvVideo" controls muted class="w-full h-auto max-h-80 rounded-lg shadow-lg">
                        <source src="https://timbang-purbalingga.desa.id/stream/pelayanan" type="video/mp4">
                        Browser tidak mendukung video.
                    </video>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #map_canvas {
        width: 100%;
        height: 100%;
    }

    .leaflet-popup-content {
        width: 400px !important;
        margin: 8px 12px;
    }

    .video-popup {
        text-align: center;
        padding: 10px;
    }

    .video-popup video {
        width: 100%;
        height: auto;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .video-popup strong {
        display: block;
        margin-bottom: 10px;
        color: #2d5a27;
        font-size: 16px;
    }

    /* Custom popup styling */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .leaflet-popup-tip {
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

        /* Custom marker style */
        /* Custom Location Pin Marker Styles */
    .custom-location-marker {
        background: none !important;
        border: none !important;
    }

    .location-pin {
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .location-pin:hover {
        transform: scale(1.1);
    }

    .pin-head {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border: 3px solid white;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        position: relative;
        z-index: 2;
        animation: bounce 2s infinite;
    }

    .pin-head svg {
        transform: rotate(45deg);
        color: white;
        width: 16px;
        height: 16px;
    }

    .pin-shadow {
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 16px;
        height: 6px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 50%;
        filter: blur(2px);
        animation: shadowPulse 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-8px);
        }
        60% {
            transform: translateY(-4px);
        }
    }

    @keyframes shadowPulse {
        0%, 20%, 50%, 80%, 100% {
            transform: translateX(-50%) scale(1);
            opacity: 0.2;
        }
        40% {
            transform: translateX(-50%) scale(0.8);
            opacity: 0.3;
        }
        60% {
            transform: translateX(-50%) scale(0.9);
            opacity: 0.25;
        }
    }
    #closeModal {
        color: #000000; /* Custom red color */
        transition: color 0.2s ease;
    }

    #closeModal:hover {
        color: #ffffff; /* Darker red on hover */
    }
</style>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Jika posisi kantor desa belum ada, tampilkan seluruh Indonesia
    @if (!empty($data_config['lat']) && !empty($data_config['lng']))
        var posisi = [{{ $data_config['lat'] }}, {{ $data_config['lng'] }}];
        var zoom = {{ $data_config['zoom'] ?: 10 }};
    @else
        var posisi = [-7.3983118, 109.5432662]; // default center (e.g., Central Java)
        var zoom = 15; // zoom out to see Indonesia
    @endif

    var options = {
        maxZoom: {{ setting('max_zoom_peta') }},
        minZoom: {{ setting('min_zoom_peta') }},
    };

    // Init map
    var lokasi_kantor = L.map('map_canvas', options).setView(posisi, zoom);

    // Base layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(lokasi_kantor);

    // Create custom icon
   var customIcon = L.divIcon({
        className: 'custom-location-marker',
        html: `
            <div class="location-pin">
                <div class="pin-head">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <div class="pin-shadow"></div>
            </div>
        `,
        iconSize: [32, 40],
        iconAnchor: [16, 40],
        popupAnchor: [0, -40]
    });

    // Add marker with custom icon
    var marker = L.marker(posisi, { icon: customIcon }).addTo(lokasi_kantor);

    // Modal elements
    var modal = document.getElementById('videoModal');
    var video = document.getElementById('cctvVideo');
    var closeBtn = document.getElementById('closeModal');

    // Function to open modal
    function openVideoModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Reset and play video
        video.currentTime = 0;
        video.play().catch(function(error) {
            console.log('Auto-play was prevented:', error);
        });
    }

    // Function to close modal
    function closeVideoModal() {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            video.pause();
        }, 300);
    }

    // Add click event to marker
    marker.on('click', function(e) {
        openVideoModal();
    });

    // Close modal events
    closeBtn.addEventListener('click', closeVideoModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeVideoModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeVideoModal();
        }
    });

    // Add tooltip to marker
    marker.bindTooltip("Klik untuk melihat CCTV Pelayanan", {
        permanent: false,
        direction: "top",
        offset: [0, -10]
    });
</script>