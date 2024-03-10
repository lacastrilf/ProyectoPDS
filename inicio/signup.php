<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prom Studio</title>
    <link rel="stylesheet" href="stylesignup.css">
    <script src="libre.js"></script>
</head>
<body>

<div class="contenedor">

<div class="contenedorcolor">
<div class="contenedor-pasar">
<div id="resu"></div>
</div>
<div class="contenedor-pasar"></div>
<div class="contenedor-pasar"></div>
</div>

<div class="puntos">
    <div class="punto2 activo"></div>
    <div class="punto2"></div>
    <div class="punto2"></div>
</div>

<img src="iconos/logo2.png" class="logo">
<p class="logo_2">PROM STUDIO</p>


<div class="contenedor-blanco">
    <p class="titulo">Selecciona un Rol</p>
    <p class="descripcion">Selecciona el rol que más se acomodo a tu<br>necesidades para usar Prom Studio</p>
    <div class="tipo">
        <div class="tipo1" onclick="usuario()">
            <img src="iconos/usuarios.png" class="imgd" id="imgu">
            <img src="iconos/usuarios2.png" class="imgd2" id="imgu2">
            <p class="texto" id="textou"><br>Usuario</b></p>
        </div> 
        <div class="tipo2" onclick="empresa()">
            <img src="iconos/banco.png" class="imgd" id="imge">
            <img src="iconos/banco2.png" class="imgd2" id="imge2">
            <p class="texto"  id="textoe"><br>Empresa</b></p>
        </div>
    </div>

    <div id="respuesta">
        <form method="post" >

    <form method="POST" >
    <input type="text" name="usuario1">
    <input type="password" name="contrasena1">
    <input type="submit" name="enviar1" value="Enviar1">
    </form>

        </form>
    </div>


<div id="respuestae">
<form method="post" >
   
    <form method="POST" >
    <input type="text" name="usuario2">
    <input type="password" name="contrasena2">
    <input type="submit" name="enviar2" value="Enviar2">
    </form>

</form>

    </div>
</div>

</div>

<?php
     if(isset($_POST['enviar1'])){
        $usuario=$_POST['usuario1'];
        $contrasena=$_POST['contrasena1'];
        $conexion=new mysqli("localhost","root","","base_proyecto");
        $sql="SELECT * FROM base_usuario WHERE nombre='$usuario'";
        $resultado=$conexion->query($sql);
		$dato=$resultado->fetch_assoc();
		$ejecutar=mysqli_query($conexion, $sql);
        if($dato){
            echo "Lo siento, este usuario ya esta registrado, intenta con otro";
     }
     else{
        $sql2="INSERT INTO base_usuario VALUES (null, '$usuario','$contrasena','estudiante')";
        $ejecutar2=mysqli_query($conexion, $sql2);
        header("location:login.php");
     }
    }

     if(isset($_POST['enviar2'])){
        $usuario=$_POST['usuario2'];
        $contrasena=$_POST['contrasena2'];
        $conexion=new mysqli("localhost","root","","base_proyecto");
        $sql="SELECT * FROM base_usuario WHERE nombre='$usuario'";
        $resultado=$conexion->query($sql);
		$dato=$resultado->fetch_assoc();
		$ejecutar=mysqli_query($conexion, $sql);
        if($dato){
            echo "Lo siento, este usuario ya esta registrado, intenta con otro";
     }
     else{
        $sql2="INSERT INTO base_usuario VALUES (null, '$usuario','$contrasena','hogar')";
        $ejecutar2=mysqli_query($conexion, $sql2);
        header("location:login.php");
     }
    }
?>
<script>


base=0;
function usuario(){
    base=1;
    document.querySelector('.tipo1').style.backgroundColor="#8a72f1";
    document.getElementById('imgu').style.display="none";
    document.getElementById('imgu2').style.display="block";
    document.getElementById('textou').style.color="white";
    document.querySelector('.tipo1').style.borderColor="#593ae6";
    document.querySelector('.tipo2').style.backgroundColor="white";
    document.getElementById('imge').style.display="block";
    document.getElementById('imge2').style.display="none";
    document.getElementById('textoe').style.color="#d2d1dd";
    document.querySelector('.tipo2').style.borderColor="#d2d1dd";

    menuu();
    
}

function menuu(){
    document.getElementById('respuestae').style.display="none";
    document.querySelector('.titulo').style.marginTop="3vw";
    document.querySelector('.descripcion').style.marginTop="-2vw";
    document.querySelector('.tipo').style.marginTop="9vw";
    document.querySelector('.tipo1').style.height="6vw";
    document.querySelector('.tipo2').style.height="6vw";
}

function empresa(){
    base=2;
    document.querySelector('.tipo2').style.backgroundColor="#8a72f1";
    document.getElementById('imge').style.display="none";
    document.getElementById('imge2').style.display="block";
    document.getElementById('textoe').style.color="white";
    document.querySelector('.tipo2').style.borderColor="#593ae6";
    document.querySelector('.tipo1').style.backgroundColor="white";
    document.getElementById('imgu').style.display="block";
    document.getElementById('imgu2').style.display="none";
    document.getElementById('textou').style.color="#d2d1dd";
    document.querySelector('.tipo1').style.borderColor="#d2d1dd";
    menue();
}

function menue(){
    document.getElementById('respuestae').style.display="block";
    document.querySelector('.titulo').style.marginTop="0";
    document.querySelector('.descripcion').style.marginTop="-2vw";
    document.querySelector('.tipo').style.marginTop="6vw";
    document.querySelector('.tipo1').style.height="5vw";
    document.querySelector('.tipo2').style.height="5vw";
    
}


</script>
    
</body>
</html>

