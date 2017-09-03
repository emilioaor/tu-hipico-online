Sistema hípico online
====================================

Este es un sistema de apuesta para carrera de caballos para múltiples hipódromos. Maneja las ventas desde un entorno web.

### Niveles de usuario:

#### Administrador:
* Registro de nuevos hipódromos.
* Registro de nuevas taquillas.
* Registro de caballos.
* Registro de carreras del día.
* Abrir y cerrar apuestas en las carreras.
* Actualizar precio en tablas.
* Monitorear las apuestas en tiempo real.

#### Taquilla:
* Venta de tickets
* Anular tickets
* Reporte del día
* Impresión en tickera

## Pasos de instalación

1.Posicionarte en la raiz del proyecto y renombrar el archivo **.env_example** por **.env**

2.Configurar el archivo **.env** con los parámetros de conexión a tu base de datos

3.Correr el comando **composer update** para descargar todas las dependencias del proyecto

4.Correr el comando **php artisan migrate** para generar las tablas en base de datos

5.Correr el comando **php artisan db:seed** para generar usuarios predeterminados. Estos usuarios son **admin** y **taq1** ambas contraseñas **123456**. Asegúrate de cambiar esta contraseña desde el sistema

Hecho esto podrás probar las funcionalidades de los distintos dos tipos de usuario. Solo falta un paso para los que deseen imprimir desde una tickera que es lo normal para este tipo de sistemas.

## ¿Como imprimir desde una tickera?

Nativamente el navegador no podrá establecer una conexión con la tickera, para que esta comunicación sea posible es necesario que la misma este configurada como **Generic text**  y hacer una instalación local que lo permita. En la carpeta **setup** esta el instalador de momento solo para windows que se encargara de esto.

Lo único que hay que hacer es seguir los pasos con el típico siguiente .. siguiente ..siguiente .. Luego debes configurar dos parámetros que te los pedirá con una interfaces gráfica.

**dominio**= (Dominio raíz de tu proyecto)

**usuario**= (El numero del usuario otorgado en el panel de administrador)

Ya configurado hacer click en **guardar** para mantener esta configuración y luego **iniciar** para que este a la escucha de lo que imprime la taquilla configurada. Al minimizar este se ocultara automáticamente en la barra de notificaciones.

**Nota:** El usuario es el campo **ID** que puedes visualizar desde el panel de administrador del sistema, El **ID** del usuario administrador generalmente es el 1, el del resto depende la cantidad de taquillas registradas
