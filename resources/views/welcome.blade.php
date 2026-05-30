<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArbaLabs | Orbital Mission Control</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Custom style agar Popup Leaflet mengikuti tema gelap ArbaLabs */
    .leaflet-popup-content-wrapper {
        background: #0f1423 !important;
        color: #ffffff !important;
        border: 1px solid #232a40 !important;
        border-radius: 0.75rem !important;
        font-family: 'Space Grotesk', sans-serif !important;
        box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }
    .leaflet-popup-tip {
        background: #0f1423 !important;
    }
    .leaflet-container {
        background: #05070e !important;
    }

    /* Tooltip Interaktif Negara */
    .country-tooltip {
        background-color: #0f1423 !important;
        border: 1px solid #00d2ff !important;
        color: #fff !important;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: bold;
        letter-spacing: 1px;
        border-radius: 4px;
        padding: 4px 10px;
        box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);
    }
    .leaflet-tooltip-left.country-tooltip::before { border-left-color: #00d2ff; }
    .leaflet-tooltip-right.country-tooltip::before { border-right-color: #00d2ff; }
</style>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              spaceBase: '#05070e',  // Warna paling gelap
              panelBg: '#0f1423',    // Warna panel (biru gelap keabuan)
              panelBorder: '#494949',// Border panel yang soft
              neonCyan: '#00d2ff',   // Cyan
              neonYellow: '#ffdd00', // Kuning rute satelit
            },
            fontFamily: {
              grotesk: ['"Space Grotesk"', 'sans-serif'], 
            }
          }
        }
      }
    </script>
    <style>
    /* Sembunyikan scrollbar agar tampilan lebih sinematik */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #232a40; border-radius: 4px; }

    /* Efek Hover Garis Gradasi Bawah Judul */
    .hover-glow-title {
        position: relative;
        width: fit-content; /* Memastikan garis hanya selebar teks */
    }
    .hover-glow-title::after {
        content: '';
        position: absolute;
        bottom: -4px; /* Jarak garis efek dengan teks */
        left: 0;
        width: 100%;
        height: 2px; /* Ketebalan garis efek */
        /* Gradasi: Transparan (kiri) -> Biru Tua (tengah) -> Transparan (kanan) */
        background: linear-gradient(to right, transparent, #1d4ed8, transparent);
        transform: scaleX(0); /* Sembunyikan garis secara default */
        transform-origin: center; /* Animasi melebar dari tengah */
        transition: transform 0.3s ease-out; /* Kecepatan animasi */
    }
    .hover-glow-title:hover::after {
        transform: scaleX(1); /* Munculkan garis saat di-hover */
    }

    /* Efek Gradasi Radial untuk Background Panel */
    .bg-radial-panel {
        background: radial-gradient(circle at top right, #222B59 0%, #1A1A1A 100%);
    }

    /* Efek Masuk Log Terminal */
    @keyframes logSlideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-log { animation: logSlideIn 0.3s ease-out forwards; }
</style>
</head>
<body class="bg-spaceBase text-white font-grotesk min-h-screen w-full overflow-x-hidden overflow-y-auto flex flex-col selection:bg-neonCyan selection:text-black">

    <header class="h-16 flex items-center justify-between px-6 bg-[#080b14] border-b border-panelBorder z-10 flex-shrink-0">
    
    <div class="flex items-center gap-3">
        <img src="/../images/arbalabs.png" alt="ArbaLabs Logo" class="h-10 w-auto">
        
        <div class="h-6 w-px bg-panelBorder mx-1 hidden sm:block"></div>
        
        <span class="text-base font-bold tracking-widest text-gray-100 font-grotesk hidden sm:block drop-shadow-[0_0_10px_rgba(255,255,255,0.15)]">
            Global Orbital Operations
        </span>
    </div>

    <div class="flex items-center gap-2 border border-panelBorder rounded-full px-4 py-1.5 bg-black/30 backdrop-blur-sm text-xs font-medium text-gray-300 shadow-inner">
        <span>System status</span>
        
        <div class="h-4 w-px bg-panelBorder mx-1"></div>
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#222B59]" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.532 1.532 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
        </svg>

        <span>All systems</span>
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>

</header>

    <main class="flex-1 p-4 grid grid-cols-12 gap-5">
        
        <aside class="col-span-3 flex flex-col gap-5 h-full">
            
            <div class="bg-radial-panel border border-panelBorder rounded-xl p-5 shadow-lg">
    <h2 class="text-lg font-bold text-gray-100 mb-4 hover-glow-title flex items-center">
        System Status 
        <span id="active-sat-label-1" class="ml-3 text-[10px] bg-[#ffdd00]/10 text-[#ffdd00] px-2 py-0.5 rounded border border-[#ffdd00]/50 tracking-widest font-mono">NODE X1</span>
    </h2>
    <div class="space-y-1">
        <div onclick="openStatusModal('verification')" class="flex justify-between text-xs tracking-wider font-semibold p-2 -mx-2 rounded hover:bg-white/5 cursor-pointer transition-colors group">
            <span class="text-gray-400 group-hover:text-white transition-colors">VERIFICATION</span> 
            <span class="text-teal-400">SECURE</span>
        </div>
        <div onclick="openStatusModal('edge_ai')" class="flex justify-between text-xs tracking-wider font-semibold p-2 -mx-2 rounded hover:bg-white/5 cursor-pointer transition-colors group">
            <span class="text-gray-400 group-hover:text-white transition-colors">EDGE AI</span> 
            <span class="text-blue-500">EDGE AI</span>
        </div>
        <div onclick="openStatusModal('payload')" class="flex justify-between text-xs tracking-wider font-semibold p-2 -mx-2 rounded hover:bg-white/5 cursor-pointer transition-colors group">
            <span class="text-gray-400 group-hover:text-white transition-colors">PAYLOAD</span> 
            <span class="text-yellow-400">PAYLOAD</span>
        </div>
        <div onclick="openStatusModal('countdown_status')" class="flex justify-between text-xs tracking-wider font-semibold p-2 -mx-2 rounded hover:bg-white/5 cursor-pointer transition-colors group">
            <span class="text-gray-400 group-hover:text-white transition-colors">LAUNCH READINESS</span> 
            <span id="status-ready-val" class="text-green-400">OPTIMAL</span>
        </div>
    </div>
</div>
            
           <div class="bg-radial-panel border border-panelBorder rounded-xl p-5 shadow-lg flex-1 flex flex-col overflow-hidden">
                <h2 class="text-lg font-bold text-gray-100 mb-4 hover-glow-title">Local Event Logs</h2>
                <ul id="event-feed" class="text-xs space-y-3 text-gray-400 overflow-y-auto flex-1 pr-2">
                    </ul>
            </div>
        </aside>

        <section class="col-span-6 flex flex-col gap-5 h-full">
            
            <div class="bg-radial-panel border border-panelBorder rounded-xl flex-1 flex flex-col overflow-hidden shadow-lg relative group min-h-[400px]">
                <div class="absolute top-0 inset-x-0 p-3 bg-gradient-to-b from-panelBg to-transparent z-20">
                    <p id="mission-timeline" class="text-xs font-medium text-gray-300 transition-all duration-500">
        Timeline: <span class="text-blue-400 font-bold drop-shadow-[0_0_5px_rgba(96,165,250,0.8)]">Waiting Countdown Launch</span> 
        <span class="text-gray-500"> > Launch > Starting Orbiting Earth > Success</span>
    </p>
                </div>

                <div id="media-container" class="relative w-full h-full overflow-hidden">
    
    <div id="map-view" class="w-full h-full z-10 relative">
        </div>

    <div id="launch-cam-view" class="w-full h-full hidden absolute inset-0 z-10 bg-black">
        <img id="rocket-cam-img" src="/images/rocket-on-pad.jpg" 
             class="w-full h-full object-cover opacity-80 transition-all duration-1000" 
             alt="Rocket standby on Launch Pad">
        
        <div class="absolute inset-0 bg-black/10 pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px); background-size: 100% 4px;"></div>
        <div class="absolute top-12 left-5 z-20 flex items-center gap-2 bg-black/50 px-3 py-1 rounded border border-gray-700 backdrop-blur-sm">
            <div class="w-2.5 h-2.5 bg-red-600 rounded-full animate-pulse"></div>
            <span class="text-[10px] font-bold text-gray-100 tracking-widest">LIVE - LC-39A PAD CAM</span>
        </div>
        
        <div id="cam-timestamp" class="absolute top-12 right-5 z-20 text-[10px] font-mono text-gray-300 bg-black/50 px-3 py-1 rounded border border-gray-700 backdrop-blur-sm">
            2026-05-30 16:00:00 UTC
        </div>
    </div>
</div>

                <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-[#05070e] to-transparent z-20 flex justify-between items-end">
                    <p id="media-label" class="text-sm font-semibold text-gray-200">Satellite Route</p>
                    <button id="btn-toggle-cam" onclick="toggleCam()" class="bg-white/10 hover:bg-white/20 border border-white/30 backdrop-blur-md px-4 py-1.5 rounded-lg text-xs font-bold text-white transition-all">
                        Launch cam
                    </button>
                </div>
            </div>

            <div class="py-8 bg-radial-panel border border-panelBorder rounded-xl flex flex-col items-center justify-center relative shadow-[0_0_30px_rgba(0,0,0,0.5)] flex-shrink-0 mt-auto">
                <h2 class="absolute top-4 left-5 text-xl font-bold hover-glow-title">Countdown</h2>
                
                <div class="border border-gray-600 bg-black/40 rounded-full px-5 py-1.5 mb-2 mt-4 text-xs tracking-wider text-gray-300">
                    ArbaEdge Launching
                </div>
                
                <div class="flex items-center gap-6 relative">
        <div id="countdown-text" class="text-6xl md:text-7xl font-bold tracking-widest text-gray-100 flex items-center gap-2 drop-shadow-md transition-colors duration-500">
            <span id="cd-days">00</span> <span class="text-gray-500 pb-2">:</span> 
            <span id="cd-hours">00</span> <span class="text-gray-500 pb-2">:</span> 
            <span id="cd-minutes">00</span> <span class="text-gray-500 pb-2">:</span> 
            <span id="cd-seconds">00</span>
        </div>
        
        <button onclick="skipCountdown()" class="p-2 rounded-full bg-white/5 border border-white/10 text-gray-500 hover:text-white hover:bg-white/20 hover:border-white/30 transition-all duration-300" title="Skip to T-10 Seconds">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>
        </section>

        <aside class="col-span-3 flex flex-col gap-5 h-full">
            
            <div class="bg-radial-panel border border-panelBorder rounded-xl p-5 shadow-lg relative">
    <h2 class="text-lg font-bold text-gray-100 flex items-center hover-glow-title">
        Telemetric Dashboard 
        <span id="active-sat-label-2" class="ml-3 text-[10px] bg-[#ffdd00]/10 text-[#ffdd00] px-2 py-0.5 rounded border border-[#ffdd00]/50 tracking-widest font-mono">NODE X1</span>
    </h2> 
    <div class="w-24 h-0.5 mt-2 mb-5"></div>
    
    <div class="space-y-1.5">
        <div class="flex justify-between text-xs tracking-wider font-semibold border-b border-gray-800 pb-1">
            <span class="text-yellow-500">Altitude:</span> 
            <span id="tele-alt" class="text-gray-200 font-mono">0 M</span>
        </div>
        <div class="flex justify-between text-xs tracking-wider font-semibold border-b border-gray-800 pb-1">
            <span class="text-yellow-500">Temperature:</span> 
            <span id="tele-temp" class="text-gray-200 font-mono">0 C</span>
        </div>
        <div class="flex justify-between text-xs tracking-wider font-semibold border-b border-gray-800 pb-1">
            <span class="text-yellow-500">Velocity:</span> 
            <span id="tele-spd" class="text-gray-200 font-mono">0 Km/s</span>
        </div>
        <div class="flex justify-between text-xs tracking-wider font-semibold border-b border-gray-800 pb-1">
            <span class="text-yellow-500">Position (Lat, Lng):</span> 
            <span id="tele-pos" class="text-gray-200 font-mono text-[11px]">0.00°, 0.00°</span>
        </div>
        <div class="flex justify-between text-xs tracking-wider font-semibold border-b border-gray-800 pb-1">
            <span class="text-yellow-500">Battery Voltage:</span> 
            <span id="tele-volt" class="text-gray-200 font-mono">0.0 V</span>
        </div>
        <div class="flex justify-between text-xs tracking-wider font-semibold border-b border-gray-800 pb-1">
            <span class="text-yellow-500">Power Consumption:</span> 
            <span id="tele-pwr" class="text-gray-200 font-mono">0 W</span>
        </div>
        <div class="flex justify-between text-xs tracking-wider font-semibold border-b border-gray-800 pb-1">
            <span class="text-yellow-500">Fuel Level:</span> 
            <span id="tele-fuel" class="text-gray-200 font-mono">100%</span>
        </div>
        <div class="flex justify-between text-xs tracking-wider font-semibold">
            <span class="text-yellow-500">Core Integrity:</span> 
            <span id="tele-core" class="text-gray-200 font-mono">100%</span>
        </div>
    </div>
</div>

            <div class="bg-radial-panel border border-panelBorder rounded-xl p-5 shadow-lg flex-1 flex flex-col overflow-hidden relative">
                <h2 class="text-lg font-bold text-gray-100 mb-4 hover-glow-title">Comment Section</h2>
                
                <div id="chat-feed" class="flex-1 overflow-y-auto space-y-3 mb-4 pr-2 text-xs">
                    </div>

                <div class="flex flex-col gap-3">
                    <div class="flex gap-3 justify-center text-lg mb-1">
        <button onclick="sendEmote('👨‍🚀')" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/5 backdrop-blur-md border border-white/10 shadow-[0_4px_15px_rgba(0,0,0,0.2)] hover:bg-white/20 hover:border-white/30 hover:scale-110 transition-all duration-300">👨‍🚀</button>
        <button onclick="sendEmote('👽')" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/5 backdrop-blur-md border border-white/10 shadow-[0_4px_15px_rgba(0,0,0,0.2)] hover:bg-white/20 hover:border-white/30 hover:scale-110 transition-all duration-300">👽</button>
        <button onclick="sendEmote('🛰️')" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/5 backdrop-blur-md border border-white/10 shadow-[0_4px_15px_rgba(0,0,0,0.2)] hover:bg-white/20 hover:border-white/30 hover:scale-110 transition-all duration-300">🛰️</button>
        <button onclick="sendEmote('☄️')" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/5 backdrop-blur-md border border-white/10 shadow-[0_4px_15px_rgba(0,0,0,0.2)] hover:bg-white/20 hover:border-white/30 hover:scale-110 transition-all duration-300">☄️</button>
        <button onclick="sendEmote('🚀')" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/5 backdrop-blur-md border border-white/10 shadow-[0_4px_15px_rgba(0,0,0,0.2)] hover:bg-white/20 hover:border-white/30 hover:scale-110 transition-all duration-300">🚀</button>
    </div>
                    <div class="relative">
        <input id="chat-input" type="text" placeholder="Type..." class="w-full bg-[#1c2233] border border-[#2c354d] rounded-full px-4 py-2 text-xs text-white focus:outline-none focus:border-blue-500" onkeypress="handleChatEnter(event)">
        
        <button onclick="sendCustomChat()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors">▶</button>
    </div>
                </div>
            </div>
        </aside>
<div id="status-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div id="status-modal-content" class="bg-radial-panel border border-panelBorder rounded-xl p-6 w-80 shadow-[0_0_40px_rgba(0,210,255,0.1)] transform scale-95 transition-transform duration-300 relative">
        </div>
</div>
    </main>

    <script>
        // ==========================================
        // 1. LEAFLET MAP & ORBIT SETUP
        // ==========================================
        const map = L.map('map-view', {
            center: [10, 20], zoom: 2, zoomControl: false, attributionControl: false,
            maxBounds: [[-90, -180], [90, 180]], maxBoundsViscosity: 1.0
        });
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18, minZoom: 2, noWrap: true
        }).addTo(map);

        // ==========================================
        // 1.5. LAYER GEOJSON (BATAS NEGARA INTERAKTIF)
        // ==========================================
        // Buat layer khusus (pane) agar batas negara berada di BAWAH garis rute satelit
        map.createPane('countryBorders');
        map.getPane('countryBorders').style.zIndex = 250; 
        
        let geojsonLayer;
        
        // 1. Gaya default (Garis cyan sangat tipis dan transparan)
        const defaultStyle = {
            color: "#00d2ff",
            weight: 1,
            opacity: 0.15, // Sangat tipis agar tidak menutupi keindahan satelit
            fillColor: "transparent",
            fillOpacity: 0
        };
        
        // 2. Gaya saat mouse di atas negara (Menyala kuning)
        const hoverStyle = {
            color: "#ffdd00",
            weight: 2,
            opacity: 0.8,
            fillColor: "#ffdd00",
            fillOpacity: 0.15 // Memberi efek kaca kuning
        };
        
        // 3. Tarik data batas negara sedunia secara online (Ringan, ~200kb)
        fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json')
            .then(response => response.json())
            .then(data => {
                geojsonLayer = L.geoJson(data, {
                    pane: 'countryBorders',
                    style: defaultStyle,
                    onEachFeature: function (feature, layer) {
                        
                        // Pasang nama negara yang akan mengikuti kursor (sticky)
                        if (feature.properties && feature.properties.name) {
                            layer.bindTooltip(feature.properties.name, {
                                className: 'country-tooltip',
                                sticky: true 
                            });
                        }
                        
                        // Sensor mouse masuk dan keluar
                        layer.on({
                            mouseover: function (e) {
                                e.target.setStyle(hoverStyle); // Nyalakan negara
                            },
                            mouseout: function (e) {
                                geojsonLayer.resetStyle(e.target); // Matikan kembali
                            }
                        });
                    }
                }).addTo(map);
            });

        const orbit1 = [], orbit2 = [], orbit3 = [], orbit4 = []; // Tambahkan orbit4 di sini
        for (let lng = -180; lng <= 180; lng += 1) {
            orbit1.push([-40 * Math.sin((lng + 10) * (Math.PI / 180)), lng]);
            orbit2.push([60 * Math.cos((lng - 45) * (Math.PI / 180)), lng]);
            orbit3.push([20 * Math.sin((lng + 120) * (Math.PI / 180)), lng]);
            // Rute matematika unik untuk satelit ArbaEdge-1
            orbit4.push([-15 * Math.cos((lng + 60) * (Math.PI / 180)), lng]); 
        }
        L.polyline(orbit1, { color: '#ffdd00', weight: 2, dashArray: '5, 10', opacity: 0.6 }).addTo(map);
        L.polyline(orbit2, { color: '#ff2a2a', weight: 2, dashArray: '5, 10', opacity: 0.4 }).addTo(map);
        L.polyline(orbit3, { color: '#00d2ff', weight: 2, dashArray: '5, 10', opacity: 0.4 }).addTo(map);
        // Catatan: polylines4 jangan digambar dulu di sini karena satelitnya belum meluncur!

        const createSatIcon = (imagePath) => L.divIcon({
            html: `<div class="relative w-10 h-10 -ml-5 -mt-5 flex items-center justify-center hover:scale-125 transition-transform duration-300"><img src="${imagePath}" class="w-full h-full drop-shadow-[0_0_10px_rgba(255,255,255,0.8)]" style="transform: rotate(45deg);" alt="Satellite"></div>`,
            className: '', iconSize: [0, 0]
        });

        const sat1 = L.marker(orbit1[0], { icon: createSatIcon('/images/satellite-png.png') }).addTo(map);
        const sat2 = L.marker(orbit2[0], { icon: createSatIcon('/images/sattelite2.png') }).addTo(map);
        const sat3 = L.marker(orbit3[0], { icon: createSatIcon('/images/satellite3.png') }).addTo(map);

        // --- KODE POP-UP YANG SEMPAT HILANG ---
        const popupTemplate = (title, color, payload) => `
            <div class="p-1 font-grotesk min-w-[150px]">
                <h3 class="font-bold text-sm text-[${color}] border-b border-gray-700 pb-1 mb-2">🛰️ ${title}</h3>
                <table class="text-[11px] w-full text-gray-300">
                    <tr><td class="font-semibold text-gray-400 w-14">Status:</td><td class="text-green-400 font-bold">ONLINE</td></tr>
                    <tr><td class="font-semibold text-gray-400">Payload:</td><td>${payload}</td></tr>
                </table>
            </div>
        `;

        sat1.bindPopup(popupTemplate('NODE X1 (ALPHA)', '#ffdd00', 'Edge AI v4'), { autoPan: false, closeOnClick: false });
        sat2.bindPopup(popupTemplate('NODE X2 (POLAR)', '#ff2a2a', 'Thermal Scanner'), { autoPan: false, closeOnClick: false });
        sat3.bindPopup(popupTemplate('NODE X3 (GAMMA)', '#00d2ff', 'Telecom Relay'), { autoPan: false, closeOnClick: false });
        // ----------------------------------------
        // ==========================================
        // 2. SATELLITE PROFILES & DATA STATE
        // ==========================================
        const satProfiles = [
            { id: 0, name: "NODE X1 (ALPHA)", color: "#ffdd00", baseAlt: 12500, baseTemp: 35, baseSpd: 7.5, baseVolt: 24.2, basePwr: 150 },
            { id: 1, name: "NODE X2 (POLAR)", color: "#ff2a2a", baseAlt: 22100, baseTemp: -15, baseSpd: 6.8, baseVolt: 28.5, basePwr: 95 },
            { id: 2, name: "NODE X3 (GAMMA)", color: "#00d2ff", baseAlt: 35780, baseTemp: 12, baseSpd: 3.1, baseVolt: 48.0, basePwr: 280 }
        ];

        let selectedSatIndex = 0; // Default yang dipilih adalah Satelit 1
        let isTracking = false;   // Variabel baru untuk status pelacakan kamera

        // FITUR UX ELEGAN: Jika pengguna men-drag peta secara manual, hentikan pelacakan otomatis
        map.on('dragstart', () => {
            isTracking = false;
            map.closePopup();   // Menyembunyikan pop-up satelit secara dinamis
});
        let activeSatellites = [
            { marker: sat1, coords: orbit1, currentSeg: 0,   progress: 0, steps: 10 }, 
            { marker: sat2, coords: orbit2, currentSeg: 100, progress: 0, steps: 13 }, 
            { marker: sat3, coords: orbit3, currentSeg: 320, progress: 0, steps: 15 } 
        ];

        const statusDataArray = [
            { // SAT 0
                color: 'text-teal-400', glow: 'rgba(45,212,191,0.8)',
                verification: `<div class="space-y-3 text-[11px] text-gray-300 font-mono mb-6"><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> AI Model Verified</div><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> Payload Active</div></div>`,
                integrity: '98.7%',
                edge_ai: `<div class="space-y-2 text-[11px] font-mono mb-5"><div class="flex justify-between"><span>AI-CAM-01</span> <span class="text-green-400">ONLINE</span></div><div class="flex justify-between"><span>PAYLOAD-AI</span> <span class="text-green-400">ONLINE</span></div></div><div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-700 text-[11px] font-mono"><div><span class="text-gray-500 block">CPU Usage</span><span class="text-white">67%</span></div><div><span class="text-gray-500 block">Inference</span><span class="text-white">122/s</span></div></div>`,
                payload: `<div class="space-y-3 text-[11px] font-mono mb-6"><div class="flex justify-between border-b border-gray-800 pb-1"><span>Edge Processor</span> <span class="text-green-400">ACTIVE</span></div></div><div class="flex justify-between pt-3 border-t border-gray-700 font-mono"><div class="flex flex-col"><span class="text-[10px] text-gray-500">Power</span><span class="text-white">150W</span></div><div class="flex flex-col items-end"><span class="text-[10px] text-gray-500">Health</span><span class="text-green-400 font-bold">99%</span></div></div>`
            },
            { // SAT 1
                color: 'text-red-400', glow: 'rgba(248,113,113,0.8)',
                verification: `<div class="space-y-3 text-[11px] text-gray-300 font-mono mb-6"><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> Thermal Lens Verified</div><div class="flex items-center gap-3"><span class="text-yellow-400 font-bold">⚠</span> Cryocooler Warning</div></div>`,
                integrity: '92.1%',
                edge_ai: `<div class="space-y-2 text-[11px] font-mono mb-5"><div class="flex justify-between"><span>THERMAL-HUB</span> <span class="text-green-400">ONLINE</span></div></div><div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-700 text-[11px] font-mono"><div><span class="text-gray-500 block">CPU Usage</span><span class="text-white">32%</span></div><div><span class="text-gray-500 block">Temp Core</span><span class="text-white">-12°C</span></div></div>`,
                payload: `<div class="space-y-3 text-[11px] font-mono mb-6"><div class="flex justify-between border-b border-gray-800 pb-1"><span>Infrared Sensor</span> <span class="text-green-400">ACTIVE</span></div><div class="flex justify-between border-b border-gray-800 pb-1"><span>Laser Rangefinder</span> <span class="text-gray-500">STANDBY</span></div></div><div class="flex justify-between pt-3 border-t border-gray-700 font-mono"><div class="flex flex-col"><span class="text-[10px] text-gray-500">Power</span><span class="text-white">95W</span></div><div class="flex flex-col items-end"><span class="text-[10px] text-gray-500">Health</span><span class="text-yellow-400 font-bold">88%</span></div></div>`
            },
            { // SAT 2
                color: 'text-[#00d2ff]', glow: 'rgba(0,210,255,0.8)',
                verification: `<div class="space-y-3 text-[11px] text-gray-300 font-mono mb-6"><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> Comm Link Verified</div><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> Bandwidth Stable</div></div>`,
                integrity: '99.9%',
                edge_ai: `<div class="space-y-2 text-[11px] font-mono mb-5"><div class="flex justify-between"><span>RELAY-NODE-A</span> <span class="text-green-400">ONLINE</span></div><div class="flex justify-between"><span>RELAY-NODE-B</span> <span class="text-green-400">ONLINE</span></div></div><div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-700 text-[11px] font-mono"><div><span class="text-gray-500 block">Bandwidth</span><span class="text-white">12.4 Gbps</span></div><div><span class="text-gray-500 block">Packet Loss</span><span class="text-white">0.01%</span></div></div>`,
                payload: `<div class="space-y-3 text-[11px] font-mono mb-6"><div class="flex justify-between border-b border-gray-800 pb-1"><span>Ka-Band Transceiver</span> <span class="text-green-400">ACTIVE</span></div><div class="flex justify-between border-b border-gray-800 pb-1"><span>X-Band Backup</span> <span class="text-gray-500">STANDBY</span></div></div><div class="flex justify-between pt-3 border-t border-gray-700 font-mono"><div class="flex flex-col"><span class="text-[10px] text-gray-500">Power</span><span class="text-white">280W</span></div><div class="flex flex-col items-end"><span class="text-[10px] text-gray-500">Health</span><span class="text-green-400 font-bold">100%</span></div></div>`
            }
        ];

        // ==========================================
        // 3. CORE LOGIC (TELEMETRY & UI)
        // ==========================================
        function updateTelemetry() {
            // JIKA TOMBOL LAUNCH CAM AKTIF
            if (isCamActive) {
                if (launchStage === 'standby' || launchStage === 'launch') {
                    document.getElementById('tele-alt').innerText = "0 M";
                    document.getElementById('tele-temp').innerText = "22 C";
                    document.getElementById('tele-spd').innerText = "0 Km/s";
                    document.getElementById('tele-pos').innerText = "LC-39A, PAD";
                    document.getElementById('tele-volt').innerText = "24.0 V";
                    document.getElementById('tele-pwr').innerText = "45 W";
                    document.getElementById('tele-fuel').innerText = "100.0%";
                    document.getElementById('tele-core').innerText = "100% (READY)";
                } 
                else if (launchStage === 'orbiting') {
                    let alt = Math.floor(Math.random() * 3000) + 85000;    
                    let temp = Math.floor(Math.random() * 5) - 45;         
                    let spd = (Math.random() * 0.4 + 6.82).toFixed(2);     
                    let volt = (Math.random() * 0.3 + 23.7).toFixed(1);
                    let pwr = Math.floor(Math.random() * 30) + 210;        
                    let fuel = (96.4 - (Math.random() * 0.8)).toFixed(1);  
                    
                    document.getElementById('tele-alt').innerText = `${alt.toLocaleString()} M`;
                    document.getElementById('tele-temp').innerText = `${temp} C`;
                    document.getElementById('tele-spd').innerText = `${spd} Km/s`;
                    document.getElementById('tele-pos').innerText = "UPPER ATMOSPHERE";
                    document.getElementById('tele-volt').innerText = `${volt} V`;
                    document.getElementById('tele-pwr').innerText = `${pwr} W`;
                    document.getElementById('tele-fuel').innerText = `${fuel}% (PROPULSION)`;
                    document.getElementById('tele-core').innerText = "100% (ACTIVE)";
                }
                // --- KONDISI BARU: DATA SAAT SUKSES MENGORBIT BUMI ---
                else if (launchStage === 'success') {
                    let alt = Math.floor(Math.random() * 40) + 420150;     // Tinggi stabil di Orbit Rendah Bumi (~420 Km)
                    let temp = Math.floor(Math.random() * 2) - 12;         // Suhu luar angkasa stabil di -12°C
                    let spd = (Math.random() * 0.02 + 7.66).toFixed(2);    // Kecepatan orbit konstan super cepat (7.66 Km/s)
                    let volt = (Math.random() * 0.1 + 28.4).toFixed(1);    // Voltase naik karena panel surya menangkap matahari
                    let pwr = Math.floor(Math.random() * 5) + 72;          // Konsumsi daya turun ke mode hemat operasional
                    
                    document.getElementById('tele-alt').innerText = `${alt.toLocaleString()} M`;
                    document.getElementById('tele-temp').innerText = `${temp} C`;
                    document.getElementById('tele-spd').innerText = `${spd} Km/s`;
                    document.getElementById('tele-pos').innerText = "LOW EARTH ORBIT (LEO)";
                    document.getElementById('tele-volt').innerText = `${volt} V`;
                    document.getElementById('tele-pwr').innerText = `${pwr} W`;
                    document.getElementById('tele-fuel').innerText = "95.1% (RESERVED)"; // Sisa bahan bakar cadangan
                    document.getElementById('tele-core').innerText = "100% (ONLINE)";
                }
                return; 
            }

            // DATA JIKA SEDANG MELIHAT PETA SATELIT BIASA
            if (typeof satProfiles === 'undefined' || !satProfiles[selectedSatIndex]) return;
            const profile = satProfiles[selectedSatIndex];
            
            let alt = Math.floor(Math.random() * 150) + profile.baseAlt; 
            let temp = Math.floor(Math.random() * 4) + profile.baseTemp;    
            let spd = (Math.random() * 0.15 + profile.baseSpd).toFixed(2);    
            let volt = (Math.random() * 0.4 + profile.baseVolt).toFixed(1); 
            let pwr = Math.floor(Math.random() * 15) + profile.basePwr;      
            
            let posText = "0.00°, 0.00°";
            if (activeSatellites && activeSatellites[selectedSatIndex]) {
                let latlng = activeSatellites[selectedSatIndex].marker.getLatLng();
                posText = `${latlng.lat.toFixed(2)}°, ${latlng.lng.toFixed(2)}°`;
            }

            document.getElementById('tele-alt').innerText = `${alt.toLocaleString()} M`;
            document.getElementById('tele-temp').innerText = `${temp} C`;
            document.getElementById('tele-spd').innerText = `${spd} Km/s`;
            document.getElementById('tele-pos').innerText = posText;
            document.getElementById('tele-volt').innerText = `${volt} V`;
            document.getElementById('tele-pwr').innerText = `${pwr} W`;
            document.getElementById('tele-fuel').innerText = "92.4% (ION)";
            document.getElementById('tele-core').innerText = "99.8% (NOMINAL)";
        }

        function selectSatellite(index) {
    selectedSatIndex = index;
    const profile = satProfiles[index];
    
    // Update label nama satelit di kedua panel UI
    ['active-sat-label-1', 'active-sat-label-2'].forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            el.innerText = profile.name;
            el.style.color = profile.color;
            el.style.borderColor = profile.color;
            el.style.backgroundColor = `${profile.color}20`;
        }
    });
    updateTelemetry();

    // AKTIFKAN AUTO-TRACKING KAMERA
    isTracking = true;
    
    // Autofokus peta langsung menuju posisi satelit yang diklik (Zoom level set ke 4 agar pas di tengah)
    const targetPos = activeSatellites[index].marker.getLatLng();
    map.setView(targetPos, 4, { animate: true, duration: 1 });
    
    // Buka pop-up informasi singkat satelit secara otomatis saat diklik
    activeSatellites[index].marker.openPopup();
    }

        sat1.on('click', () => selectSatellite(0));
        sat2.on('click', () => selectSatellite(1));
        sat3.on('click', () => selectSatellite(2));

        function openStatusModal(type) {
    const modal = document.getElementById('status-modal');
    const contentBox = document.getElementById('status-modal-content');
    
    let data;
    // JIKA KAMERA AKTIF: Gunakan dataset khusus ArbaLabs 1 (Pre-launch)
    if (isCamActive) {
        data = {
            color: 'text-[#00d2ff]', glow: 'rgba(0,210,255,0.8)', integrity: '100%',
            verification: `<div class="space-y-3 text-[11px] text-gray-300 font-mono mb-6"><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> Ground Diagnostics Clear</div><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> Umbilical Data Link Connected</div></div>`,
            edge_ai: `<div class="space-y-2 text-[11px] font-mono mb-5"><div class="flex justify-between"><span>PAD-CORE-AI</span> <span class="text-green-400">NOMINAL</span></div></div>`,
            payload: `<div class="space-y-3 text-[11px] font-mono mb-6"><div>ArbaLabs 1 capsule structural stress is 0%. Sealed and pristine.</div></div>`,
            countdown_status: `<div class="space-y-3 text-[11px] font-mono mb-6"><div>Launch terminal holding sequence normal. Ground ignition matrix synchronized.</div></div>`
        };
    } else {
        // JIKA LIHAT PETA: Gunakan data satelit orbit biasa
        if(!statusDataArray[selectedSatIndex]) return;
        data = statusDataArray[selectedSatIndex];
        data.countdown_status = `<div class="space-y-3 text-[11px] font-mono mb-6"><div>Orbital insertion phase successful. Satellites operational.</div></div>`;
    }
    
    // Tentukan judul header modal berdasarkan tombol mana yang diklik
    let titleStr = type === 'verification' ? 'SYSTEM VERIFICATION' : 
                   (type === 'edge_ai' ? 'EDGE DEVICE STATUS' : 
                   (type === 'payload' ? 'PAYLOAD STATUS' : 'LAUNCH READINESS'));
    
    contentBox.innerHTML = `
        <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
            <h3 class="font-bold ${data.color} tracking-wider">${titleStr}</h3>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-red-400 text-xl font-bold transition-colors">&times;</button>
        </div>
        ${data[type] || '<p class="text-xs text-gray-400">No data entry</p>'}
        ${type === 'verification' || type === 'countdown_status' ? `<div class="mt-4 pt-4 border-t border-gray-700 flex justify-between items-end"><span class="text-xs text-gray-400 tracking-wider">Mission Integrity</span><span class="text-2xl font-bold ${data.color} drop-shadow-[0_0_8px_${data.glow}]">${data.integrity}</span></div>` : ''}
    `;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        contentBox.classList.remove('scale-95');
        contentBox.classList.add('scale-100');
    }, 10);
}

        function closeStatusModal() {
            const modal = document.getElementById('status-modal');
            const contentBox = document.getElementById('status-modal-content');
            modal.classList.add('opacity-0');
            contentBox.classList.remove('scale-100');
            contentBox.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        // ==========================================
        // 4. EXTRA FEATURES (CAM, CHAT, LOGS, COUNTDOWN)
        // ==========================================
        let isCamActive = false;
        let timestampInterval;

        function toggleCam() {
    isCamActive = !isCamActive;
    const mapLayer = document.getElementById('map-view');
    const camLayer = document.getElementById('launch-cam-view');
    const btn = document.getElementById('btn-toggle-cam');
    const label = document.getElementById('media-label');

    if(isCamActive) {
        mapLayer.style.display = 'none';
        camLayer.classList.remove('hidden');
        btn.innerText = "Satellite map";
        label.innerText = "LC-39A PAD CAMERA - STANDBY PREPARATION";
        label.classList.add('text-red-400', 'animate-pulse');
        updateCamTimestamp();
        timestampInterval = setInterval(updateCamTimestamp, 1000);
        
        // REVISI BARU: Matikan auto-tracking & paksa ubah badge nama menjadi ArbaLabs 1
        isTracking = false;
        ['active-sat-label-1', 'active-sat-label-2'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.innerText = "ArbaLabs 1";
                el.style.color = "#00d2ff"; // Warna Cyan khas pre-launch
                el.style.borderColor = "#00d2ff50";
                el.style.backgroundColor = "rgba(0, 210, 255, 0.1)";
            }
        });
        updateTelemetry(); // Update tampilan angka seketika ke posisi 0
    } else {
        mapLayer.style.display = 'block';
        if(typeof map !== 'undefined') map.invalidateSize();
        camLayer.classList.add('hidden');
        btn.innerText = "Launch cam";
        label.innerText = "Satellite Route";
        label.classList.remove('text-red-400', 'animate-pulse');
        clearInterval(timestampInterval);
        
        // REVISI BARU: Kembalikan nama badge ke satelit orbit yang aktif semula
        selectSatellite(selectedSatIndex);
    }
}

        function updateCamTimestamp() {
            const tsElement = document.getElementById('cam-timestamp');
            if(!tsElement) return;
            const now = new Date();
            const year = 2026; 
            const month = String(now.getUTCMonth() + 1).padStart(2, '0');
            const day = String(now.getUTCDate()).padStart(2, '0');
            const hours = String(now.getUTCHours()).padStart(2, '0');
            const minutes = String(now.getUTCMinutes()).padStart(2, '0');
            const seconds = String(now.getUTCSeconds()).padStart(2, '0');
            tsElement.innerText = `${year}-${month}-${day} ${hours}:${minutes}:${seconds} UTC`;
        }

        // ==========================================
        // LOCAL EVENT LOGS (Terminal Style)
        // ==========================================
        const logData = [
            { level: "INFO", color: "text-blue-400", msg: "Verifying subsystem integrity..." },
            { level: "INFO", color: "text-blue-400", msg: "Handshake protocol established via Node-A." },
            { level: "WARN", color: "text-yellow-400", msg: "Thermal variance detected. Compensating..." },
            { level: "INFO", color: "text-blue-400", msg: "Adjusting orbital trajectory parameters." },
            { level: "CRIT", color: "text-red-500", msg: "Packet drop detected. Retrying connection." },
            { level: "INFO", color: "text-blue-400", msg: "Telemetry sync normal." },
            { level: "WARN", color: "text-yellow-400", msg: "Bandwidth utilization peaking at 89%." },
            { level: "INFO", color: "text-blue-400", msg: "Cryptographic keys verified." }
        ];

        function addLog() {
            const now = new Date();
            // Timestamp detail hingga milidetik
            const time = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}.${String(now.getMilliseconds()).padStart(3, '0')}`;
            
            const log = logData[Math.floor(Math.random() * logData.length)];
            // Buat kode hex acak 6 digit
            const hexId = "0x" + Math.floor(Math.random()*16777215).toString(16).toUpperCase().padStart(6, '0');
            
            const li = document.createElement("li");
            li.className = "animate-log font-mono flex flex-col pb-2 mb-2 border-b border-gray-800/50 " + (log.level === 'CRIT' ? 'border-l-2 border-l-red-500 pl-2 bg-red-500/5 rounded-r' : '');
            
            li.innerHTML = `
                <div class="flex gap-3 text-[9px] mb-0.5">
                    <span class="text-gray-500">[${time}]</span>
                    <span class="text-gray-600">${hexId}</span>
                </div>
                <div class="flex gap-2 text-[11px]">
                    <span class="${log.color} font-bold">[${log.level}]</span>
                    <span class="text-gray-300 break-words">${log.msg}</span>
                </div>
            `;
            
            const feed = document.getElementById("event-feed");
            if(feed) {
                feed.appendChild(li); // Tambahkan log baru ke bawah
                
                // BATASI MAKSIMAL 5 BARIS
                // Jika jumlah log sudah lebih dari 5, hapus elemen pertama (paling atas/tua)
                if(feed.children.length > 5 ) {
                    feed.removeChild(feed.firstChild); 
                }
                
                // Auto-scroll ke bawah agar log terbaru selalu terlihat
                feed.scrollTop = feed.scrollHeight; 
            }
        }

        // ==========================================
        // DATABASE USER & KOMENTAR (Lebih Bervariasi)
        // ==========================================
        const dummyUsers = [
            "AstroFan99", "KoreaSpaceAgency", "OrbitalGeek", "Dev_Null", "StarWatcher",
            "ElonFanboy", "NASA_JPL_Bot", "ArbaLabs_Eng", "GalacticSurfer", "IndoSpace",
            "TechBro_01", "OrbitWatcher", "CodeNinja", "Stella_X", "MarsColonist",
            "Sat_Tracker", "Ping_1ms", "QuantumMechanic", "SkyGazer_ID", "Hadi_Space"
        ];

        const dummyChats = [
            "Let's goooo 🚀", 
            "Is the telemetry live?", 
            "ArbaLabs architecture is crazy clean ✨", 
            "Dayum, look at that trajectory!", 
            "Godspeed! ☄️",
            "Telemetry looks nominal.",
            "Waiting for stage 1 preparation...",
            "Are those real-time coordinates?",
            "UI is so clean!",
            "Go ArbaLabs! 🇮🇩",
            "Ping 12ms, connection is super stable.",
            "Can't wait for the launch! 👨‍🚀",
            "Data feed is rendering perfectly.",
            "Woah, the orbit path is perfectly calculated.",
            "System temperature is green.",
            "Is Node X2 handling the thermal data?",
            "Beautiful interface.",
            "Standing by for payload verification.",
            "Amazing work from the engineering team!",
            "Aliens are watching this stream 👽",
            "Hype hype hype 🔥"
        ];
        function addChat(customMsg = null, user = null) {
            const username = user || dummyUsers[Math.floor(Math.random() * dummyUsers.length)];
            const text = customMsg || dummyChats[Math.floor(Math.random() * dummyChats.length)];
            const div = document.createElement("div");
            div.innerHTML = `<span class="text-gray-400 font-bold">${username}:</span> <span class="text-gray-200">${text}</span>`;
            const chatFeed = document.getElementById("chat-feed");
            if(chatFeed) {
                chatFeed.appendChild(div);
                if (chatFeed.children.length > 3) chatFeed.removeChild(chatFeed.firstChild);
                chatFeed.scrollTop = chatFeed.scrollHeight; 
            }
        }
        function sendEmote(emote) { addChat(emote, "Guest_You"); }
        // Fungsi untuk menangkap teks ketikan dan mengirimnya ke chat
        function sendCustomChat() {
            const inputField = document.getElementById('chat-input');
            const message = inputField.value.trim(); // Ambil teks dan hilangkan spasi kosong di awal/akhir
            
            // Hanya kirim jika kotaknya tidak kosong
            if (message !== "") {
                addChat(message, "Guest_You"); // Kirim sebagai Guest_You
                inputField.value = "";         // Kosongkan kembali kolom ketikan setelah terkirim
            }
        }

        // Fungsi agar tombol 'Enter' di keyboard juga bisa berfungsi untuk mengirim
        function handleChatEnter(event) {
            if (event.key === "Enter") {
                sendCustomChat();
            }
        }

        // ==========================================
        // 5. COUNTDOWN & LAUNCH SEQUENCE ENGINE
        // ==========================================
        let launchDate = new Date("Sep 1, 2026 00:00:00").getTime(); 
        const format = (num) => num.toString().padStart(2, '0');
        let isLaunched = false; // Status apakah roket sudah meluncur
        let launchStage = 'standby'; // VARIABEL BARU: 'standby', 'launch', 'orbiting'

        // Fungsi Tombol Skip (Memotong waktu jadi sisa 10 detik)
        function skipCountdown() {
            if(!isLaunched) {
                // Set target waktu = Waktu saat ini + 10 detik
                launchDate = new Date().getTime() + (10 * 1000); 
            }
        }

        const countdownInterval = setInterval(() => {
            const distance = launchDate - new Date().getTime();
            
            if (distance > 0) {
                // Normal Countdown (Angka terus berjalan mundur)
                document.getElementById("cd-days").innerText = format(Math.floor(distance / (1000 * 60 * 60 * 24)));
                document.getElementById("cd-hours").innerText = format(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
                document.getElementById("cd-minutes").innerText = format(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
                document.getElementById("cd-seconds").innerText = format(Math.floor((distance % (1000 * 60)) / 1000));
            } else if (distance <= 0 && !isLaunched) {
                // WAKTU HABIS (MENYENTUH ANGKA 0) - TRIGGER PELUNCURAN!
                isLaunched = true;
                
                // 1. Kunci angka di 0 agar tidak jadi minus (-)
                document.getElementById("cd-days").innerText = "00";
                document.getElementById("cd-hours").innerText = "00";
                document.getElementById("cd-minutes").innerText = "00";
                document.getElementById("cd-seconds").innerText = "00";
                
                // 2. Jalankan fungsi ledakan visual
                triggerLaunchSequence();
            }
        }, 1000);

        // Fungsi yang mengatur semua efek visual saat roket meluncur
        function triggerLaunchSequence() {
            launchStage = 'launch'; // 1. Fase Lepas Landas (0 detik)

            // A. Ubah warna teks Countdown menjadi Merah Menyala
            const cdText = document.getElementById("countdown-text");
            if(cdText) {
                cdText.classList.remove("text-gray-100");
                cdText.classList.add("text-red-500", "animate-pulse", "drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]");
            }

            // B. Efek Layar Berkedip Merah (Red Alert)
            const mediaContainer = document.getElementById("media-container");
            if(mediaContainer) {
                mediaContainer.classList.add("shadow-[inset_0_0_100px_rgba(255,0,0,0.5)]");
            }
            
            // C. Ubah Gambar Roket menjadi Gambar Meluncur
            const rocketImg = document.getElementById("rocket-cam-img");
            if(rocketImg) {
                rocketImg.src = "/images/rocket-launch.jpg"; 
                rocketImg.classList.add("animate-[ping_0.5s_ease-in-out_1]"); 
            }

            // D. Geser Timeline ke Launch Phase
            const timeline = document.getElementById("mission-timeline");
            if(timeline) {
                timeline.innerHTML = `
                    Timeline: <span class="text-gray-500">Waiting Countdown Launch > </span> 
                    <span class="text-red-400 font-bold text-sm drop-shadow-[0_0_8px_rgba(248,113,113,0.9)] animate-pulse uppercase tracking-widest">Launch Phase</span> 
                    <span class="text-gray-500"> > Starting Orbiting Earth > Success</span>
                `;
            }

            logLiftoffEvent();

            // ========================================================
            // TIMEOUT 1: 5 Detik Setelah Liftoff -> Menembus Atmosfer
            // ========================================================
            setTimeout(() => {
                launchStage = 'orbiting'; // 2. Fase Menembus Atmosfer (5 detik)
                
                if(rocketImg) {
                    rocketImg.src = "/images/rocket-atmosphere.png"; 
                    rocketImg.classList.remove("animate-[ping_0.5s_ease-in-out_1]");
                }

                if(timeline) {
                    timeline.innerHTML = `
                        Timeline: <span class="text-gray-500">Waiting Countdown Launch > Launch > </span> 
                        <span class="text-blue-400 font-bold text-sm drop-shadow-[0_0_8px_rgba(0,210,255,0.9)] animate-pulse uppercase tracking-widest">Starting Orbiting Earth</span> 
                        <span class="text-gray-500"> > Success</span>
                    `;
                }

                if(mediaContainer) {
                    mediaContainer.classList.remove("shadow-[inset_0_0_100px_rgba(255,0,0,0.5)]");
                    mediaContainer.classList.add("shadow-[inset_0_0_50px_rgba(0,210,255,0.2)]");
                }

                sendAtmosphereLog();
                updateTelemetry();

                // ========================================================
                // TIMEOUT 2: 5 Detik Berikutnya -> Sukses Mengorbit!
                // ========================================================
                setTimeout(() => {
                    launchStage = 'success'; // 3. Fase Sukses Mengorbit

                    // A. Ganti Gambar menjadi Satelit Berhasil Mengorbit Bumi
                    if(rocketImg) {
                        rocketImg.src = "/images/satellite-success.png"; 
                    }

                    // B. Update Timeline Menu ke 'Success'
                    if(timeline) {
                        timeline.innerHTML = `
                            Timeline: <span class="text-gray-500">Waiting Countdown Launch > Launch > Starting Orbiting Earth > </span> 
                            <span class="text-green-400 font-bold text-sm drop-shadow-[0_0_10px_rgba(34,197,94,0.9)] animate-pulse uppercase tracking-widest">Success Phase</span>
                        `;
                    }

                    // C. Ubah Efek Glow Layar Menjadi Hijau Indah Penanda Sukses Misi
                    if(mediaContainer) {
                        mediaContainer.classList.remove("shadow-[inset_0_0_50px_rgba(0,210,255,0.2)]");
                        mediaContainer.classList.add("shadow-[inset_0_0_50px_rgba(34,197,94,0.25)]");
                    }

                    // D. Tembakkan Log Sukses ke Terminal
                    sendSuccessOrbitLog();

                    // ========================================================
                    // PROSES INJEKSI OTOMATIS SATELIT KE-4 (ARBAEDGE-1)
                    // ========================================================
                    // Gunakan proteksi IF agar data tidak terduplikasi jika tombol skip ditekan berkali-kali
                    if (satProfiles.length < 4) {
                        
                        // 1. Gambar garis rute baru berwarna ungu neon ke peta
                        L.polyline(orbit4, { color: '#bf5af2', weight: 2, dashArray: '5, 10', opacity: 0.6 }).addTo(map);

                        // 2. Buat objek marker satelit ke-4 di peta
                        // (Pastikan kamu ada file gambar satellite4.png di folder public/images, atau samakan dengan yang ada)
                        const sat4 = L.marker(orbit4[0], { icon: createSatIcon('/images/satellitearbaedge.png') }).addTo(map);
                        
                        // 3. Pasangkan struktur jendela pop-up info singkat untuk satelit ke-4
                        sat4.bindPopup(popupTemplate('ARBAEDGE-1', '#bf5af2', 'Advanced Edge AI v5'), { autoPan: false, closeOnClick: false });
                        
                        // 4. Daftarkan detektor klik pada satelit ke-4
                        sat4.on('click', () => selectSatellite(3));

                        // 5. Suntikkan Profil Telemetri Baru (Id: 3) ke dalam sistem array dasbor
                        satProfiles.push({ 
                            id: 3, name: "ARBAEDGE-1", color: "#bf5af2", 
                            baseAlt: 15200, baseTemp: 28, baseSpd: 7.8, baseVolt: 32.4, basePwr: 195 
                        });

                        // 6. Masukkan data koordinat ke array mesin animasi agar satelit ke-4 otomatis langsung terbang bergerak
                        activeSatellites.push({ marker: sat4, coords: orbit4, currentSeg: 0, progress: 0, steps: 8 });

                        // 7. Suntikkan dataset System Status Modal baru untuk ARBAEDGE-1
                        statusDataArray.push({
                            color: 'text-[#bf5af2]', glow: 'rgba(191,90,242,0.8)',
                            verification: `<div class="space-y-3 text-[11px] text-gray-300 font-mono mb-6"><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> ArbaEdge Core Verified</div><div class="flex items-center gap-3"><span class="text-green-400 font-bold">✓</span> Next-Gen AI Payload Active</div></div>`,
                            integrity: '100%',
                            edge_ai: `<div class="space-y-2 text-[11px] font-mono mb-5"><div class="flex justify-between"><span>EDGE-CORE-V5</span> <span class="text-green-400">ONLINE</span></div></div><div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-700 text-[11px] font-mono"><div><span class="text-gray-500 block">NPU Load</span><span class="text-white">12%</span></div><div><span class="text-gray-500 block">Inference Rate</span><span class="text-white">512/s</span></div></div>`,
                            payload: `<div class="space-y-3 text-[11px] font-mono mb-6"><div class="flex justify-between border-b border-gray-800 pb-1"><span>Hyper-Cam Module</span> <span class="text-green-400">ACTIVE</span></div><div class="flex justify-between border-b border-gray-800 pb-1"><span>Neural Accelerator</span> <span class="text-green-400">ACTIVE</span></div></div>`
                        });

                        // 8. Cetak log konfirmasi satelit baru terdeteksi di terminal kiri
                        sendConstellationUpdateLog();
                    }

                    updateTelemetry(); // Cetak telemetri orbital stabil

                }, 5000); // Jeda 5 detik dari fase atmosfer ke sukses orbit 
            
            }, 5000); // <--- TAMBAHKAN BARIS INI (Penutup Timeout 1)

        }

        // --- FUNGSI HELPER UNTUK CETAK LOG TERMINAL ---
        function logLiftoffEvent() {
            const feed = document.getElementById("event-feed");
            if(feed) {
                const li = document.createElement("li");
                li.className = "animate-log font-mono flex flex-col pb-2 mb-2 border-b border-gray-800/50 border-l-2 border-l-red-500 pl-2 bg-red-500/20 rounded-r";
                li.innerHTML = `<div class="flex gap-3 text-[9px] mb-0.5"><span class="text-gray-500">[${new Date().toLocaleTimeString()}]</span></div><div class="flex gap-2 text-[11px]"><span class="text-red-500 font-bold">[CRIT]</span><span class="text-red-300 font-bold break-words uppercase">IGNITION SEQUENCE START. LIFTOFF!</span></div>`;
                feed.appendChild(li);
                if(feed.children.length > 5) feed.removeChild(feed.firstChild);
                feed.scrollTop = feed.scrollHeight;
            }
        }

        function sendAtmosphereLog() {
            const feed = document.getElementById("event-feed");
            if(feed) {
                const li = document.createElement("li");
                li.className = "animate-log font-mono flex flex-col pb-2 mb-2 border-b border-gray-800/50 border-l-2 border-l-blue-500 pl-2 bg-blue-500/10 rounded-r";
                li.innerHTML = `<div class="flex gap-3 text-[9px] mb-0.5"><span class="text-gray-500">[${new Date().toLocaleTimeString()}]</span></div><div class="flex gap-2 text-[11px]"><span class="text-blue-400 font-bold">[INFO]</span><span class="text-gray-300 break-words uppercase">MAX-Q PASSED. ENTERING UPPER ATMOSPHERE OBLIQUE TRAJECTORY.</span></div>`;
                feed.appendChild(li);
                if(feed.children.length > 5) feed.removeChild(feed.firstChild);
                feed.scrollTop = feed.scrollHeight;
            }
        }

        function sendSuccessOrbitLog() {
            const feed = document.getElementById("event-feed");
            if(feed) {
                const li = document.createElement("li");
                li.className = "animate-log font-mono flex flex-col pb-2 mb-2 border-b border-gray-800/50 border-l-2 border-l-green-500 pl-2 bg-green-500/10 rounded-r";
                li.innerHTML = `<div class="flex gap-3 text-[9px] mb-0.5"><span class="text-gray-500">[${new Date().toLocaleTimeString()}]</span></div><div class="flex gap-2 text-[11px]"><span class="text-green-400 font-bold">[OK]</span><span class="text-gray-100 font-bold break-words uppercase">ORBITAL INSERTION SUCCESSFUL. ARBALABS-1 IS ONLINE IN LEO POSITION.</span></div>`;
                feed.appendChild(li);
                if(feed.children.length > 5) feed.removeChild(feed.firstChild);
                feed.scrollTop = feed.scrollHeight;
            }
        }

        // ========================================================
        // TAMBAHKAN KODE LANGKAH 3 DI SINI
        // ========================================================
        function sendConstellationUpdateLog() {
            const feed = document.getElementById("event-feed");
            if(feed) {
                const li = document.createElement("li");
                // Desain log berwarna ungu neon khusus ArbaEdge-1
                li.className = "animate-log font-mono flex flex-col pb-2 mb-2 border-b border-gray-800/50 border-l-2 border-l-[#bf5af2] pl-2 bg-[#bf5af2]/10 rounded-r";
                li.innerHTML = `
                    <div class="flex gap-3 text-[9px] mb-0.5"><span class="text-gray-500">[${new Date().toLocaleTimeString()}]</span></div>
                    <div class="flex gap-2 text-[11px]"><span class="text-[#bf5af2] font-bold">[SYS]</span><span class="text-gray-200 font-bold break-words uppercase">CONSTELLATION UPDATED: ARBAEDGE-1 LINK ESTABLISHED IN NETWORK.</span></div>
                `;
                feed.appendChild(li);
                if(feed.children.length > 5) feed.removeChild(feed.firstChild);
                feed.scrollTop = feed.scrollHeight;
            }
        }

        // ==========================================
        // 6. MAIN ANIMATION & INTERVAL ENGINE (RESTORED)
        // ==========================================
        // Jalankan fungsi otomatis setiap beberapa detik
        setInterval(addLog, 4500);                // Munculkan log setiap 4.5 detik
        setInterval(() => addChat(), 3500);       // Munculkan chat setiap 3.5 detik
        setInterval(updateTelemetry, 2000);       // Update angka telemetri setiap 2 detik

        // Rumus Matematika untuk pergerakan halus satelit (Linear Interpolation)
        function lerp(p1, p2, t) { 
            return [p1[0] + (p2[0] - p1[0]) * t, p1[1] + (p2[1] - p1[1]) * t]; 
        }
        
        // Loop animasi satelit & pelacakan kamera (Berjalan sangat cepat: 30ms)
        setInterval(() => {
            // Gerakkan ketiga satelit
            activeSatellites.forEach(sat => {
                let nextSeg = (sat.currentSeg + 1) % sat.coords.length;
                let currentPos = lerp(sat.coords[sat.currentSeg], sat.coords[nextSeg], sat.progress / sat.steps);
                sat.marker.setLatLng(currentPos);
                sat.progress++;
                if (sat.progress >= sat.steps) {
                    sat.progress = 0;
                    sat.currentSeg = nextSeg;
                }
            });

            // Logika Auto-Tracking Kamera (Jika satelit diklik)
            if (isTracking && activeSatellites[selectedSatIndex]) {
                const currentSatPos = activeSatellites[selectedSatIndex].marker.getLatLng();
                // animate: false agar gerakan kamera mulus mengikuti interval 30ms tanpa patah-patah
                map.setView(currentSatPos, map.getZoom(), { animate: false });
            }
        }, 30);
    </script>
</body>
</html>