package es.gva.edu.iesjuandegaray.bicis;

import java.io.IOException;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

public class ValenbiciAPI {

    private static final String API_URL = "https://valencia.opendatasoft.com/api/explore/v2.1/catalog/datasets/valenbisidisponibilitat-valenbisi-dsiponibilidad/records?limit=20";

    public static void main(String[] args) {

        if (API_URL.isEmpty()) {
            System.err.println("La URL de la API no está especificada.");
            return;
        }

        try (CloseableHttpClient httpClient = HttpClients.createDefault()) {

            HttpGet request = new HttpGet(API_URL);
            HttpResponse response = httpClient.execute(request);
            HttpEntity entity = response.getEntity();

            if (entity != null) {
                String result = EntityUtils.toString(entity);
                System.out.println("Respuesta de la API:");
                System.out.println(result);

                try {
                    JSONObject jsonObject = new JSONObject(result);
                    JSONArray resultsArray = jsonObject.getJSONArray("results");
      
                    for (int i = 0; i < resultsArray.length(); i++) {
                        JSONObject estacion = resultsArray.getJSONObject(i);

                        String direccion = estacion.optString("address");
                        int bicisDisponibles = estacion.optInt("available_bikes");
                        int huecos = estacion.optInt("available_bike_stands");

                        System.out.println("Estación: " + direccion);
                        System.out.println("Bicis disponibles: " + bicisDisponibles);
                        System.out.println("Huecos libres: " + huecos);
                        System.out.println("-----------------------------");
                    }

                } catch (org.json.JSONException e) {
                    System.err.println("Error al procesar los datos JSON: " + e.getMessage());
                }
            }

        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}

