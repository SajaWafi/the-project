<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>location</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #edf1f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: #fff;
            border-radius: 20px;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
            overflow: hidden;
        }

        .page-title {
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            font-weight: 800;
            color: #1d567e;
            background: #fff;
            position: relative;
            z-index: 1000;
            border-bottom: 1px solid #f0f0f0;
        }

        .map-wrapper {
            position: relative;
            height: 470px;
            width: 100%;
        }

        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        .map-actions {
            position: absolute;
            right: 10px;
            top: 16px;
            z-index: 500;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .map-btn {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 12px;
            background: #7fc8ff;
            color: white;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(0,0,0,0.15);
        }

        .search-bar {
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: 10px;
            z-index: 500;
            background: rgba(255,255,255,0.96);
            border-radius: 14px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .search-left {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #9a9a9a;
            font-size: 16px;
        }

        .search-right {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9e9ee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9a9a9a;
            font-size: 12px;
        }

        .panel {
            background: #fff;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            margin-top: -6px;
            position: relative;
            z-index: 20;
            padding: 10px 14px 150px;
            min-height: 332px;
        }

        .drag-line {
            width: 58px;
            height: 5px;
            border-radius: 999px;
            background: #bcbcbc;
            margin: 0 auto 16px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 800;
            color: #333;
            margin-bottom: 8px;
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            color: #555;
            margin-bottom: 4px;
        }

        .green-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #14c414;
            display: inline-block;
        }

        .status-value {
            font-size: 14px;
            color: #555;
            margin-bottom: 14px;
        }

        .bottom-card {
            background: #f3f3f3;
            border-radius: 20px;
            padding: 14px 10px;
            margin-top: 18px;
        }

        .card-items {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }

        .card-item {
            text-align: center;
        }

        .circle-icon {
            width: 60px;
            height: 60px;
            background: #e6e6e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 6px;
        }

        .circle-icon.active {
            background: #2f80ed;
            color: white;
        }

        .label {
            font-size: 16px;
            font-weight: 600;
            color: #000;
        }

        .sub {
            font-size: 14px;
            color: #777;
        }

        .bottom-nav {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 64px;
            background: #2f80ed;
            border-radius: 0 0 20px 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1000;
        }

        .nav-item {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(255,255,255,0.65);
            transition: 0.2s;
            text-decoration: none;
        }

        .nav-svg {
            width: 22px;
            height: 22px;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.18);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .leaflet-control-attribution {
            font-size: 10px;
        }

        .custom-marker {
            width: 18px;
            height: 18px;
            background: #18b7b0;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 0 0 3px rgba(24,183,176,0.25);
        }
    </style>
</head>
<body>
    <div class="phone">

        <div class="page-title">location</div>

        <div class="map-wrapper">
            <div id="map"></div>

            <div class="map-actions">
                <button class="map-btn" id="locateBtn">📍</button>
            </div>

            <div class="search-bar">
                <div class="search-left">
                    <span>🔍</span>
                    <span>Search Maps</span>
                </div>
                <div class="search-right">AA</div>
            </div>
        </div>

        <div class="panel">
            <div class="drag-line"></div>

            <div class="section-title">Child Status</div>
            <div class="status-row">
                <span class="green-dot"></span>
                <span>Inside Safe Zone</span>
            </div>
            <div class="status-value">Last update: 10 sec</div>

            <div class="bottom-card">
                <div class="card-items">
                    <div class="card-item">
                        <div class="circle-icon active">🏠</div>
                        <div class="label">Home</div>
                    </div>

                    <div class="card-item">
                        <div class="circle-icon">🏫</div>
                        <div class="label">School</div>
                    </div>

                    <div class="card-item">
                        <a herf='#' class="circle-icon">+</a>
                        <div class="label">Add</div>
                        <div class="sub">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-nav">
            <a href="{{ route('parents.doctors') }}" class="nav-item {{ request()->routeIs('parents.doctors') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M6 4v5a6 6 0 0 0 12 0V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 15v2a4 4 0 0 0 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="18" cy="19" r="2" fill="currentColor"/>
                </svg>
            </a>

            <a href="{{ route('parents.notifications') }}" class="nav-item {{ request()->routeIs('parents.notifications') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>

            <a href="{{ route('parents.home') }}" class="nav-item {{ request()->routeIs('parents.home') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M4 10.5 12 4l8 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 10v9h10v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </a>

            <a href="{{ route('parents.reports') }}" class="nav-item {{ request()->routeIs('parents.reports') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <rect x="6" y="4" width="12" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>

            <a href="{{ route('parents.location') }}" class="nav-item {{ request()->routeIs('parents.location') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <circle cx="12" cy="10" r="2.5" fill="currentColor"/>
                </svg>
            </a>
        </div>
    </div>

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
    </script>

    <script>
        let map;
        let userMarker;
        let safeZoneCircle;

        const defaultLat = 32.8872;
        const defaultLng = 13.1913;
        const safeRadius = 150;

        function createCustomIcon() {
            return L.divIcon({
                className: '',
                html: '<div class="custom-marker"></div>',
                iconSize: [18, 18],
                iconAnchor: [9, 9]
            });
        }

        function initMap(lat = defaultLat, lng = defaultLng) {
            map = L.map('map', {
                zoomControl: false
            }).setView([lat, lng], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            userMarker = L.marker([lat, lng], {
                icon: createCustomIcon()
            }).addTo(map);

            safeZoneCircle = L.circle([lat, lng], {
                radius: safeRadius,
                color: '#37d6c6',
                weight: 2,
                fillColor: '#55d7d0',
                fillOpacity: 0.18
            }).addTo(map);
        }

        function updateLocation(lat, lng) {
            map.setView([lat, lng], 17);

            if (userMarker) userMarker.setLatLng([lat, lng]);
            if (safeZoneCircle) safeZoneCircle.setLatLng([lat, lng]);
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported in this browser.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    updateLocation(position.coords.latitude, position.coords.longitude);
                },
                function() {
                    alert('Unable to fetch current location.');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        document.addEventListener('DOMContentLoaded', function() {
            initMap();

            document.getElementById('locateBtn').addEventListener('click', function() {
                getCurrentLocation();
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        updateLocation(position.coords.latitude, position.coords.longitude);
                    },
                    function() {}
                );
            }
        });
    </script>
</body>
</html>