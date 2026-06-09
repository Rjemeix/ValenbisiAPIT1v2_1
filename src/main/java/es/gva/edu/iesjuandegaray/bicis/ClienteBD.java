package es.gva.edu.iesjuandegaray.bicis ;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class ClienteBD {

    private static final String AWSDNS = "databasedmp.crsb4droazot.us-east-1.rds.amazonaws.com";
    private static final String DBNAME = "starswars";
    private static final String PUERTO = "3306";
    private static final String USERNAME = "admin";
    private static final String PASSWORD = "valenbicipass";

    public static void main(String[] args) {
        
        String url = "jdbc:mysql://" + AWSDNS + ":" + PUERTO + "/" + DBNAME;
        
        Connection conexion = null;
        Statement stmt = null;
        ResultSet rs = null;

        try {
            System.out.println("Intentando conectar con la base de datos en AWS...");
            
            conexion = DriverManager.getConnection(url, USERNAME, PASSWORD);
            System.out.println("¡Conexión establecida con éxito a la nube!");

            stmt = conexion.createStatement();
            
            String sql = "SELECT id, episode, title FROM films;";
            rs = stmt.executeQuery(sql);

            System.out.println("\nLISTADO DE PELÍCULAS ");
         
            while (rs.next()) {
                int id = rs.getInt("id");
                String titulo = rs.getString("title");
            
                String episodio = rs.getString("episode");
                
                System.out.println("ID: " + id + " - " + episodio + " - Título: " + titulo);
            }

        } catch (SQLException e) {
            System.err.println("Error detectado:");
            e.printStackTrace();
        } finally {
           
            try {
                if (rs != null) rs.close();
                if (stmt != null) stmt.close();
                if (conexion != null) conexion.close();
                System.out.println("Recursos cerrados.");
            } catch (SQLException e) {
                System.err.println("Error al cerrar la conexion:");
                e.printStackTrace();
            }
        }
    }
}