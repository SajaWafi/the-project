<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Tracking</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background: #edf1f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
       .phone { width: 390px; height: 844px; background: #fff; border-radius: 20px; position: relative; box-shadow: 0 12px 30px rgba(0,0,0,0.35); overflow: hidden; display: flex; flex-direction: column; }
       .page-title { flex-shrink: 0; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 21px; font-weight: 800; color: #1d567e; background: #fff; position: relative; z-index: 1000; border-bottom: 1px solid #f0f0f0; }
        .back-btn { position: absolute; left: 12px; display: flex; align-items: center; justify-content: center; color: #2f80ed; text-decoration: none; }
        .back-btn svg { width: 24px; height: 24px; display: block; }
        .logo { position: absolute; right: 10px; width: 38px; height: 38px; object-fit: contain; }
        
        .map-wrapper { flex-shrink: 0; position: relative; height: 470px; width: 100%; }
        #map { height: 100%; width: 100%; z-index: 1; }
        
        .map-actions { position: absolute; right: 10px; top: 16px; z-index: 500; display: flex; flex-direction: column; gap: 10px; }
        .map-btn { width: 42px; height: 42px; border: none; border-radius: 12px; background: #7fc8ff; color: white; font-size: 18px; cursor: pointer; box-shadow: 0 6px 14px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; }
        .map-icon-svg { width: 20px; height: 20px; color: #ffffff; display: block; }
        
        /* 💡 تنسيقات خانة البحث */
        .search-wrapper { position: absolute; left: 10px; right: 10px; bottom: 15px; z-index: 1000; }
        .search-bar { background: rgba(255,255,255,0.96); border-radius: 14px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0,0,0,0.12); width: 100%; }
        .search-left { display: flex; align-items: center; gap: 8px; flex: 1; }
        .search-icon { width: 18px; height: 18px; color: #2f80ed; display: block; cursor: pointer; }
        .search-input { border: none; outline: none; background: transparent; width: 100%; font-size: 14px; color: #333; text-align: left; direction: ltr; }
        .search-input::placeholder { color: #9a9a9a; }
        .search-results-popup { position: absolute; bottom: 100%; left: 0; right: 0; margin-bottom: 8px; background: #fff; border-radius: 14px; box-shadow: 0 -4px 15px rgba(0,0,0,0.15); max-height: 200px; overflow-y: auto; display: none; flex-direction: column; border: 1px solid #f0f0f0; }
        .search-result-item { padding: 12px; font-size: 13px; border-bottom: 1px solid #f0f0f0; cursor: pointer; text-align: right; color: #333; }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-item:hover { background: #f0f8ff; color: #2f80ed; font-weight: bold; }

        .panel { flex: 1; background: #fff; border-top-left-radius: 18px; border-top-right-radius: 18px; margin-top: -12px; position: relative; z-index: 20; padding: 15px 14px 90px; overflow-y: auto; scrollbar-width: none; }
        .panel::-webkit-scrollbar { display: none; }
        .drag-line { width: 58px; height: 5px; border-radius: 999px; background: #bcbcbc; margin: 0 auto 16px; }
        .section-title { font-size: 15px; font-weight: 800; color: #333; margin-bottom: 8px; }
        
        .green-dot { width: 12px; height: 12px; border-radius: 50%; background: #14c414; display: inline-block; }
        .status-alert { display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 10px; font-weight: 700; font-size: 14px; margin-bottom: 16px; width: 100%; border: 1px solid transparent; }
        .status-alert.safe { background: #e8f9e8; color: #14c414; border-color: #bbf0bb; }
        .status-alert.danger { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

        .info-grid { display: flex; flex-direction: column; gap: 10px; margin-bottom: 15px; }
        .info-row { display: flex; align-items: center; gap: 12px; background: #f8f9fa; padding: 12px 14px; border-radius: 12px; border: 1px solid #eee; }
        .info-icon { width: 36px; height: 36px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2f80ed; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
        .info-icon svg { width: 18px; height: 18px; }
        .info-text { display: flex; flex-direction: column; }
        .info-label { font-size: 11px; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-val { font-size: 15px; color: #222; font-weight: 800; margin-top: 3px; }
        
        .bottom-card { background: #f3f3f3; border-radius: 20px; padding: 14px 10px; margin-top: 18px; }
        .card-items { display: flex; justify-content: flex-start; align-items: flex-start; overflow-x: auto; gap: 15px; padding-bottom: 5px; scrollbar-width: none; }
        .card-item { text-align: center; cursor: pointer; min-width: 65px; }
        .circle-icon { width: 60px; height: 60px; background: #e6e6e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 6px; color: #555; }
        .circle-icon.active { background: #2f80ed; color: white; }
        .panel-icon-svg { width: 26px; height: 26px; display: block; color: currentColor; }
        .label { font-size: 16px; font-weight: 600; color: #000; }
        .sub { font-size: 14px; color: #777; }
        
        .bottom-nav { position: absolute; left: 0; right: 0; bottom: 0; height: 64px; background: #2f80ed; border-radius: 24px 24px 0 0; display: flex; justify-content: space-around; align-items: center; z-index: 1000; }
        .nav-item { width: 48px; height: 48px; border-radius: 14px; display: flex; justify-content: center; align-items: center; color: rgba(255,255,255,0.65); transition: 0.2s; text-decoration: none; }
        .nav-svg { width: 22px; height: 22px; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #ffffff; transform: translateY(-2px); }
        
        .leaflet-control-attribution { display: none !important; }
        .custom-marker { width: 18px; height: 18px; background: #18b7b0; border: 3px solid white; border-radius: 50%; box-shadow: 0 0 0 3px rgba(24,183,176,0.25); }
        .add-link { text-decoration: none; color: inherit; }
    </style>
</head>
<body>
    <div class="phone">

        <div class="page-title">
            <a href="{{ route('home') }}" class="back-btn" aria-label="Back to home">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <span>Location</span>
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
        </div>

        <div class="map-wrapper">
            <div id="map"></div>
            <div class="map-actions">
                <button class="map-btn" id="locateBtn" aria-label="Locate" title="تتبع موقع طفلي">
                    <svg class="map-icon-svg" viewBox="0 0 24 24" fill="none">
                        <path d="M12 21s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="10" r="3" fill="currentColor"/>
                    </svg>
                </button>
            </div>
            
            <div class="search-wrapper">
                <div id="searchResults" class="search-results-popup"></div>
                <div class="search-bar">
                    <div class="search-left">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" onclick="searchMapLocation()">
                            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.5"/>
                            <path d="M20 20L17 17" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                        <input type="text" id="mapSearchInput" class="search-input" placeholder="Search for a place (e.g. Tripoli)...">
                    </div>
                </div>
            </div>
        </div>

      <div class="panel">
            <div class="drag-line"></div>
            <div class="section-title">Child Status</div>

            <div class="status-alert {{ ($isSafe ?? true) ? 'safe' : 'danger' }}">
                <span class="green-dot" style="background: {{ ($isSafe ?? true) ? '#14c414' : '#ef4444' }};"></span>
                {{ ($isSafe ?? true) ? 'Inside Safe Zone' : 'Outside Safe Zone!' }}
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="info-text">
                        <span class="info-label">Last Update</span>
                        <span class="info-val">
                            @php
                                $sec = $lastUpdateSec ?? 0;
                                if ($sec < 60) {
                                    echo round($sec) . ' seconds ago';
                                } elseif ($sec < 3600) {
                                    echo round($sec / 60) . ' mins ago';
                                } else {
                                    $hours = floor($sec / 3600);
                                    $mins = round(($sec % 3600) / 60);
                                    echo $hours . ' hr ' . $mins . ' min ago';
                                }
                            @endphp
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"/><path d="M12 2v20"/><path d="M8 6l4-4 4 4"/></svg>
                    </div>
                    <div class="info-text">
                        <span class="info-label">Altitude</span>
                        <span class="info-val">{{ round($altitude ?? 0) }} meters</span>
                    </div>
                </div>
            </div>

            <div class="bottom-card">
                <div class="card-items">
                    @foreach($safeZones ?? [] as $zone)
                        <div class="card-item" onclick="focusOnZone({{ $zone->center_latitude }}, {{ $zone->center_longitude }})">
                            <div class="circle-icon">
                                <svg class="panel-icon-svg" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 21s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="10" r="3" fill="currentColor"/>
                                </svg>
                            </div>
                            <div class="label">{{ $zone->zone_name }}</div>
                        </div>
                    @endforeach

                    <a href="{{ route('safe.zone.settings') ?? '#' }}" class="card-item">
                        <div class="circle-icon">
                            <svg class="panel-icon-svg" viewBox="0 0 24 24" fill="none">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="label">Add</div>
                    </a>
                </div>
            </div>

        </div>

        <div class="bottom-nav">
            <a href="{{ route('doctors') ?? '#' }}" class="nav-item {{ request()->routeIs('parents.doctors') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M6 4v5a6 6 0 0 0 12 0V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 15v2a4 4 0 0 0 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="18" cy="19" r="2" fill="currentColor"/>
                </svg>
            </a>
            <a href="{{ route('alerts') ?? '#' }}" class="nav-item {{ request()->routeIs('parents.alerts') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>
            <a href="{{ route('home') ?? '#' }}" class="nav-item {{ request()->routeIs('parents.home') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M4 10.5 12 4l8 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 10v9h10v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </a>
            <a href="{{ route('report') ?? '#' }}" class="nav-item {{ request()->routeIs('parents.report') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <rect x="6" y="4" width="12" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>
            <a href="{{ route('location') ?? '#' }}" class="nav-item {{ request()->routeIs('parents.location') ? 'active' : 'active' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <circle cx="12" cy="10" r="2.5" fill="currentColor"/>
                </svg>
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
    let map; //يمثل الخريطة نفسها.
    let userMarker; //يمثل علامة الطفل على الخريطة.
    let safeZoneCircles = []; //مصفوفة نخزن فيها دوائر المناطق الآمنة.
    let autoFollow = true;  //الخريطة تتبع الطفل تلقائياً.

    const currentLat = {{ $latitude ?? 32.8872 }};
    const currentLng = {{ $longitude ?? 13.1913 }};
    const safeZonesData = @json($safeZones ?? []); //تحويل Safe Zones من Laravel إلى JSON.

    function createCustomIcon() {
        return L.divIcon({
            className: '',
            html: '<div class="custom-marker"></div>',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });
    }
    //إنشاء الخريطة
    function initMap(lat, lng) {
        if (map) { map.remove(); } 
    //تهيئة الخريطة وتحديد مستوى الرؤية
        map = L.map('map', { zoomControl: false }).setView([lat, lng], 17);//حددت المركز على إحداثيات الطفل الحالية

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '' 
        }).addTo(map);

        userMarker = L.marker([lat, lng], { icon: createCustomIcon() }).addTo(map);
    //المرور على جميع المناطق الآمنة.
        safeZonesData.forEach(zone => {
            let circle = L.circle([zone.center_latitude, zone.center_longitude], {
                radius: zone.radius_meters,
                color: '#37d6c6',
                weight: 2,
                fillColor: '#55d7d0',
                fillOpacity: 0.18,
                dashArray: '4,6'
            }).addTo(map);
            safeZoneCircles.push(circle);
        });
//إيقاف التتبع عند تحريك الخريطة إذا الأب حرك الخريطة بيده.
        map.on('dragstart', function() {
            autoFollow = false;
        });
    }
    //وظيفتها البحث في OpenStreetMap
    async function searchMapLocation() {
        const query = document.getElementById('mapSearchInput').value.trim();
        const resultsDiv = document.getElementById('searchResults');
        
        if (query.length < 2) {
            resultsDiv.style.display = 'flex';
            resultsDiv.innerHTML = '<div style="padding: 10px; font-size: 12px; color: #4b60ff; text-align: center;">Please enter at least two letters for your search.</div>';
            
            // إخفاء الرسالة تلقائياً بعد 3 ثوانٍ 
            setTimeout(() => {
                resultsDiv.style.display = 'none';
                resultsDiv.innerHTML = '';
            }, 3000);
    
    return;
}

        resultsDiv.style.display = 'flex';
        resultsDiv.innerHTML = '<div style="padding: 10px; font-size: 12px; color: #666; text-align: center;">Searching..</div>';

        try { //محرك بحث OpenStreetMap
            const apiUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=ly&accept-language=ar`;
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.length === 0) {
                resultsDiv.innerHTML = '<div style="padding: 10px; font-size: 12px; color: #ef4444; text-align: center;">No results were found.</div>';
                return;
            }
        //بناء القائمة المنسدلة ديناميكياً 
            resultsDiv.innerHTML = '';
            data.forEach(place => {
                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.textContent = place.display_name;
                //إذا ضغط الأب على نتيجة.
                item.onclick = function() {
                    const lat = parseFloat(place.lat);
                    const lng = parseFloat(place.lon);
                    //إيقاف التتبع
                    autoFollow = false; 
                    //الخريطة تنتقل للمكان المختار.
                    if (map) map.flyTo([lat, lng], 16, { animate: true, duration: 1.5 });
                    
                    document.getElementById('mapSearchInput').value = place.name || place.display_name.split(',')[0];
                    resultsDiv.style.display = 'none';
                };
                resultsDiv.appendChild(item);
            });

        } catch (error) {
            console.error("Search error:", error);
            resultsDiv.innerHTML = '<div style="padding: 10px; font-size: 12px; color: #ef4444; text-align: center;">A communication error has occurred .</div>';
        }
    }
        //الضغط بالخارج للإغلاق
    document.addEventListener('click', function(e) {
        const resultsDiv = document.getElementById('searchResults');
        const searchInput = document.getElementById('mapSearchInput');
        if (e.target !== searchInput && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });
    //البحث بزر Enter
    document.getElementById('mapSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchMapLocation();
        }
    });
    //تنقل الخريطة مباشرة إلى المنطقة الآمنة.
    function focusOnZone(lat, lng) {
        autoFollow = false; 
        if (map) {
            map.flyTo([lat, lng], 17, {
                animate: true,
                duration: 1.5
            });
        }
    }
    //المسؤول عن تشغيل الخريطة أول ما تفتح الصفحة، وبرمجة زر "تحديد موقع الطفل"  
    document.addEventListener('DOMContentLoaded', function() {
        initMap(currentLat, currentLng);

        document.getElementById('locateBtn').addEventListener('click', function() {
             autoFollow = true; 
             if (map && userMarker) {
                 map.flyTo(userMarker.getLatLng(), 17, {
                     animate: true,
                     duration: 1.0
                 });
             }
        });

        setInterval(function() {
            //الجافاسكربت هنا يرسل طلب صامت (في الخلفية) للينك location.live
            fetch('{{ route("location.live") ?? "" }}')
                .then(response => response.ok ? response.json() : null)
                .then(data => {
                    if (data && data.lat && data.lng) {
                        let newLatLng = new L.LatLng(data.lat, data.lng);
                        userMarker.setLatLng(newLatLng); 
                        
                        if (autoFollow) {
                            map.setView(newLatLng, map.getZoom()); 
                        }
                    }
                }).catch(err => console.log('Live tracking paused.'));
        }, 5000);
    });
    </script>
</body>
</html>