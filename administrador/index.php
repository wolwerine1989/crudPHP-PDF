

<?php
  session_start();

    if ($_POST) {

      if(($_POST['usuario']=="develoteca")&&($_POST['contrasenia']=="sistema")){
      
      $_SESSION['usuario']="ok";
      $_SESSION['nombreUsuario']="Develoteca";

      header('Location:inicio.php');

      }else{
        $mensaje="Error al ingresar";

          }
      }
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>


  <div class="container">
    <div class="row">

    <div class="col-md-4">
        
    </div>

<div class="col-md-4">
<br><br><br>
    <div class="card">
        <div class="card-header">
        </div>

        <div class="card-body">

          <?php if (isset($mensaje)) {  ?>
          <!-- b4-alert-default -->
          <div class="alert alert-danger" role="alert">
          <?php echo $mensaje  ?> 
            </div>
            <?php } ?>


            <form method="POST">
            <div class = "form-group">
            <label >Usuario</label>
            <input type="text" class="form-control" name="usuario" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">Nunca reveles tu correo, ni tu contraseña.</small>
            </div>


            <div class="form-group">
            <label for="exampleInputPassword1">Contraseña</label>
            <input type="text" class="form-control" name="contrasenia" placeholder="Password">
            </div>


            <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
        </div>
    </div>
</div>

  
</div>
  </div>

  </body>
</html>