<?php 

session_start();

date_default_timezone_set("America/Monterrey");

include_once('conexion.php');

$fecha = date("Y-m-d H:i:s");
$fecha2 = date("d-m-Y");
$diasemana=date("w",strtotime($fecha2));

$error = false;
if (isset($_POST['confirmar'])) {

	$cliente=$_POST['cliente'];
    $cliente = strip_tags($cliente);
    $cliente = htmlspecialchars($cliente);

    $dir2=$_POST['dir'];
    $dir2 = strip_tags($dir2);
    $dir2 = htmlspecialchars($dir2);

    $total2=$_POST['total'];
    $total2 = strip_tags($total2);
    $total2 =htmlspecialchars($total2);

$email2=$_POST['email'];
$email2=strip_tags($email2);
$email2=htmlspecialchars($email2);

$tel2=$_POST['tel'];
$tel2=strip_tags($tel2);
$tel2=htmlspecialchars($tel2);

   
    if (empty($cliente)){
      $error = true;
      $errorcliente = 'Rellena Campo';
    }
    
    if (empty($dir2)){
      $error = true;
      $errordir = 'Rellena Campo';
    }

    if(!filter_var($email2, FILTER_VALIDATE_EMAIL)){
      $error = true;
      $erroremail = 'Introduce un Email valido';
    }

    if (empty($tel2)){
      $error = true;
      $errortel = 'Rellena Campo';
    }

    if (empty($total2)){
      $error = true;
      $errortotal = 'Pide aunque sea un producto para continuar';
    }

$total3 = 0;

foreach($_SESSION['shopping_cart'] as $element):



	$nombre = $element['nombre'];
    $precio = $element['precio'];
    $quantity = $element['quantity'];
	

if ($diasemana == 3) {

	$total3= $total3 + (ceil($element['quantity']  / 2) * $element['precio']);
} else {

     	$total3= $total3 + ($element['quantity'] * $element['precio']); 
	}

if(!$error){
      echo '';
      $sql = "insert into pedidos(Nombre,Direccion,Email,Telefono, Comida,Cantidad,Total,Fecha)
                   values('$cliente', '$dir2', '$email2', '$tel2', '$nombre','$quantity','$total3','$fecha')";
                   if(mysqli_query($conn, $sql)){
                    $successMsg = 'SU PEDIDO A SIDO TOMADO EN UN MOMENTO LE LLEGARA A SU DOMICILIO GRACIAS!!!!';
                   
                   }else{
                    echo 'Error '.mysqli_error($conn);
                   }
    }
    endforeach;
 session_destroy();
}

 ?>


<html>
<head>
<title> Pedidos  </title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/estilos.css">

  <script>
function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
</script>

</head>
<body background="Picture/30316.png">
<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container">
            </div>
                <!-- Inicia Menú -->
            <div class="collapse navbar-collapse " id="navegacion-ga">
                <div class="container-fluid" >
                    <ul class="nav navbar-nav etiqueta-header">
                <li><a href="index.html"><img src="images/taquito.png" class="img-responsive logo" alt=""> </a></li>
                       <li class="direccion"><a href="acerca-de.html" style="color: #fff;">Acerca de</a></li>
                        <li class="direccion4"><a href="menu.php" style="color: #fff;">Menu</a></li>
                        <li class="direccion5"><a href="ubicacion.html" style="color: #fff;">Ubicacion</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    <div style="clear:both"></div>  
        <br />  
        <div class="table-responsive"> 
        <form method = "post" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete = "off"> 
        <table class="table">  
            <tr><th colspan="5"><h3>Detalles de la Orden</h3></th></tr>   
        <tr>  
             <th width="40%">Nombre del Producto</th>  
             <th width="10%">Cantidad</th>  
             <th width="20%">Precio</th>  
             <th width="15%">Total</th>  
        </tr> 

        <?php 

if ($diasemana == 3) {
    echo '<script language="javascript">alert("Hoy es miercoles de 2 x 1!!!! aprovecha nuestras ofertas");</script>';
}

        if(!empty($_SESSION['shopping_cart'])):  
            
             $total = 0;  
        
             foreach($_SESSION['shopping_cart'] as $key => $product): 

             	if ($diasemana == 3) {

	$total= $total + (ceil($product['quantity']  / 2) * $product['precio']);
} else {

     	$total= $total + ($product['quantity'] * $product['precio']); 
	}
	

        ?>  
        <tr>  
           <td><input type = "text" name = "nombre" value="<?php echo $product['nombre']; ?>" class = "form-control" autocomplete="off" readonly></td>  
           <td><input type = "text" name = "quantity" value="<?php echo $product['quantity']; ?>" class = "form-control" autocomplete="off" readonly></td>  
           <td><input type = "text" name = "precio" value="$ <?php echo $product['precio']; ?>" class = "form-control" autocomplete="off" readonly></td>  
           <td>$ <?php echo ($product['quantity'] * $product['precio']); ?></td>
        </tr>  
        <?php  

    endforeach;

        ?>  
        <tr>  
             <td colspan="3" align="right">Total</td>  
             <td align="right"><input type = "text" name = "total" value="$ <?= $total; ?>" class = "form-control" autocomplete="off" readonly> 
        	<span class = "text-danger"><?php if (isset($errortotal)) echo $errortotal; ?></span></td>  
             <td></td>  
        </tr> 

        <?php  
        endif;
        ?>  
        </table>  
         </div>
        </div>

        <div class ="container">
    <div style = "...">
     <center><h2> Confirmar Compra </h2></center>
  
  <?php
        if(isset($successMsg)){
          ?>

  <div class = "alert alert-success">
            <span class = " glyphicon glyphicon-info-sign"></span>
            <?php echo $successMsg; ?>
            </div>

          <?php
        }
        ?>

     <div class = "form-group">
     <label for = "cliente" class = "control-label"> Nombre</label>
     <input type = "text" name = "cliente" class = "form-control" autocomplete="off" placeholder="Coloca tu nombre"> 
     <span class = "text-danger"><?php if (isset($errorcliente)) echo $errornombre; ?></span>  
    </div>

  <div class = "form-group">
     <label for = "dir" class = "control-label"> Direccion</label>
     <input type = "text" name = "dir" class = "form-control" autocomplete="off" placeholder="Coloca tu domicilio">  
     <span class = "text-danger"><?php if (isset($errordir)) echo $errordir; ?></span> 
    </div>

    <div class = "form-group">
     <label for = "email" class = "control-label"> Email</label>
     <input type = "email" name = "email" class = "form-control" autocomplete="off" placeholder="Coloca tu E-mail">  
     <span class = "text-danger"><?php if (isset($erroremail)) echo $erroremail; ?></span> 
    </div>

    <div class = "form-group">
     <label for = "tel" class = "control-label"> Numero Telefonico (MAXIMO 10 DIGITOS)</label>
     <input type = "text" name = "tel" class = "form-control" maxlength="10" onkeypress="return valida(event)" autocomplete="off" placeholder="Coloca tu numero telefonico"> 
     <span class = "text-danger"><?php if (isset($errortel)) echo $errortel; ?></span>  
    </div>

    <div class = "form-group">
    <label for = "fechita" class = "control-label">Fecha y Hora</label>
    <input type = "text" name = "fechita" class = "form-control" value="<?= $fecha; ?>" autocomplete="off" readonly>   
    </div>

    <div class = "form-group">
        
   <center>
       <a href="menu.php" class="btn btn-primary" align="right">Añadir mas pruductos</a>
       <input type = "submit" name = "confirmar" value = "Confirmar" class = "btn btn-primary"></center>
    </form>
    
    

    </div>
    </div>


    <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>