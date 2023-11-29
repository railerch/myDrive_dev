<?php
session_start();
date_default_timezone_set("America/caracas");
error_reporting(-1);

// FUNCION DE DEPURACION
$debug = false;
function debug($datos)
{
    echo '<pre>';
    var_dump($datos);
};

// CONEXION BD
try {
    $conn = new PDO("sqlite:myDrive_db.db");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "ERROR EN CONEXION BD: " . $e->getMessage();
}

// GESTION DE LA APP
switch (@$_GET['acc']) {
    case 'iniciar-sesion':
        if (@$_POST['usuario'] != "" && @$_POST['clave'] != "") {
            $salt       = md5('myDr!v3');
            $usuario    = strtolower($_POST['usuario']);
            $clave      = md5($_POST['clave'] . $salt);

            // Sesion para usuario root
            if ($usuario == 'root') {
                if ($clave == '325574a76e0f571ba2260bac81b2e382') {
                    setcookie("creds", "root|$clave", time() + 86400, "/");
                    setcookie("auth", "true", time() + 86400, "/");

                    $_SESSION['nombre-usuario'] = 'SU root';
                    $_SESSION['directorio-usuario'] = '/';
                    $_SESSION['usuario'] = 'root';

                    echo '<meta http-equiv="refresh" content="2;url=main-app.php?acc=inicio">';
                    $mensaje = "<span style='color: #555555'>Iniciando.</span>";
                } else {
                    $_SESSION['error'] = "Datos invalidos.";
                    header("location: login.php");
                }
            } else {
                // Sesion para usuarios registrados
                try {
                    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'");
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row) {
                        setcookie("creds", "{$_POST['usuario']}|$clave", time() + 86400, "/");
                        setcookie("auth", "true", time() + 86400, "/");

                        $_SESSION['nombre-usuario'] = ucwords($row['nombre']);
                        $_SESSION['directorio-usuario'] = strtolower($row['usuario']);
                        $_SESSION['usuario'] = strtolower($row['usuario']);

                        echo '<meta http-equiv="refresh" content="2;url=main-app.php?acc=inicio">';
                        $mensaje = "<span style='color: #555555'>Iniciando.</span>";
                    } else {
                        $_SESSION['error'] = "Datos invalidos.";
                        header("location: login.php");
                    }
                } catch (PDOException $e) {
                    echo '<meta http-equiv="refresh" content="3;url=login.php">';
                    $mensaje = "<span style='color: red'>ERROR INTERNO</span><br>" . "<small>" . $e->getMessage() . "</small>";
                }
            }
        } else {
            $_SESSION['error'] = "<b>Error:</b> Hay campos vacios.";
            header("location: login.php");
        }
        break;
    case 'cerrar-sesion':
        setcookie("PHPSESSID", "", time() - 3600, "/");
        setcookie("creds", "", time() - 3600, "/");
        setcookie("auth", "", time() - 3600, "/");

        session_destroy();
        session_write_close();

        echo '<meta http-equiv="refresh" content="2;url=login.php">';
        $mensaje = "<span style='color: #555555'>Sesión finalizada.</span>";
        break;
    case 'sesion-no-autorizada':
        $mensaje = "<span style='color: red'>Sesión no autorizada.</span>";
        echo '<meta http-equiv="refresh" content="2;url=login.php">';
        break;
    case 'crear-cuenta':

        // Validar campos vacios
        $registrar = true;
        foreach ($_POST as $campo) {
            if ($campo == '') $registrar = $false;
        }

        // Realizar registro
        if ($registrar) {
            $salt       = md5('myDr!v3');
            $nombre     = $_POST['nombre'];
            $correo     = $_POST['correo'];
            $usuario    = $_POST['usuario'];
            $clave      = md5($_POST['clave'] . $salt);

            try {
                $stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, correo, usuario, clave) VALUES (NULL, '$nombre', '$correo', '$usuario', '$clave')");
                $stmt->execute();

                // Crear directorio del usuario
                mkdir("repositorio/" . $usuario);

                // Redirigir al login para inicio de sesion
                $mensaje = "<span style='color: #555555'>Cuenta creada correctamente.</span>";
                echo '<meta http-equiv="refresh" content="3;url=login.php">';
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    // Mensaje de error para usuario duplicado
                    $mensaje = "<span style='color: red'>ERROR AL REGISTRAR</span><br>" . "<small>El nombre de usuario o email ya existen</small>";
                } else {
                    // Mensaje para errores inesperados
                    $mensaje = "<span style='color: red'>ERROR INTERNO</span><br>" . "<small>" . $e->getMessage() . "</small>";
                }
                echo '<meta http-equiv="refresh" content="3;url=crear-cuenta.php">';
            }
        } else {
            $_SESSION['error'] = "<b>Error:</b> Hay campos vacios.";
            header("location: crear-cuenta.php");
        }

        break;
    case 'actualizar-datos-cuenta':
        $salt       = md5('myDr!v3');
        $usuario    = $_SESSION['usuario'];
        $nombre     = $_POST['nombre'];
        $correo     = $_POST['correo'];
        $pass = md5($_POST['clave'] . $salt);
        $clave      = $_POST['clave'] != '' ? ",clave='$pass'" : NULL;

        try {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = '$nombre', correo = '$correo' $clave WHERE usuario = '$usuario'");
            $stmt->execute();

            $mensaje = "<span style='color: #555555'>Datos actualizados.</span>";
            echo '<meta http-equiv="refresh" content="1;url=cuenta-usuario.php">';
        } catch (PDOException $e) {
            $mensaje =
                "<span style='color: red'>Error al actualizar, intente nuevamente.</span><br>" . "<small>" . $e->getMessage() . "</small>";
            echo '<meta http-equiv="refresh" content="3;url=cuenta-usuario.php">';
        }

        break;
    case 'eliminar-cuenta':
        // Eliminar usuario de la BD
        $usuario = $_SESSION['directorio-usuario'];
        $conn->query("DELETE FROM usuarios WHERE usuario = '$usuario'");

        // ELiminar repositorio del usuario
        $rutaUsuario = "repositorio/" . $_SESSION['directorio-usuario'];
        $rutas = [$rutaUsuario];

        // Buscar directorios y subdirectorios
        $i = 0;
        while ($i < count($rutas)) {
            $directorio = array_diff(scandir($rutas[$i]), array('.', '..'));
            foreach ($directorio as $archivo) {
                $fl = $rutas[$i] . '/' . $archivo;
                if (is_dir($fl)) {
                    array_push($rutas, $rutas[$i] . '/' . $archivo);
                }
            }
            $i++;
        }

        // Eliminar repositorio
        $x = count($rutas) - 1;
        while ($x > -1) {
            // ELiminar archivos
            $directorio = array_diff(scandir($rutas[$x]), array('.', '..'));
            foreach ($directorio as $archivo) {
                $fl = $rutas[$x] . '/' . $archivo;
                if (is_file($fl)) {
                    unlink($fl);
                }
            }
            // Eliminar directorio raiz del usuario
            rmdir($rutas[$x]);
            $x--;
        }

        // Eliminar sesion del usuario
        setcookie("PHPSESSID", "", time() - 3600, "/");
        setcookie("creds", "", time() - 3600, "/");
        setcookie("auth", "", time() - 3600, "/");

        session_destroy();
        session_write_close();

        // Redirigir al login
        $mensaje = "<span style='color: #555555'>Su cuenta ha sido eliminada.</span>";
        echo '<meta http-equiv="refresh" content="2;url=login.php">';
        break;

    case 'crear-nota':
        if (file_exists($_SESSION['ruta'] . '/' . $_POST['nombre'] . '.txt')) {
            $mensaje    = "<span style='color: #555555'>Nota actualizada.</span>";
        } else {
            $mensaje = "<span style='color: #555555'>Nota creada.</span>";
        }

        // Procesar el archivo
        $rutaNota   = $_SESSION['ruta'] . '/' .  $_POST['nombre'] . '.txt';
        $nota       = fopen($rutaNota, 'w');
        fwrite($nota, $_POST['contenido']);
        fclose($nota);

        // Retornar contenido del archivo al editor
        $_SESSION['txt-nombre']     = $_POST['nombre'];
        $_SESSION['txt-contenido']  = fread(fopen($rutaNota, 'r'), filesize($rutaNota));
        echo '<meta http-equiv="refresh" content="1;url=crear-nota.php?acc=editar-nota">';
        break;
    case 'crear-directorio':
        if (mkdir($_SESSION['ruta'] . '/' . $_POST['nombre'])) {
            $mensaje = "<span style='color: #555555'>Directorio creado.</span>";
        } else {
            $mensaje = "<span style='color: red'>Error, directorio no creado.</span>";
        }
        echo '<meta http-equiv="refresh" content="1;url=main-app.php?directorio=' . $_SESSION['directorio'] . '">';
        break;
    case 'eliminar-directorio':
        if (@rmdir($_SESSION['ruta'] . '/' . $_GET['directorio'])) {
            $mensaje = "<span style='color: #555555'>Directorio eliminado.</span>";
        } else {
            $mensaje = "<span style='color: red'>Error, el directorio no está vacio.</span>";
        }

        echo '<meta http-equiv="refresh" content="1;url=main-app.php?directorio=' . $_SESSION['directorio'] . '">';
        break;
    case 'subir-archivo':
        $dir = $_SESSION['ruta'];
        $destino = $dir . '/' . basename($_FILES['archivo']['name']);

        // MOSTRAR MENSAJE UNA VEZ SE HAYA CARGADO EL ARCHIVO
        if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $destino)) {
            $mensaje = "<span style='color: #555555'>Archivo cargado.</span>";
        } else {
            $mensaje = "<span style='color: red'>Error, archivo no cargado.</span>";
        };
        echo '<meta http-equiv="refresh" content="1;url=main-app.php?directorio=' . $_SESSION['directorio'] . '">';
        break;
    case 'eliminar-archivo':
        if (unlink($_SESSION['ruta'] . '/' . $_GET['archivo'])) {
            $mensaje = "<span style='color: #555555'>Archivo eliminado.</span>";
        } else {
            $mensaje = "<span style='color: red'>Error, archivo no eliminado.</span>";
        };
        echo '<meta http-equiv="refresh" content="1;url=main-app.php?directorio=' . $_SESSION['directorio'] . '">';
        break;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("head.php") ?>
    <title>Redirigiendo</title>
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh">
    <div class="text-center">
        <img class="mb-3" src="public/img/preloader.gif" alt="">
        <h3><?php echo @$mensaje ?></h3>
        <?php if ($debug) debug($_POST) ?>
    </div>
</body>

</html>