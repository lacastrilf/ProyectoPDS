<?php
session_start();
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
    <?php
	
    if (isset($_POST['ingresar'])) {
        $usem=$_POST['usem'];
        $password=$_POST['password'];
        $conexion=new mysqli("localhost","root","","base_proyecto");
		$sql2= "SELECT * FROM base_usuario WHERE nombre='$usem' AND contrasena='$password'";
		$resultado=$conexion->query($sql2);
		$dato=$resultado->fetch_assoc();
		$ejecutar2=mysqli_query($conexion, $sql2);
        if($dato)
			{
                $_SESSION['id_usuario'] = $dato['ID'];
                $_SESSION['usuario']=$dato['nombre'];
                if($dato['perfil']=='estudiante'){
				header("location:../otro/estudiante.php");
                }
                else if($dato['perfil']=='hogar'){
                    header("location:../otro/hogar.php");
                }

			}
            else{
                echo "<script>alert('Usuario o contraseña incorrectos');</script>";
                echo "<script>window.location.href='login.php';</script>";
            }
}

    ?>
</div>
    </div>



    
</body>
</html>


