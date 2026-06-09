<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad de ValenBisi</title>
    <style>
        :root { --bg-color: #121212; --text-color: #e0e0e0; --card-bg: #1e1e1e; --accent-color: #4CAF50; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); color: var(--text-color); margin: 0; padding: 40px 20px; }
        h1 { text-align: center; color: #ffffff; }
        .lang-selector { text-align: center; margin-bottom: 20px; }
        .lang-selector button { padding: 8px 16px; cursor: pointer; background: #333; color: white; border: none; border-radius: 4px; margin: 0 5px; }
        table { width: 95%; max-width: 1200px; margin: 0 auto; border-collapse: collapse; background-color: var(--card-bg); border-radius: 12px; overflow: hidden; }
        th { background-color: var(--accent-color); color: #ffffff; padding: 16px; text-transform: uppercase; font-size: 0.85rem; }
        td { padding: 14px; border-bottom: 1px solid #333; text-align: center; font-size: 0.95rem; }
        tr:hover { background-color: #2a2a2a; }
        .btn-mapa { display: block; width: 250px; margin: 30px auto; padding: 15px; background-color: #4CAF50; color: white; text-align: center; text-decoration: none; font-weight: bold; border-radius: 5px; }
    </style>
</head>
<body>

<h1 id="main-title">Disponibilidad de ValenBisi</h1>

<div class="lang-selector">
    <button onclick="changeLanguage('es')">Español</button>
    <button onclick="changeLanguage('en')">English</button>
</div>

<?php
$baseUrl = "https://geoportal.valencia.es/server/rest/services/OPENDATA/Trafico/MapServer/228/query?where=1=1&outFields=*&returnGeometry=true&outSR=4326&f=json&resultRecordCount=2000";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data["features"]) && !empty($data["features"])) {
    echo "<table><thead><tr>";
    $headers = ['addr'=>'Dirección', 'num'=>'Número', 'open'=>'Abierto', 'avail'=>'Disponibles', 'free'=>'Libres', 'total'=>'Total', 'upd'=>'Actualizado', 'lat'=>'Latitud', 'lon'=>'Longitud'];
    foreach($headers as $id => $text) {
        echo "<th id='th-$id'>$text</th>";
    }
    echo "</tr></thead><tbody>";

    foreach ($data["features"] as $station) {
        $attr = $station['attributes'];
        $geo = $station['geometry'] ?? ['y' => 0, 'x' => 0];
        // Guardamos el estado original en un atributo data para usarlo en JS
        $isOpen = ($attr['open'] == "T");
        
        echo "<tr>
            <td>" . htmlspecialchars($attr['address']) . "</td>
            <td>" . htmlspecialchars($attr['number']) . "</td>
            <td class='td-open' data-open='" . ($isOpen ? "true" : "false") . "'>" . ($isOpen ? "Sí" : "No") . "</td>
            <td>" . (int)$attr['available'] . "</td>
            <td>" . (int)$attr['free'] . "</td>
            <td>" . (int)$attr['total'] . "</td>
            <td>" . htmlspecialchars($attr['updated_at']) . "</td>
            <td>" . round($geo['y'], 5) . "</td>
            <td>" . round($geo['x'], 5) . "</td>
        </tr>";
    }
    echo "</tbody></table>";
    echo "<a href='mapearbicis.php' class='btn-mapa' id='btn-map'>Ver Mapa de Estaciones</a>";
} else {
    echo "<p style='text-align:center;'>No se pudieron cargar las estaciones.</p>";
}
?>

<script>
function changeLanguage(lang) {
    const texts = {
        'es': { 
            title: "Disponibilidad de ValenBisi", 
            btn: "Ver Mapa de Estaciones", 
            th: ["Dirección", "Número", "Abierto", "Disponibles", "Libres", "Total", "Actualizado", "Latitud", "Longitud"],
            open: "Sí", closed: "No" 
        },
        'en': { 
            title: "ValenBisi Availability", 
            btn: "View Station Map", 
            th: ["Address", "Number", "Open", "Available", "Free", "Total", "Updated", "Latitude", "Longitude"],
            open: "Yes", closed: "No" 
        }
    };
    
    document.getElementById('main-title').innerText = texts[lang].title;
    document.getElementById('btn-map').innerText = texts[lang].btn;
    
    // Traducir encabezados
    const ids = ['addr', 'num', 'open', 'avail', 'free', 'total', 'upd', 'lat', 'lon'];
    ids.forEach((id, i) => document.getElementById('th-' + id).innerText = texts[lang].th[i]);
    
    // Traducir celdas de "Abierto"
    document.querySelectorAll('.td-open').forEach(td => {
        const isOpen = td.getAttribute('data-open') === 'true';
        td.innerText = isOpen ? texts[lang].open : texts[lang].closed;
    });
}
</script>
</body>
</html>