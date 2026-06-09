package es.gva.edu.iesjuandegaray.bicis ;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class ClienteBD {

    // Configuración de los parámetros de la base de datos en la nube
    private static final String AWSDNS = "databasedmp.crsb4droazot.us-east-1.rds.amazonaws.com";
    private static final String DBNAME = "starswars";
    private static final String PUERTO = "3306";
    private static final String USERNAME = "admin";
    private static final String PASSWORD = "valenbicipass";

    public static void main(String[] args) {
        
        // Formamos la URL de conexión con el orden correcto (Servidor : Puerto / BaseDeDatos)
        String url = "jdbc:mysql://" + AWSDNS + ":" + PUERTO + "/" + DBNAME;
        
        Connection conexion = null;
        Statement stmt = null;
        ResultSet rs = null;

        try {
            System.out.println("Intentando conectar con la base de datos en AWS...");
            
            // 1. Establecer la conexión utilizando el Driver de MySQL
            conexion = DriverManager.getConnection(url, USERNAME, PASSWORD);
            System.out.println("¡Conexión establecida con éxito a la nube!");

            // 2. Crear el objeto Statement para ejecutar la consulta SQL
            stmt = conexion.createStatement();
            
            // 3. Consulta SQL adaptada fielmente a tu script (usando 'episode')
            String sql = "SELECT id, episode, title FROM films;";
            rs = stmt.executeQuery(sql);

            System.out.println("\n--- LISTADO DE PELÍCULAS (STAR WARS) ---");
            // 4. Recorrer el bache de resultados devuelto por la base de datos
            while (rs.next()) {
                int id = rs.getInt("id");
                String titulo = rs.getString("title");
                // Recuperamos el campo exacto como String, ya que en tu SQL es un VARCHAR ("Episode I", etc.)
                String episodio = rs.getString("episode");
                
                System.out.println("ID: " + id + " | " + episodio + " | Título: " + titulo);
            }

        } catch (SQLException e) {
            System.err.println("Error detectado en la base de datos o en la conexión:");
            e.printStackTrace();
        } finally {
            // 5. Cerrar siempre los recursos abiertos en el orden inverso para evitar fugas de memoria
            try {
                if (rs != null) rs.close();
                if (stmt != null) stmt.close();
                if (conexion != null) conexion.close();
                System.out.println("\nRecursos cerrados correctamente.");
            } catch (SQLException e) {
                System.err.println("Error al intentar cerrar los recursos:");
                e.printStackTrace();
            }
        }
    }
}