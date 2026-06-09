ValenbiciAPI

Para el ValenbiciAPI, el objetivo es proporcionar una interfaz que gestione la información del servicio de bicicletas públicas de Valencia.

Funcionalidades

Consulta de estado: Permite consultar el estado actual de las estaciones, determinando cuántas bicicletas hay disponibles y cuántos huecos libres quedan en cada una.

Gestión de datos: Utiliza una estructura de datos para procesar la información en tiempo real, permitiendo al usuario final saber rápidamente la disponibilidad sin tener que consultar la web externa manualmente.

Conexión a la nube: Implementación de conexión JDBC hacia una instancia remota de base de datos AWS.

Gestión de datos:

Consulta de listado de películas (tabla films).

Filtros dinámicos mediante PreparedStatement para listar personajes según la película seleccionada.

Seguridad: Configuración de acceso mediante grupos de seguridad de AWS para permitir la conexión desde clientes externos (MySQL Workbench/Java).

Librerías: mysql-connector-j 

Configuración: Las credenciales y el endpoint de la base de datos se encuentran configuradas en la clase ClienteBD.java.

Configuración de Entorno (AWS)
Laboratorio: Acceso al Learner Lab de AWS Academy.

Base de Datos: Instancia MariaDB 

Red: Puerto 3306 abierto para tráfico entrante (0.0.0.0/0).

Autor
Nombre: Marc Cervera

Centro: IES Juan de Garay