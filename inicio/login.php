<?php
session_start();

if (isset($_POST['ingresar'])) {
    $usem = $_POST['usem'];
    $password = $_POST['password'];
    $conexion = new mysqli("localhost", "root", "", "base_proyecto");
    $sql2 = "SELECT * FROM base_usuario WHERE nombre='$usem' AND contrasena='$password'";
    $resultado = $conexion->query($sql2);
    $dato = $resultado->fetch_assoc();
    $ejecutar2 = mysqli_query($conexion, $sql2);
    if ($dato) {
        $_SESSION['id_usuario'] = $dato['ID'];
        $_SESSION['usuario'] = $dato['nombre'];
        if ($dato['perfil'] == 'estudiante') {
            header("location:../otro/estudiante.php");
        } else if ($dato['perfil'] == 'hogar') {
            header("location:../otro/hogar.php");
        }

    } else {
        echo "<script>alert('Usuario o contraseña incorrectos');</script>";
        echo "<script>window.location.href='login.php';</script>";
    }
}
if(isset($_POST['enviar'])){
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $perfil = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    if($perfil === 'estudiante' || $perfil === 'hogar'){
        $conexion = new mysqli("localhost","root","","base_proyecto");
        $sql = "SELECT * FROM base_usuario WHERE nombre='$usuario'";
        $resultado = $conexion->query($sql);
        $dato = $resultado->fetch_assoc();
        if($dato){
            echo "Lo siento, este usuario ya está registrado, intenta con otro";
        } else {
            $sql2 = "INSERT INTO base_usuario (nombre, contrasena, perfil) VALUES ('$usuario','$contrasena','$perfil')";
            $ejecutar2 = mysqli_query($conexion, $sql2);
            header("location:login.php");
        }
    } else {
        echo "Perfil no válido";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <title>SmartSpends</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>
<div id="container">
    <!-- Cover Box -->
    <div id="cover">
        <!-- Sign Up Section -->
        <h1 class="sign-up">Hello, Friend!</h1>
        <p class="sign-up">Enter your personal details<br> and start a journey with us</p>
        <a class="button sign-up" href="#cover">Sign Up</a>
        <!-- Sign In Section -->
        <h1 class="sign-in">Welcome Back!</h1>
        <p class="sign-in">To keep connected with us please<br> login with your personal info</p>
        <br>
        <a class="button sub sign-in" href="#">Sign In</a>
    </div>
    <!-- Login Box -->
    <div id="login" >
        <h1>Ingresar</h1>
        <p></p>
        <form method="post" action="login.php">
            <input type="text" placeholder="Usuario" name="usem"><br>
            <input type="password" placeholder="Password" name="password"><br>
            <input class="submit-btn" type="submit" value="Ingresar" name="ingresar" >
        </form>
    </div>
    <!-- Register Box -->
    <div id="register">
        <h1>Crear Cuenta</h1>
        <p  style="font-size: 95%;">Selecciona un rol</p>
        <div class="contenedor-tipos">

            <div class="tipo1" onclick="usuario()">
                <p class="texto" id="textou" style="font-size: 100%;"><br>Estudiante</b></p>
            </div>
            <div class="tipo2" onclick="empresa()">
                <p class="texto"  id="textoe" style="font-size: 100%;"><br>Hogar</b></p>
            </div>
        </div>
        <form method="post">
            <input type="text" placeholder="Name" name="usuario" autocomplete="off"><br>
            <input type="password" placeholder="Password" name="contrasena" autocomplete="current-password"><br>
            <input type="hidden" name="tipo" id="tipo" value="">
            <input class="submit-btn" type="submit" name="enviar" value="Registrar">
        </form>
    </div>
</div>
<script>
    function usuario() {
        document.querySelector('.tipo1').classList.add('seleccionado');
        document.querySelector('.tipo2').classList.remove('seleccionado');
        document.getElementById('tipo').value = 'estudiante';
    }

    function empresa() {
        document.querySelector('.tipo2').classList.add('seleccionado');
        document.querySelector('.tipo1').classList.remove('seleccionado');
        document.getElementById('tipo').value = 'hogar';
    }
</script>
</body>
</html>


