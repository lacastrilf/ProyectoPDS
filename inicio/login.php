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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prom Studio</title>
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
    <div id="login">
        <h1>Sign In</h1>
        <a href="#"><img class="social-login" src="https://image.flaticon.com/icons/png/128/59/59439.png"></a>
        <a href="#"><img class="social-login" src="https://image.flaticon.com/icons/png/128/49/49026.png"></a>
        <a href="#"><img class="social-login" src="https://image.flaticon.com/icons/png/128/34/34227.png"></a>
        <p>or use your email account:</p>
        <form>
            <input type="email" placeholder="Email" autocomplete="off"><br>
            <input type="password" placeholder="Password" autocomplete="off"><br>
            <a id="forgot-pass" href="#">Forgot your password?</a><br>
            <input class="submit-btn" type="submit" value="Sign In">
        </form>
    </div>
    <!-- Register Box -->
    <div id="register">
        <h1>Create Account</h1>
        <a href="#"><img class="social-login" src="https://image.flaticon.com/icons/png/128/59/59439.png"></a>
        <a href="#"><img class="social-login" src="https://image.flaticon.com/icons/png/128/49/49026.png"></a>
        <a href="#"><img class="social-login" src="https://image.flaticon.com/icons/png/128/34/34227.png"></a>
        <p>or use your email for registration:</p>
        <form>
            <input type="text" placeholder="Name" autocomplete="off"><br>
            <input type="email" placeholder="Email" autocomplete="off"><br>
            <input type="password" placeholder="Password" autocomplete="off"><br>
            <input class="submit-btn" type="submit" value="Sign Up">
        </form>
    </div>
</div>


<div class="contenedor">
<div class="contenedorcolor">
<div class="contenedor-pasar"></div>
</div>

<div class="contenedor-blanco">
    <div id="respuesta">
        <form method="post" action="login.php">

        <label class="label2" >Usuario</label><br>
        <input type="text" class="inputs" name="usem">

        <label class="label3">Contrasena</label><br>
        <input type="password" class="inputs2" name="password">
        <a href="signup.php"  class="descripcion2"><label class="descripcion2"><b>¿No tienes una cuenta? Creala Ahora</b></label></a>
        <input type="submit" class="boton" name="ingresar" ><center><p  class="pb"></p></center></div>

        </form>

</div>
    </div>



    
</body>
</html>


