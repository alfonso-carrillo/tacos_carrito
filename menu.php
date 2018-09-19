<?php
session_start();
$product_ids = array();

if(filter_input(INPUT_POST, 'add_to_cart')){
    if(isset($_SESSION['shopping_cart'])){
        
        
        $count = count($_SESSION['shopping_cart']);
        
        
        $product_ids = array_column($_SESSION['shopping_cart'], 'id');
        
        if (!in_array(filter_input(INPUT_GET, 'id'), $product_ids)){
        $_SESSION['shopping_cart'][$count] = array
            (
                'id' => filter_input(INPUT_GET, 'id'),
                'nombre' => filter_input(INPUT_POST, 'nombre'),
                'precio' => filter_input(INPUT_POST, 'precio'),
                'quantity' => filter_input(INPUT_POST, 'quantity')
            );   
        }
        else {
            for ($i = 0; $i < count($product_ids); $i++){
                if ($product_ids[$i] == filter_input(INPUT_GET, 'id')){
                    $_SESSION['shopping_cart'][$i]['quantity'] += filter_input(INPUT_POST, 'quantity');
                }
            }
        }
        
    }
    else { 
        $_SESSION['shopping_cart'][0] = array
        (
            'id' => filter_input(INPUT_GET, 'id'),
            'nombre' => filter_input(INPUT_POST, 'nombre'),
            'precio' => filter_input(INPUT_POST, 'precio'),
            'quantity' => filter_input(INPUT_POST, 'quantity')
        );
    }
}

if(filter_input(INPUT_GET, 'action') == 'delete'){
    
    foreach($_SESSION['shopping_cart'] as $key => $product){
        if ($product['id'] == filter_input(INPUT_GET, 'id')){
            
            unset($_SESSION['shopping_cart'][$key]);
        }
    }
   
    $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Menu</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
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

		<font size=7 face="arial"><center>TODOS LOS MIERCOLES 2 X 1</center></font></p></h2>
		<hr>
		<div class="container">
        <?php

        $connect = mysqli_connect('localhost', 'id6647515_tacos_arre', 'eltac0suprem0', 'id6647515_tacos');
        $query = 'SELECT * FROM productos ORDER by id ASC';
        $result = mysqli_query($connect, $query);

        if ($result):
            if(mysqli_num_rows($result)>0):
                while($product = mysqli_fetch_assoc($result)):
                
                ?>
                <div class="col-sm-4 col-md-3" >
                    <form method="post" action="menu.php?action=add&id=<?php echo $product['id']; ?>">
                        <div class="products">
                            <img src="<?php echo $product['imagen']; ?>" class="img-responsive" />
                            <h4 class="text-info"><?php echo $product['nombre']; ?></h4>
                            <h4>$ <?php echo $product['precio']; ?></h4>
                            <input type="text" name="quantity" class="form-control" value="1" />
                            <input type="hidden" name="nombre" value="<?php echo $product['nombre']; ?>" />
                            <input type="hidden" name="precio" value="<?php echo $product['precio']; ?>" />
                            <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-info" value="Anadir al carrito" />
                        </div>
                    </form>
                </div>
                <?php
                endwhile;
            endif;
        endif;   
        ?>
        <div style="clear:both"></div>  
        <br />  
        <div class="table-responsive">  
        <table class="table">  
            <tr><th colspan="5"><h3>Detalles de la Orden</h3></th></tr>   
        <tr>  
             <th width="40%">Nombre del Producto</th>  
             <th width="10%">Cantidad</th>  
             <th width="20%">Precio</th>  
             <th width="15%">Total</th>  
             <th width="5%">Accion</th>  
        </tr>  
        <?php   
        if(!empty($_SESSION['shopping_cart'])):  
            
             $total = 0;  
        
             foreach($_SESSION['shopping_cart'] as $key => $product): 
        ?>  
        <tr>  
           <td><?php echo $product['nombre']; ?></td>  
           <td><?php echo $product['quantity']; ?></td>  
           <td>$ <?php echo $product['precio']; ?></td>  
           <td>$ <?php echo ($product['quantity'] * $product['precio']); ?></td>  
           <td>
               <a href="menu.php?action=delete&id=<?php echo $product['id']; ?>">
                    <div class="btn-danger">Quitar</div>
               </a>
           </td>  
        </tr>  
        <?php  
                  $total = $total + ($product['quantity'] * $product['precio']);  
             endforeach;  
        ?>  
        <tr>  
             <td colspan="3" align="right">Total</td>  
             <td align="right">$ <?php echo ($total); ?></td>  
             <td></td>  
        </tr>  
        <tr>
            <td colspan="5">
             <?php 
                if (isset($_SESSION['shopping_cart'])):
                if (count($_SESSION['shopping_cart']) > 0):
             ?>
                <a href="pedido.php" class="btn btn-primary">Hacer Pedido</a>
             <?php endif; endif; ?>
            </td>
        </tr>
        <?php  
        endif;
        ?>  
        </table>  
         </div>
        </div>

		<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>