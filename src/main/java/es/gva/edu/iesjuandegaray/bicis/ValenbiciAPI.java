package es.gva.edu.iesjuandegaray.bicis;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import java.io.IOException;

public class ValenbiciAPI {

    private static final String API_URL =
            "https://geoportal.valencia.es/server/rest/services/OPENDATA/Trafico/MapServer/228/query"
            + "?where=1%3D1"
            + "&outFields=*"
            + "&returnGeometry=true"
            + "&f=json";

    public static void main(String[] args) {

        try (CloseableHttpClient httpClient = HttpClients.createDefault()) {

            HttpGet request = new HttpGet(API_URL);
            HttpResponse response = httpClient.execute(request);

            HttpEntity entity = response.getEntity();

            if (entity != null) {

                String result = EntityUtils.toString(entity);

                JSONObject jsonObject = new JSONObject(result);
                JSONArray features = jsonObject.getJSONArray("features");

                System.out.println("Número de estaciones: " + features.length());
                System.out.println();

                for (int i = 0; i < features.length(); i++) {
                    JSONObject feature = features.getJSONObject(i);

                    JSONObject attributes = feature.getJSONObject("attributes");
                    int number = attributes.optInt("number", 0);
                    String address = attributes.optString("address", "Desconocida");
                    int available = attributes.optInt("available", 0);
                    int free = attributes.optInt("free", 0);
                    int total = attributes.optInt("total", 0);

                    Geometry geo = new Geometry();
                    JSONObject geometry = feature.optJSONObject("geometry");
                    if (geometry != null) {
                        geo.x = geometry.optDouble("x", 0.0);
                        geo.y = geometry.optDouble("y", 0.0);
                    }

                    System.out.println("Estación: " + number + " - " + address);
                    System.out.println("  -> Bicicletas Disponibles: " + available);
                    System.out.println("  -> Anclajes Libres: " + free);
                    System.out.println("  -> Capacidad Total: " + total);
                    System.out.println("  -> Coordenadas: x=" + geo.x + ", y=" + geo.y);
                    System.out.println("----------------------------------------");
                }
            }

        } catch (IOException e) {
            System.out.println("Error en la petición HTTP:");
            e.printStackTrace();
        } catch (Exception e) {
            System.out.println("Error procesando JSON:");
            e.printStackTrace();
        }
    }
}