# DOCUMENTACION

## PASOS PARA INSTALAR EL PROYECTO CON DOCKER

1. Estos comando instalarán y levantaran los contenedores de Docker para php, fpm y mysql, así como los vendors necesarios para correr el proyecto Symfony.
```
    $ docker compose up -d
    $ docker-compose exec php-fpm composer install
``` 

2.1 Si se desea empezar con una serie de datos minimos creados en la raiz del proyecto existe un SQL con las tablas y filas.
En el backup SQL existen dos usuarios con el mismo password 1234. 
```
    worker email:davter@happydonia.com
    manager email:davter@happydonia.com
    password: 1234
```

2.1 Si se desea empezar el proyecto completamente vacio habra que ejecutar el siguiente comando que creara las tablas necesarias para guardar la informacion
```
    $ docker compose exec php-fpm bin/console doc:sc:up --force
``` 
Esto implica introducir a mano en la tabla de usuario un usuario worker y otro manager. Tambien una company con la informacion necesaria.

3. Para acceder a la interfaz web se puede hacer a traves del siguiente link
```
    http://localhost:25000/
```
Encontraremos la pantalla de login. Introducir el usuario deseado. A continuacion veremos diferente informacion segun el tipo de usuario.
El usuario worker puede ver sus fichajes y registrar un nuevo fichaje.
El usuario manager puede ver los fichajes de los workers y configurar el horario de la empresa para todos los empleados.

## ARQUITECTURA DEL PROYECTO
El proyecto sigue la arquitectura hexagonal y DDD, separando las capas por Application, Domain, Infrastructure y usando los conceptos de servicios de aplicacion, repositorios, Value objects y Eventos de dominio, etc.

Ademas existen tests unitarios y funcionales que se pueden lanzar usando el siguiente comando:
```
    docker compose exec php-fpm bin/phpunit
```

He implementado un sistema de eventos usando el componente Messenger de Symfony, unicamente para los registros de los fichajes los cuales se guardan en una tabla para poder usarse mas adelante como se desee, ya sea un panel historico de eventos sucedidos o 
ser consumidos para procesarse a posteriori como enviar emails. Se podria haber usado Rabbit para encolar los eventos y procesarse de forma asincrona, pero he querido hacerlo sencillo. 

## COMENTARIOS PERSONALES
No he podido dedicarle todo el tiempo me que hubiera gustado dado que esta semana pasada estuve muy ocupado y hubieron festivos con muchos compromisos personales, y 
esta semana he podido dedicarle algo mas de tiempo al terminar mi joranda laboral y los proximos dias estoy fuera de casa por motivos laborales. 

Por ello, me hubiera gustado haber podido hacer las cosas mejor, como por ejemplo:
- un API Rest con su panel de documentacion y pruebas usando NelmioAPIBundle, Swagger, etc. Compplementado ademas con la interfaz web responsive que hice inicialmente. 
- Mas cobertura de tests.
- Uso de Rabbit para el sistema de eventos de dominio y procesado posterior.
- Uso de patron Factory para crear usuarios segun el tipo o rol que desempeña.
- Añadir mas validaciones a nivel de dominio
- He decidido usar las anotaciones de Doctrine para el mapeo de las entidades, asumiendo ese acople para avanzar mas rapido, pero siendo purista se deberia haber usado archivos de configuracion XML, a nivel de infrastructura
para ello.
- Tambien he usado las Collection de Doctrine en las relaciones de las entidades pero habria que haber usado una implementacion propia en lugar de este elemento propio de infrastuctura del ORM.

En resumen, conozco estos inconvenientes, y me hubiera gustado haberlos podido solventar pero dado el poco tiempo que he tenido los he asumido y en el futuro serian solucionados.
Espero que haya aportado valor a la prueba tecnica y se pueda valorar el esfuerzo que he puesto en ella. 
Gracias.