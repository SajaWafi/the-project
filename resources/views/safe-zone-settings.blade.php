<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Location Alerts') }}</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { display: flex; justify-content: center; align-items: center; background: #edf1f4; min-height: 100vh; font-family: Arial, sans-serif; padding: 20px; }
        
        .mobile-screen { width: 390px; height: 844px; max-width: 100%; max-height: 95vh; border-radius: 30px; overflow-y: auto; overflow-x: hidden; position: relative; background: #f9f9f9; box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14); scrollbar-width: none; }
        .mobile-screen::-webkit-scrollbar { display: none; }
        .mobile-screen::before { content: ""; position: absolute; inset: 0; background-image: url('{{ asset('images/bg.png') }}'); background-size: 165% 100%; background-position: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }} bottom; opacity: 0.9; z-index: 0; pointer-events: none; }
        
        .content { position: relative; z-index: 1; padding: 16px 14px 24px; min-height: 100%; }
        
        .header { display: flex; justify-content: center; align-items: center; position: relative; margin-bottom: 14px; min-height: 44px; }
        
        .back-btn { position: absolute; inset-inline-start: 0; border: none; background: transparent; cursor: pointer; color: #2f80ed; display: flex; align-items: center; justify-content: center; }
        .back-btn svg { width: 24px; height: 24px; transform: scaleX({{ app()->getLocale() == 'ar' ? '-1' : '1' }}); }
        
        .page-title { font-size: 22px; font-weight: 800; color: #1f5b87; }
        .logo { position: absolute; inset-inline-end: 0; width: 100px; height: 100px; object-fit: contain; }
        
        .safezone-sheet { width: 100%; background: #f7f7f7; border-radius: 18px; padding: 14px 14px 18px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08); }
        .safezone-title { font-size: 16px; font-weight: 800; color: #111; margin-bottom: 4px; }
        .safezone-text { font-size: 13px; color: #666; line-height: 1.35; margin-bottom: 10px; }
        
        .map-box { position: relative; width: 100%; height: 295px; border-radius: 6px; overflow: hidden; margin-bottom: 10px; background: #dbeafe; z-index: 1; }
        #realMap { width: 100%; height: 100%; }
        
        .radius-label { font-size: 14px; font-weight: 500; color: #202020; margin-bottom: 8px; direction: ltr; text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}; }
        
        .slider-wrap { margin-bottom: 14px; }
        .radius-slider { width: 100%; appearance: none; -webkit-appearance: none; height: 3px; background: #9bb8ee; border-radius: 999px; outline: none; }
        .radius-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 10px; height: 10px; border-radius: 50%; background: #2f80ed; cursor: pointer; box-shadow: 0 0 0 3px rgba(47, 128, 237, 0.12); }
        .slider-labels { display: flex; justify-content: space-between; font-size: 12px; color: #666; margin-top: 6px; direction: ltr; }
        
        .location-input { width: 100%; height: 34px; border: none; outline: none; border-radius: 999px; background: #e7e7e7; padding: 0 14px; font-size: 13px; color: #333; margin-bottom: 10px; }
        
        .saved-title { font-size: 14px; font-weight: 700; color: #202020; margin-bottom: 8px; }
        
        .saved-card { background: #fff; border-radius: 14px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.06); margin-bottom: 10px; }
        .saved-name { font-size: 15px; font-weight: 500; color: #202020; margin-bottom: 2px; }
        .saved-sub { font-size: 12px; color: #888; direction: ltr; text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}; }
        
        .trash-btn { border: none; background: transparent; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .trash-btn svg { width: 18px; height: 18px; }
        
        .safezone-actions { display: flex; justify-content: center; gap: 14px; margin-top: 12px; }
        .safezone-btn { min-width: 92px; height: 34px; border: none; border-radius: 999px; font-size: 14px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.06); }
        .safezone-reset { background: #e5e7eb; color: #111; }
        .safezone-save { background: #bcecdf; color: #111; }
        
        .alert-message { font-size: 13px; color: #14c414; margin-bottom: 10px; text-align: center; font-weight: bold; }
        
        .leaflet-control-locate { background-color: #fff; border-radius: 8px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.2); transition: 0.2s; }
        .leaflet-control-locate:hover { background-color: #f0f0f0; }

        .search-wrapper { position: relative; width: 100%; margin-bottom: 15px; z-index: 9999; }
        .search-bar { background: #fff; border-radius: 14px; padding: 10px 14px; display: flex; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.06); width: 100%; border: 1px solid #eaeaea; }
        .search-left { display: flex; align-items: center; gap: 10px; flex: 1; }
        .search-icon { width: 18px; height: 18px; color: #2f80ed; display: block; cursor: pointer; }
        .search-input { border: none; outline: none; background: transparent; width: 100%; font-size: 13px; color: #333; }
        .search-input::placeholder { color: #aaa; }
        
        .search-results-popup { position: absolute; top: 100%; left: 0; right: 0; margin-top: 8px; background: #fff; border-radius: 14px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); max-height: 200px; overflow-y: auto; display: none; flex-direction: column; border: 1px solid #f0f0f0; }
        .search-result-item { padding: 12px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; cursor: pointer; text-align: start; color: #333; }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-item:hover { background: #f0f8ff; color: #2f80ed; font-weight: bold; }

        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(2px); 
            border-radius: 30px;
            z-index: 99999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding-bottom: 20px;
        }
        .modal-overlay.show { opacity: 1; pointer-events: auto; }
        
        .custom-modal {
            background: #fff;
            padding: 24px 20px;
            text-align: center;
            border-radius: 20px;
            width: calc(100% - 40px);
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transform: translateY(50px);
            transition: transform 0.3s ease;
        }
        .modal-overlay.show .custom-modal { transform: translateY(0); }
        
        .modal-title-custom { font-size: 16px; font-weight: 800; color: #111; margin-bottom: 8px; }
        .modal-desc-custom { font-size: 12px; color: #666; margin-bottom: 20px; }
        .modal-btns-row { display: flex; justify-content: center; gap: 12px; }
        .modal-action-btn { padding: 8px 20px; border-radius: 999px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; min-width: 90px; }
        
        .btn-cancel { background: #bcecdf; color: #111; }
        .btn-confirm { background: #2f80ed; color: #fff; }
    </style>
</head>
<body>

    <script>
        // تعريف متغيرات الترجمة للـ JS
        const jsTranslations = {
            searchEmpty: "{{ __('Please write at least two letters to search.') }}",
            searching: "{{ __('Searching...') }}",
            noResults: "{{ __('No results were found. Please check the region name.') }}",
            searchError: "{{ __('An error occurred in connecting to the search service.') }}",
            waitMap: "{{ __('Please wait for the map to load.') }}",
            enterName: "{{ __('Please write the name of the area (e.g., house)') }}",
            locationError: "{{ __('Your location could not be determined. Please enable location services (GPS) and grant permission to your browser.') }}",
            locationNotSupported: "{{ __('Your browser does not support location services.') }}"
        };
    </script>

    <div class="mobile-screen">
        <div class="content">
            <div class="header">
               <button class="back-btn" onclick="window.history.back()" type="button" aria-label="{{ __('Back') }}">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="page-title">{{ __('Location Alerts') }}</div>
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Taif">
            </div>

            <div class="safezone-sheet">
                @if ($errors->any())
                <div style="background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 13px;">
                    <ul style="margin-inline-start: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if(session('success'))
                    <div class="alert-message">{{ session('success') }}</div>
                @endif

                <div class="safezone-title">{{ __('Select Location') }}</div>
                <div class="safezone-text">
                    {{ __('Select a safe zone. You will be notified if your child leaves this area.') }}
                </div>

                <div class="map-box">
                    <div id="realMap"></div>
                </div>

                <div class="search-wrapper">
                    <div class="search-bar">
                        <div class="search-left">
                            <svg class="search-icon" viewBox="0 0 24 24" fill="none" onclick="searchMapLocation()">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.5"/>
                                <path d="M20 20L17 17" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                            </svg>
                            <input type="text" id="mapSearchInput" class="search-input" placeholder="{{ __('Search for a place (e.g. Tripoli)...') }}" autocomplete="off">
                        </div>
                    </div>
                    <div id="searchResults" class="search-results-popup"></div>
                </div>
                
                <form id="zoneForm" action="{{ route('safe.zone.store') }}" method="POST" onsubmit="return prepareFormData();">
                    @csrf
                    <input type="hidden" name="center_latitude" id="inputLat">
                    <input type="hidden" name="center_longitude" id="inputLng">
                    <input type="hidden" name="radius_meters" id="inputRadius" value="100">

                    <div class="radius-label">
                        @if(app()->getLocale() == 'ar')
                            <span id="radiusValue">100</span>m :{{ __('Safe Zone Radius:') }}
                        @else
                            {{ __('Safe Zone Radius:') }} <span id="radiusValue">100</span>m
                        @endif
                    </div>

                    <div class="slider-wrap">
                        <input type="range" min="50" max="200" step="50" value="100" id="radiusSlider" class="radius-slider">
                        <div class="slider-labels">
                            <span>50m</span>
                            <span>100m</span>
                            <span>200m</span>
                        </div>
                    </div>

                    <input type="text" name="zone_name" id="zoneName" class="location-input" placeholder="{{ __('Enter location name (e.g Home)') }}" required>

                    <div class="safezone-actions">
                        <button type="button" class="safezone-btn safezone-reset" onclick="resetSafeZone()">{{ __('Reset') }}</button>
                        <button type="submit" class="safezone-btn safezone-save">{{ __('Save') }}</button>
                    </div>
                </form>

                <div class="saved-title" style="margin-top: 20px;">{{ __('Saved Locations') }}</div>

                @forelse($safeZones as $zone)
                    <div class="saved-card" style="cursor: pointer;" onclick="viewSavedZone({{ $zone->center_latitude }}, {{ $zone->center_longitude }}, {{ $zone->radius_meters }})">
                        <div class="saved-info">
                            <div class="saved-name">{{ $zone->zone_name }}</div>
                            <div class="saved-sub">
                                @if(app()->getLocale() == 'ar')
                                    {{ $zone->radius_meters }}m :{{ __('Radius:') }}
                                @else
                                    {{ __('Radius:') }} {{ $zone->radius_meters }}m
                                @endif
                            </div>
                        </div>

                        <form id="delete-form-{{ $zone->id }}" action="{{ route('safe.zone.destroy', $zone->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="trash-btn" onclick="event.stopPropagation(); openDeleteModal('{{ $zone->id }}');">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 7h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9 7V5h6v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M8 7l.6 11h6.8L16 7" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="safezone-text" style="text-align: center; margin-top: 10px;">{{ __('No safe zones saved yet.') }}</div>
                @endforelse

            </div>
        </div>

        <div class="modal-overlay" id="customDeleteModal">
            <div class="custom-modal">
                <div class="modal-title-custom">{{ __('Delete Location') }}</div>
                <div class="modal-desc-custom">{{ __('Are you sure you want to delete this safe zone?') }}</div>
                <div class="modal-btns-row">
                    <button class="modal-action-btn btn-cancel" onclick="closeDeleteModal()">{{ __('Cancel') }}</button>
                    <button class="modal-action-btn btn-confirm" onclick="confirmDeleteAction()">{{ __('Yes, Delete') }}</button>
                </div>
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

            L.Control.Locate = L.Control.extend({
                onAdd: function(map) {
                    var btn = L.DomUtil.create('div', 'leaflet-control-locate');
                    btn.innerHTML = `<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#2f80ed" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>`;
                    btn.title = "My location";

                    L.DomEvent.disableClickPropagation(btn);
                    btn.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (navigator.geolocation) {
                            btn.style.opacity = '0.5'; 
                            navigator.geolocation.getCurrentPosition(
                                function(pos) {
                                    btn.style.opacity = '1';
                                    let currentLat = pos.coords.latitude;
                                    let currentLng = pos.coords.longitude;
                                    let newLatLng = new L.LatLng(currentLat, currentLng);

                                    map.flyTo(newLatLng, 16, { animate: true, duration: 1.5 });
                                    if (marker) marker.setLatLng(newLatLng);
                                    if (circle) circle.setLatLng(newLatLng);
                                    document.getElementById('inputLat').value = currentLat;
                                    document.getElementById('inputLng').value = currentLng;
                                },
                                function(error) {
                                    btn.style.opacity = '1';
                                    alert(jsTranslations.locationError);
                                },
                                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 } 
                            );
                        } else {
                            alert(jsTranslations.locationNotSupported);
                        }
                    };
                    return btn;
                }
            });

            new L.Control.Locate({ position: 'topright' }).addTo(map);

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
                
                document.getElementById('inputLat').value = e.latlng.lat;
                document.getElementById('inputLng').value = e.latlng.lng;
            });

            setTimeout(() => { map.invalidateSize(); }, 200);
        }

        async function searchMapLocation() {
            const query = document.getElementById('mapSearchInput').value.trim();
            const resultsDiv = document.getElementById('searchResults');
            
            if (query.length < 2) {
                resultsDiv.style.display = 'block';
                resultsDiv.innerHTML = `<div style="padding: 10px; font-size: 12px; color: #ef4444; text-align: center;">${jsTranslations.searchEmpty}</div>`;
                
                setTimeout(() => {
                    resultsDiv.style.display = 'none';
                    resultsDiv.innerHTML = '';
                }, 3000);
                
                return;
            }

            resultsDiv.style.display = 'block';
            resultsDiv.innerHTML = `<div style="padding: 10px; font-size: 12px; color: #666; text-align: center;">${jsTranslations.searching}</div>`;
            
            try { 
                const langCode = '{{ app()->getLocale() }}'; // 💡 البحث بلغة النظام
                const apiUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=ly&accept-language=${langCode}`;
                const response = await fetch(apiUrl);
                const data = await response.json();
                
                if (data.length === 0) {
                    resultsDiv.innerHTML = `<div style="padding: 10px; font-size: 12px; color: #ef4444; text-align: center;">${jsTranslations.noResults}</div>`;
                    return;
                }

                resultsDiv.innerHTML = '';
                data.forEach(place => {
                    const item = document.createElement('div');
                    item.className = 'search-result-item';
                    
                    let displayName = place.display_name;
                    item.textContent = displayName;
                    
                    item.onclick = function() {
                        const lat = parseFloat(place.lat);
                        const lng = parseFloat(place.lon);
                        const newLatLng = new L.LatLng(lat, lng);

                        if (map) map.flyTo(newLatLng, 15, { animate: true, duration: 1.5 });
                        if (marker) marker.setLatLng(newLatLng);
                        if (circle) circle.setLatLng(newLatLng);

                        document.getElementById('inputLat').value = lat;
                        document.getElementById('inputLng').value = lng;
                        
                        let shortName = place.name || displayName.split(',')[0];
                        document.getElementById('zoneName').value = shortName;
                        
                        resultsDiv.style.display = 'none';
                    };
                    resultsDiv.appendChild(item);
                });

            } catch (error) {
                console.error("Search error:", error);
                resultsDiv.innerHTML = `<div style="padding: 10px; font-size: 12px; color: #ef4444; text-align: center;">${jsTranslations.searchError}</div>`;
            }
        }

        document.addEventListener('click', function(e) {
            const resultsDiv = document.getElementById('searchResults');
            const searchInput = document.getElementById('mapSearchInput');
            const searchBtn = document.querySelector('.search-btn');
            
            if (e.target !== searchInput && e.target !== searchBtn && !searchBtn?.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        });

        document.getElementById('mapSearchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                searchMapLocation();
            }
        });

        function viewSavedZone(lat, lng, radius) {
            let newLatLng = new L.LatLng(lat, lng);
            
            if (marker) marker.setLatLng(newLatLng);
            if (circle) {
                circle.setLatLng(newLatLng);
                circle.setRadius(radius);
            }

            radiusSlider.value = radius;
            radiusValue.textContent = radius;

            if (map) {
                map.flyTo(newLatLng, 16, { animate: true, duration: 1.5 });
            }
            
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
            document.getElementById('inputRadius').value = this.value;
        });
        
        function resetSafeZone() {
            radiusSlider.value = 100;
            updateSafeCircle(100);
            document.getElementById('inputRadius').value = 100;
            if (map && marker) {
                map.setView(marker.getLatLng(), 15);
            }
        }

        function prepareFormData() {
            if (!marker) {
                alert(jsTranslations.waitMap);
                return false;
            }
            const nameInput = document.getElementById('zoneName').value.trim();
            if (!nameInput) {
                alert(jsTranslations.enterName);
                return false;
            }

            const latlng = marker.getLatLng();
            
            document.getElementById('inputLat').value = latlng.lat;
            document.getElementById('inputLng').value = latlng.lng;
            document.getElementById('inputRadius').value = radiusSlider.value;

            return true;
        }

        getUserLocation();

        let currentDeleteFormId = null;

        function openDeleteModal(zoneId) {
            currentDeleteFormId = 'delete-form-' + zoneId;
            const modal = document.getElementById('customDeleteModal');
            modal.classList.add('show');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('customDeleteModal');
            modal.classList.remove('show');
            currentDeleteFormId = null;
        }

        function confirmDeleteAction() {
            if (currentDeleteFormId) {
                document.getElementById(currentDeleteFormId).submit();
            }
        }
    </script>
</body>
</html>