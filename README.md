# TURNOS.COM
<p align="center">
<img src="public/images/app-icon.png" width="150px">
</p>


## Funciones para Visitantes
<p> Todos los visitantes pueden crear una cuenta única. Para registrarse, se requieren los siguientes datos: </p>

| Datos básicos       | Direccion | Contacto          | Seguridad  |
| ------------------- | --------- | ----------------- | ---------- |
| Nombres             | País      | Número de celular | Contraseña |
| Apellido            | Provincia | Email             |
| DNI                 | Ciudad    |
| Fecha de nacimiento | Dirección |
| Género              |
<p>Al crear una cuenta, se le asigna el rol de user (usuario) y status (estado) activo. Estos datos pueden ser modificados por un administrador.</p>

#### 🔐Validación de Datos Únicos

<p>El sistema verifica en tiempo real que no existan registros previos con:</p>

- El mismo número de DNI
- La misma dirección de email
- El mismo número de celular


<p> ⚠️ El DNI, email y número de celular están asociados a una única cuenta, por lo que el sistema no permite registrar múltiples cuentas con estos mismos datos. </p>

## Funciones Usuario Paciente
<p>Obligatoriamente el usuario debe tener una cuenta creada para las siguientes funciones:</p>

### Reservar turnos

| Opción                       | Acción                                                                                 |
| ---------------------------- | -------------------------------------------------------------------------------------- |
| Elegir solicitante del turno | 1.Para mi=>Para el usuario propietario de la cuenta (Opcion seleccionada por defecto). |
| Elegir solicitante del turno | 2.Para otra persona=>Para una tercera persona.                                         |
| Elegir especialidad          | Elige entre diversas especialidades médicas                                            |
| Elegir Doctor                | Selecciona el doctor de preferencia                                                    |
| Elegir un turno              | Nombre del turno (Puede existir mas de uno para un solo doctor)                        |
| Elegir una fecha             | Visualización de días disponibles                                                      |
| Elegir un horario            | Franjas horarias según profesional seleccionado                                        |
<p>Al reservar un turno para otra persona, los cupos de turnos se descuentan al propietario de la cuenta solicitante.</p>

### Historial Personal
#### Detalles completos - Información completa de cada reserva solicitada

- Nombre del turno, especialidad y profesional asignado
- Ubicación y dirección del consultorio
- Fecha y hora programada
- Estado de la cita (Asistido, pendiente, No asistido)
- Cancelar reservas
- Visualizacion del historial de hasta un año de antigüedad
  
#### Gestión de reservas - Capacidad para cancelar citas con anticipación

- Cancelación con confirmación requerida
- Registro de cancelaciones en historial
- Notificaciones por email al cancelar (Futuro)
- Política de cancelación - Límite de tiempo para modificaciones

#### Características Adicionales

- Confirmaciones automáticas - Notificaciones por email y/o SMS (futuro)
- Recordatorios - Alertas previas a la cita programada (futuro)
- Disponibilidad en tiempo real - Visualización actualizada de horarios

<p>🔔 Nota: Todas las acciones quedan registradas en el historial del usuario para su consulta posterior. </p>


## Funciones Admin
<p> El administrador tiene acceso completo al sistema, incluyendo todas las funciones de paciente más las siguientes herramientas de gestión: </p>

### Gestionar Usuarios
| Opciones                    | Acciones                                                                                                         |
| --------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| 👁‍🗨 Ver usuarios              | Listado completo de usuarios registrados                                                                         |
| 🖍  Editar datos de usuarios | Modificación de información de usuarios (Cambiar rol 🥉usuario 🥈doctor 🥇admin),(Cambiar estado ✅activo ❌inactivo) |
| 🧨 Eliminar usuarios         | Remoción de usuarios del sistema                                                                                 |
|                             |
### Historial General
#### Detalles completos de los usuarios - Información completa de cada reserva solicitada
- Nombre del turno, especialidad y profesional asignado
- Ubicación y dirección del consultorio
- Fecha y hora programada
- Estado de la cita (Asistido, pendiente, No asistido)
- Cancelar reservas
- Visualizacion del historial sin limites de antigüedad
- Opcion de archivar historiales con antigüedad de más de un año automaticamente
- Opcion de archivar historiales manualmente

### Gestionar Doctores
| Opciones                  | Acciones                             |
| ------------------------- | ------------------------------------ |
| 👁‍🗨 Ver doctores            | Listado de médicos registrados       |
| 🧱 Crear doctor            | Alta de nuevos profesionales médicos |
| 🖍  Editar datos de doctor | Actualización de datos de doctores   |
| 🧨 Eliminar doctor         | Baja de profesionales del sistema    |

### Gestionar Especialidad
| Opciones                          | Acciones                                      |
| --------------------------------- | --------------------------------------------- |
| 👁‍🗨 Ver especialidades              | Listado de especialidades médicas disponibles |
| 🧱 Crear especialidades            | Adición de nuevas especialidades              |
| 🖍  Editar datos de especialidades | Modificación de especialidades existentes     |
| 🧨 Eliminar especialidades         | Remoción de especialidades del sistema        |

### Gestionar Turnos
| Opciones          | Acciones                                  |
| ----------------- | ----------------------------------------- |
| 👁‍🗨 Ver turnos      | Consulta de todos los turnos disponibles  |
| 🧱 Crear turnos    | Generación de nuevos horarios disponibles |
| 🖍  Editar turnos  | Modificación de turnos existentes         |
| 🧨 Eliminar turnos | Cancelación de turnos programados         |
| ❇️ Clonar turnos   | Clonar turnos ya creados                  |
#### Creación de Turnos
##### 📝 1-Información Básica Requerida

| Opciones            | Acciones                        |
| ------------------- | ------------------------------- |
| Nombre del turno    | Identificador descriptivo       |
| Dirección           | Ubicación física de la atención |
| Especialidad médica | Área de atención                |
| Doctor asignado     | Profesional responsable         |

##### ⏰ 2-Configuración Horaria
| Secciones             | Opciones                 | Acciones                                                                                  |
| --------------------- | ------------------------ | ----------------------------------------------------------------------------------------- |
| Bloque de atención    | ☀️ Mañana,🌞 Tarde,🌙 Noche | Se puede seleccionar solo uno.                                                            |
| Capacidad diaria      | 3,5,20,60...             | Cantidad máxima de reservas por día                                                       |
| Horario de atención   | 12:30 a 18:30            | Hora de inicio y Hora de fin                                                              |
| Modalidad de reservas | ⏱️ Horario único          | (Todas las reservas mismo horario)                                                        |
|                       | 🕓 División horaria       | (Reservas con horarios asignados. Se crean de la division entre la hora de inicio y fin.) |


##### 📅 3-Configuración de Fechas
| Secciones                 | Opciones          | Acciones                                |
| ------------------------- | ----------------- | --------------------------------------- |
| Modalidades de selección  | 📌Fecha única      | (selección individual)                  |
|                           | 📆 Rango de fechas | (desde-hasta)                           |
| Filtro de fines de semana | ✅ Activado        | bloquea selección de sábados y domingos |
|                           | ⚪ Desactivado     | permite selección todos los días        |

##### 🚦 4-Estado del Turno
| Opciones   | Acciones                                   |
| ---------- | ------------------------------------------ |
| 🟢 Activo   | Turno visible y disponible para reservas   |
| 🔴 Inactivo | Turno creado pero no visible para usuarios |

<p>🔔 Nota: Los turnos inactivos pueden ser activados posteriormente según necesidades de agenda médica. El sistema mantiene histórico completo de todos los turnos creados.(Futuro)</p>

### Gestionar Reservas
| Opciones                   | Acciones                                          |
| -------------------------- | ------------------------------------------------- |
| 👁‍🗨 Ver reservas             | Monitorización de todas las citas agendadas       |
| 🧱 Crear reservas(Futuro)   | Generación de nuevos horarios disponibles         |
| 🖍  Editar reservas(Futuro) | Modificación de turnos existentes                 |
| 🧨 Eliminar reservas        | Cancelación de citas médicas cuando sea necesario |

<p> El módulo de administración incluye un sistema avanzado de filtros para la gestión eficiente de reservas, con capacidades de búsqueda y modificación de estados tanto automáticas como manuales. </p>

| Opciones                      | Acciones                                                         |
| ----------------------------- | ---------------------------------------------------------------- |
| 🔎Busqueda de paciente por dni | Localización rápida de pacientes mediante documento de identidad |
| 📆Filtrar por rago de fechas   | Consulta personalizada por períodos específicos                  |
| 🥼Filtrar por especialidad     | Visualización por área médica                                    |

#### Filtros rápidos por proximidad:
- 🔙 Ayer 
- 🟢 Hoy 
- 🔜 Mañan
#### Filtros por estado de reserva:
- ⏳ Pendiente
- ✅ Asistido
- ❌ No asistido

#### 🔄 Sistema de Gestión de Estados
<p>🤖 Cambios Automáticos</p>

- Verificación programada - Cada X tiempo configurado, el sistema verifica automáticamente la status
- Actualización automática - Cambia estado de "Pendiente" a "No asistido" cuando corresponde

<p>👨‍💼 Cambios Manuales</p>

- Modificación administrativa - Los administradores pueden cambiar manualmente el estado a:
- ✅ "Asistido"
- ❌ "No asistido"

#### 📊 Impacto en el Usuario - Sistema de Faltas

| Cambio de Estado | Acción              | Impacto en Faltas |
| ---------------- | ------------------- | ----------------- |
| ⏳ → ❌            | No asistió          | +1 falta          |
| ⏳ → ✅            | Asistió             | Sin cambios       |
| ❌ → ✅            | Corrección positiva | -1 falta          |
| ✅ → ❌            | Corrección negativa | +1 falta          |

- Si la reserva es "Pendiente" y luego marcada como "No asistida", se le suma +1 falta al usuario
- Si la reserva es "Pendiente" y luego marcada como "Asistida", no hay cambios.
- Si la reserva fue marcada antes como "No asistida", pero modificada a "Asistida", se resta -1 a las faltas del usuario.
- Si la reserva fue marcada antes como "Asistida", pero modificada a "No asistida", se suma +1 a las faltas del usuario.
#### ⚠️ Políticas de Faltas

- Registro acumulativo - Las faltas se suman en el historial del paciente
- Impacto en reservas futuras - La cantidad de faltas puede afectar la capacidad para solicitar nuevos turnos
- Sistema de alertas - Notificaciones al usuario sobre su estado de faltas (Futuro)
- Límites configurables - Umbrales personalizables para restricciones por faltas

<p>📈 Nota: El sistema está diseñado para promover la responsabilidad en la status a consultas médicas, optimizando la disponibilidad de turnos para todos los pacientes.</p>

### 📖 Historial
- Creación de registro en el historial al realizar una reserva con el estado "pendiente". 
- Registro de cambios del estado del registro a "asistido" cuando el administrador lo confirma. 
- Registro de cambios del estado del registro a "no asistido" cuando el administrador lo realiza manualmente o de forma automática. 
- Registro de cambios del estado del registro a "cancelado por el usuario" o "cancelado por un administrador" dependiendo la situación. 
- Registro de cambios del estado al eliminar la reserva o turno, con el estado "turno eliminado". 

#### 📝 Historial archivado
<p>
Los registros del historial con fechas anteriores a un año, automáticamente son clonadas en la tabla de "historiales archivados". La verificación automática se realiza una vez al mes.
El paciente no puede visualizar estos registros. 
</p>

### ⚙️ Configuración del Sistema
#### 📝 Información General
| Opción                   | Descripción                                      |
| ------------------------ | ------------------------------------------------ |
| Nombre de la aplicación  | Se muestra en la parte superior de la aplicación |
| Mensaje de bienvenida    | Se muestra en la pantalla de inicio              |
| Pie de página            | Texto informativo en la parte inferior           |
| Nombre de la institución | Identificación oficial en la interfaz            |

#### 💬 Mensajes al Usuario

| Opción                 | Descripción                                              |
| ---------------------- | -------------------------------------------------------- |
| Mensaje para pacientes | Aviso importante que se muestra antes de reservar turnos |

#### 🔧 Configuración de Políticas
##### ⚠️ Límites del Sistema

| Opción                     | Descripción                                                       |
| -------------------------- | ----------------------------------------------------------------- |
| Límite de faltas           | Número máximo de instatuss permitidas antes de bloquear pacientes |
| Límite de reservas activas | Cantidad máxima de reservas simultáneas por paciente              |

#### ⏰ Configuración de Tiempos

| Opción                     | Descripción                                           |
| -------------------------- | ----------------------------------------------------- |
| Cancelación de reservas    | Tiempo mínimo de anticipación requerido (en horas)    |
| Anticipación para reservar | Período máximo de anticipación para visualizar turnos |

##### Unidad de tiempo configurable

- ⏰ Horas
- 📅 Días
- 📆 Meses


#### 🔄 Automatización

| Opción                     | Descripción                                       |
| -------------------------- | ------------------------------------------------- |
| Frecuencia de verificación | Intervalo para verificación automática de statuss |
#### 🚻🌍 Personalización del Generos y Nacionalidad
| Opción                                                             | Descripción                                                                                   |
| ------------------------------------------------------------------ | --------------------------------------------------------------------------------------------- |
| 🚻 Gestionar generos permitidos para el registro                    | Generos masculino-femenino entre otros que se desee habilitar o no para la creacion de cuenta |
| 🌍 Gestionar paises,provincias,ciudades permitidos para el registro | Permite administrar las nacionalidades que se mostraran para la creacion de cuenta            |
#### 🎨 Personalización del Diseño
##### 🎨 Paleta de Colores General

| Opción                  | Descripción                              |
| ----------------------- | ---------------------------------------- |
| Color general de diseño | Tono principal de la aplicación          |
| Color de títulos        | Color para encabezados principales       |
| Color de subtítulos     | Color para subtítulos y secciones        |
| Botones principales     | Color para acciones primarias            |
| Botones secundarios     | Color para acciones secundarias          |
| Texto en botones        | Color contrastante para texto en botones |
| Fondo del pie de página | Color de fondo para el footer            |


##### 🌙 Tema Oscuro

| Opción                       | Descripción                      |
| ---------------------------- | -------------------------------- |
| Color de fondo               | Fondo principal en modo oscuro   |
| Texto principal              | Color para texto principal       |
| Texto secundario             | Color para texto secundario      |
| Fondo de barra de navegación | Color de la navbar               |
| Fondo de login/registro      | Color para formularios de acceso |
| Texto en formularios         | Color para texto en inputs       |

##### ☀️ Tema Claro

| Opción                       | Descripción                      |
| ---------------------------- | -------------------------------- |
| Color de fondo               | Fondo principal en modo oscuro   |
| Texto principal              | Color para texto principal       |
| Texto secundario             | Color para texto secundario      |
| Fondo de barra de navegación | Color de la navbar               |
| Fondo de login/registro      | Color para formularios de acceso |
| Texto en formularios         | Color para texto en inputs       |

#### 📋 Características de Configuración

- Previsualización en tiempo real - Visualización inmediata de cambios
- Reset a valores por defecto - Restablecimiento configuración inicial (Futuro)
- Exportación/Importación - Backup y restauración de configuraciones (Futuro)
- Configuración por roles - Permisos diferenciados para modificaciones
  
<p>🎯 Nota: Todos los cambios de configuración requieren confirmación y quedan registrados en el historial de modificaciones del sistema. La personalización visual se aplica inmediatamente para todos los usuarios. (Futuro)</p> 

## Capturas

### Vista de creación de turno
<img src="https://i.postimg.cc/Vvb2yX76/2025-09-02-4.png">
<img src="https://i.postimg.cc/mDk0wvC7/2025-09-02-5.png">
<img src="https://i.postimg.cc/SsPHtgf3/2025-09-02-6.png">
<img src="https://i.postimg.cc/ThMFThSc/2025-09-02-7.png">

### Vista de reserva de turno
<img src="https://i.postimg.cc/pVhdV749/2025-09-02-8.png">
<img src="https://i.postimg.cc/WpB1d8c5/2025-09-02-9.png">

### Vista Historial (usuario)
<img src="https://i.postimg.cc/hPVPTmSc/2025-09-02-10.png">

### Vista Reservas (administrador)
<img src="https://i.postimg.cc/VkRfHcrh/2025-09-02-12.png">


## Instalacion
### 1. Instalar dependencias de PHP. 
- El repositorio no incluye la carpeta vendor. Debe descargar todas las librerías necesarias mediante Composer:
```
composer install
```

### 2. Instala los paquetes de Node
-  Instalar dependencias de Frontend. 
```
npm install
```
- Error común en Windows. Sucede cuando PowerShell tiene bloqueada la ejecución de scripts por defecto como medida de seguridad.
- Ejecutar desde PowerShell:
```
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### 3. Configurar el archivo de entorno
- 1. Copiar el archivo de ejemplo
```
cp .env.example .env
```
- 2. Abrir el archivo .env y configurar los datos de la base de datos MySQL

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Generar la clave de la aplicación
- Laravel necesita una clave única para encriptar sesiones y otros datos:
```
php artisan key:generate
```