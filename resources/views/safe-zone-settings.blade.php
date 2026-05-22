<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Zone Settings</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* (ضعي كل الـ CSS الخاص بك هنا كما هو بدون أي تغيير) */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { display: flex; justify-content: center; align-items: center; background: #edf1f4; min-height: 100vh; font-family: Arial, sans-serif; padding: 20px; }
        .mobile-screen { width: 390px; height: 844px; max-width: 100%; max-height: 95vh; border-radius: 30px; overflow-y: auto; overflow-x: hidden; position: relative; background: #f9f9f9; box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14); scrollbar-width: none; }
        .mobile-screen::-webkit-scrollbar { display: none; }
        .mobile-screen::before { content: ""; position: absolute; inset: 0; background-image: url('{{ asset('images/bg.png') }}'); background-size: 165% 100%; background-position: right bottom; opacity: 0.9; z-index: 0; pointer-events: none; }
        .content { position: relative; z-index: 1; padding: 16px 14px 24px; min-height: 100%; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .header { display: flex; justify-content: center; align-items: center; position: relative; margin-bottom: 14px; min-height: 44px; }
        .back-btn { position: absolute; left: 0; border: none; background: transparent; cursor: pointer; color: #2f80ed; display: flex; align-items: center; justify-content: center; }
        .back-btn svg { width: 24px; height: 24px; }
        .page-title { font-size: 22px; font-weight: 800; color: #1f5b87; }
        .logo { position: absolute; right: 0; width: 100px; height: 100px; object-fit: contain; }
        .safezone-sheet { width: 100%; background: #f7f7f7; border-radius: 18px; padding: 14px 14px 18px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08); }
        .safezone-title { font-size: 16px; font-weight: 800; color: #111; margin-bottom: 4px; }
        .safezone-text { font-size: 13px; color: #666; line-height: 1.35; margin-bottom: 10px; }
        .map-box { position: relative; width: 100%; height: 295px; border-radius: 6px; overflow: hidden; margin-bottom: 10px; background: #dbeafe; }
        #realMap { width: 100%; height: 100%; }
        .radius-label { font-size: 14px; font-weight: 500; color: #202020; margin-bottom: 8px; }
        .slider-wrap { margin-bottom: 14px; }
        .radius-slider { width: 100%; appearance: none; -webkit-appearance: none; height: 3px; background: #9bb8ee; border-radius: 999px; outline: none; }
        .radius-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 10px; height: 10px; border-radius: 50%; background: #2f80ed; cursor: pointer; box-shadow: 0 0 0 3px rgba(47, 128, 237, 0.12); }
        .slider-labels { display: flex; justify-content: space-between; font-size: 12px; color: #666; margin-top: 6px; }
        .location-input { width: 100%; height: 34px; border: none; outline: none; border-radius: 999px; background: #e7e7e7; padding: 0 14px; font-size: 13px; color: #333; margin-bottom: 10px; }
        .saved-title { font-size: 14px; font-weight: 700; color: #202020; margin-bottom: 8px; }
        .saved-card { background: #fff; border-radius: 14px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.06); margin-bottom: 10px; }
        .saved-name { font-size: 15px; font-weight: 500; color: #202020; margin-bottom: 2px; }
        .saved-sub { font-size: 12px; color: #888; }
        .trash-btn { border: none; background: transparent; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .trash-btn svg { width: 18px; height: 18px; }
        .safezone-actions { display: flex; justify-content: center; gap: 14px; margin-top: 12px; }
        .safezone-btn { min-width: 92px; height: 34px; border: none; border-radius: 999px; font-size: 14px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.06); }
        .safezone-reset { background: #e5e7eb; color: #111; }
        .safezone-save { background: #bcecdf; color: #111; }
        .alert-message { font-size: 13px; color: #14c414; margin-bottom: 10px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">
            <div class="header">
                <button class="back-btn" onclick="window.location.href='{{ route('parents.location') }}'" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="page-title">Location Alerts</div>
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Taif">
            </div>

            <div class="safezone-sheet">
   @if ($errors->any())
    <div style="background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 13px;">
        <ul style="margin-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                
                @if(session('success'))
                    <div class="alert-message">{{ session('success') }}</div>
                @endif

                <div class="safezone-title">Select Location</div>
                <div class="safezone-text">
                    Select a safe zone. You will be notified if your child leaves this area.
                </div>

                <div class="map-box">
                    <div id="realMap"></div>
                </div>
<form id="zoneForm" action="{{ route('safe.zone.store') }}" method="POST" onsubmit="return prepareFormData();">
                    @csrf
                    <input type="hidden" name="center_latitude" id="inputLat">
                    <input type="hidden" name="center_longitude" id="inputLng">
                    <input type="hidden" name="radius_meters" id="inputRadius" value="100">

                    <div class="radius-label">
                        Safe Zone Radius: <span id="radiusValue">100</span>m
                    </div>

                    <div class="slider-wrap">
                        <input type="range" min="50" max="200" step="50" value="100" id="radiusSlider" class="radius-slider">
                        <div class="slider-labels">
                            <span>50m</span>
                            <span>100m</span>
                            <span>200m</span>
                        </div>
                    </div>

                    <input type="text" name="zone_name" id="zoneName" class="location-input" placeholder="Enter location name (e.g Home)" required>

                    <div class="safezone-actions">
                        <button type="button" class="safezone-btn safezone-reset" onclick="resetSafeZone()">Reset</button>
                        <button type="submit" class="safezone-btn safezone-save">Save</button>
                    </div>
                </form>
                <div class="saved-title" style="margin-top: 20px;">Saved Locations</div>

                @forelse($safeZones as $zone)
                    <div class="saved-card" style="cursor: pointer;" onclick="viewSavedZone({{ $zone->center_latitude }}, {{ $zone->center_longitude }}, {{ $zone->radius_meters }})">
                        <div class="saved-info">
                            <div class="saved-name">{{ $zone->zone_name }}</div>
                            <div class="saved-sub">Radius: {{ $zone->radius_meters }}m</div>
                        </div>

                        <form action="{{ route('safe.zone.destroy', $zone->id) }}" method="POST" onsubmit="event.stopPropagation(); return confirm('Delete this safe zone?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="trash-btn" onclick="event.stopPropagation();">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 7h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9 7V5h6v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M8 7l.6 11h6.8L16 7" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="safezone-text" style="text-align: center; margin-top: 10px;">No safe zones saved yet.</div>
                @endforelse

            </div>
        </div>
    </div>

    <script>
        const radiusSlider = document.getElementById('radiusSlider');
        const radiusValue = document.getElementById('radiusValue');
        
        let map = null;
        let marker = null;
        let circle = null;

        function initMap(lat = 32.8872, lng = 13.1913) {
            if (map) { map.remove(); }

            map = L.map('realMap', { zoomControl: false }).setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(map);

            const customIcon = L.divIcon({
                html: `
                    <div style="width:22px;height:22px;display:flex;align-items:center;justify-content:center;">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none">
                            <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" fill="#2f80ed"/>
                            <circle cx="12" cy="10" r="2.5" fill="#fff"/>
                        </svg>
                    </div>
                `,
                className: '',
                iconSize: [22, 22],
                iconAnchor: [11, 22]
            });

            marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            circle = L.circle([lat, lng], {
                radius: 100,
                color: '#49d6c2',
                fillColor: '#49d6c2',
                fillOpacity: 0.20,
                weight: 3,
                dashArray: '4,6'
            }).addTo(map);

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                circle.setLatLng(e.latlng);
            });

            setTimeout(() => { map.invalidateSize(); }, 200);
        }

        // دالة عرض المكان المحفوظ على الخريطة
        function viewSavedZone(lat, lng, radius) {
            let newLatLng = new L.LatLng(lat, lng);
            
            // 1. تحريك الماركر والدائرة للمكان الجديد
            if (marker) marker.setLatLng(newLatLng);
            if (circle) {
                circle.setLatLng(newLatLng);
                circle.setRadius(radius);
            }

            // 2. تحديث شريط السحب والرقم باش يطابقوا المكان المحفوظ
            radiusSlider.value = radius;
            radiusValue.textContent = radius;

            // 3. تحريك الخريطة بنعومة (زي جوجل ماب)
            if (map) {
                map.flyTo(newLatLng, 16, {
                    animate: true,
                    duration: 1.5 // سرعة الطيران للخريطة (ثانية ونصف)
                });
            }
            
            // تحديث الحقول المخفية تحسباً لو الأب يبي يعدل ويحفظ من جديد
            document.getElementById('inputLat').value = lat;
            document.getElementById('inputLng').value = lng;
            document.getElementById('inputRadius').value = radius;
        }

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (pos) { initMap(pos.coords.latitude, pos.coords.longitude); },
                    function () { initMap(); }
                );
            } else {
                initMap();
            }
        }

        function updateSafeCircle(value) {
            radiusValue.textContent = value;
            if (circle) { circle.setRadius(Number(value)); }
        }

        radiusSlider.addEventListener('input', function () {
            updateSafeCircle(this.value);
        });

        function resetSafeZone() {
            radiusSlider.value = 100;
            updateSafeCircle(100);
            if (map && marker) {
                map.setView(marker.getLatLng(), 15);
            }
        }

        // إرسال البيانات
       // هذي الدالة تتنفذ تلقائياً لما تضغطي على زر Save
        function prepareFormData() {
            if (!marker) {
                alert("الرجاء انتظار تحميل الخريطة");
                return false; // يمنع الإرسال لو الخريطة ما فتحتش
            }

            const nameInput = document.getElementById('zoneName').value.trim();
            if (!nameInput) {
                alert("الرجاء كتابة اسم المنطقة (مثلاً: المنزل)");
                return false;
            }

            const latlng = marker.getLatLng();
            
            // تعبئة الحقول المخفية بالإحداثيات
            document.getElementById('inputLat').value = latlng.lat;
            document.getElementById('inputLng').value = latlng.lng;
            document.getElementById('inputRadius').value = radiusSlider.value;

            return true; // يسمح للارافل باستلام الفورم
        }

        getUserLocation();
    </script>

</body>
</html>