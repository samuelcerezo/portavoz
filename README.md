# portavoz
Tema en blanco de WordPress para Portavoz.

### Mejoras de seguridad
- Deshabilitados pingbacks y XMLRPC.
- Deshabilitada la REST API para usuarios no logueados.
- Reglas CSP de seguridad generadas automáticamente.

### Carga automática de tipografías
- Las tipografías dentro del directorio _fonts_ se cargarán automáticamente.
- Deben estar ubicadas en directorios con el nombre de la familia y los distintos grosores en ficheros separados con el grosor como nombre, seguido de una _i_ si es cursiva. Por ejemplo: _Open Sans/400.woff2_, _Open Sans/400i.woff2_. Solo se cargarán los archivos woff2, para un mejor WPO.

### URLs generadas automáticamente a partir de la ID
- {{id-XXX}} siendo XXX la ID del tipo de objeto, reemplazará automáticamente la cadena de texto por la url. Si se trata de un adjunto, mostrará el enlace al fichero.
- {{YYY-XXX}} siendo YYY la taxonomía y XXX la ID del término de la taxonomía, reemplazará automáticamente la cadena de texto por la url al término de la taxonomía.

### Soporte extendido
- Soporte SASS. El archivo _main.scss_ se procesa automáticamente a CSS y se carga en el frontend. El archivo _admin.scss_ se procesa automáticamente a CSS y se carga en el backend.
- Soporte SVG.

### Integración con Elementor
- Al enviar un formulario, permite enviar los datos a una url mediante POST o GET.
- Al enviar un formulario, permite enviar los datos a una Datakey de Salesforce Marketing Cloud.

### Carga automática de funciones PHP
- Todos los archivos PHP dentro del directorio _functions_ se cargarán automáticamente, estando disponibles las funciones en ellos contenidas.

### Otras mejoras
- Eliminación automática de archivos innecesarios, como _readme.html_, _wp-config-sample.php_ o _license.txt_.
- Notificaciones de actualizaciones automáticas de plugins y temas deshabilitadas.
