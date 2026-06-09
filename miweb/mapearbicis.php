<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Estaciones Valenbisi</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        :root {
            --bg-color: #121212;
            --text-color: #e0e0e0;
            --card-bg: #1e1e1e;
            --accent-color: #4CAF50;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 { color: #ffffff; margin-bottom: 30px; font-weight: 600; }

        .container {
            width: 95%;
            max-width: 1200px;
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        #map { height: 600px; width: 100%; border-radius: 8px; margin-top: 20px; }

        .controls { margin-bottom: 20px; text-align: center; }
        
        input {
            padding: 10px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #333;
            background: #2d2d2d;
            color: white;
        }

        .btn-mapa {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: var(--accent-color);
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-mapa:hover { background-color: #45a049; }
    </style>
</head>
<body>

    <h1>Mapeo de Bicicletas en Valencia</h1>

    <div class="container">
        <div class="controls">
            <input type="text" id="filterInput" placeholder="Buscar dirección..." onkeyup="filterMarkers()">
        </div>
        
        <div id="map"></div>
        
        <div style="text-align: center;">
            <a href="index.php" class="btn-mapa">Volver al listado</a>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([39.47, -0.37], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let stations = [];

        function getMarkerColor(available) {
            if (available < 5) return 'red';
            if (available < 10) return 'orange';
            if (available < 20) return 'yellow';
            return 'green';
        }

        fetch('data.json')
            .then(response => response.json())
            .then(data => {
                Object.values(data).forEach(station => {
                    const { lat, lon, address, available, free, total } = station;
                    if (lat && lon) {
                        let circle = L.circleMarker([lat, lon], {
                            color: getMarkerColor(available),
                            radius: 8,
                            fillOpacity: 0.8
                        }).addTo(map)
                        .bindPopup(`<strong>${address}</strong><br>Disponibles: ${available}<br>Libres: ${free}<br>Total: ${total}`);
                        
                        stations.push({ marker: circle, address: address.toLowerCase() });
                    }
                });
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