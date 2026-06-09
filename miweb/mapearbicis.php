<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Estaciones Valenbisi</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        :root { --bg-color: #121212; --text-color: #e0e0e0; --card-bg: #1e1e1e; --accent-color: #4CAF50; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); color: var(--text-color); margin: 0; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        h1 { color: #ffffff; margin-bottom: 20px; }
        .lang-selector { margin-bottom: 20px; }
        .lang-selector button { padding: 8px 16px; cursor: pointer; background: #333; color: white; border: none; border-radius: 4px; margin: 0 5px; }
        .container { width: 95%; max-width: 1200px; background-color: var(--card-bg); padding: 20px; border-radius: 12px; }
        #map { height: 600px; width: 100%; border-radius: 8px; margin-top: 20px; }
        .controls { margin-bottom: 20px; text-align: center; }
        input { padding: 10px; width: 300px; border-radius: 5px; border: 1px solid #333; background: #2d2d2d; color: white; }
        .btn-mapa { display: inline-block; margin-top: 20px; padding: 12px 24px; background-color: var(--accent-color); color: white; text-decoration: none; font-weight: bold; border-radius: 5px; }
    </style>
</head>
<body>

    <h1 id="main-title">Mapeo de Bicicletas en Valencia</h1>
    
    <div class="lang-selector">
        <button onclick="setLang('es')">Español</button>
        <button onclick="setLang('en')">English</button>
    </div>

    <div class="container">
        <div class="controls">
            <input type="text" id="filterInput" placeholder="Buscar dirección..." onkeyup="filterMarkers()">
        </div>
        
        <div id="map"></div>
        
        <div style="text-align: center;">
            <a href="index.php" class="btn-mapa" id="btn-back">Volver al listado</a>
        </div>
    </div>

    <script>
        const map = L.map('map').setView([39.47, -0.37], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

        let stations = [];
        let currentLang = localStorage.getItem('lang') || 'es';

        const i18n = {
            'es': { title: "Mapeo de Bicicletas en Valencia", placeholder: "Buscar dirección...", back: "Volver al listado", available: "Disponibles", free: "Libres", total: "Total" },
            'en': { title: "Bicycle Map in Valencia", placeholder: "Search address...", back: "Back to list", available: "Available", free: "Free", total: "Total" }
        };

        function setLang(lang) {
            currentLang = lang;
            localStorage.setItem('lang', lang);
            document.getElementById('main-title').innerText = i18n[lang].title;
            document.getElementById('filterInput').placeholder = i18n[lang].placeholder;
            document.getElementById('btn-back').innerText = i18n[lang].back;
          
            stations.forEach(s => {
                s.marker.setPopupContent(createPopupContent(s.data));
            });
        }

        function createPopupContent(d) {
            const t = i18n[currentLang];
            return `<strong>${d.address}</strong><br>${t.available}: ${d.available}<br>${t.free}: ${d.free}<br>${t.total}: ${d.total}`;
        }

        fetch('data.json')
            .then(response => response.json())
            .then(data => {
                Object.values(data).forEach(station => {
                    if (station.lat && station.lon) {
                        let circle = L.circleMarker([station.lat, station.lon], {
                            color: station.available < 5 ? 'red' : (station.available < 10 ? 'orange' : 'green'),
                            radius: 8, fillOpacity: 0.8
                        }).addTo(map);
                        
                        circle.bindPopup(createPopupContent(station));
                        stations.push({ marker: circle, address: station.address.toLowerCase(), data: station });
                    }
                });
                setLang(currentLang); 
            });

        function filterMarkers() {
            let query = document.getElementById('filterInput').value.toLowerCase();
            stations.forEach(s => {
                if (s.address.includes(query)) s.marker.addTo(map);
                else map.removeLayer(s.marker);
            });
        }
    </script>
</body>
</html>