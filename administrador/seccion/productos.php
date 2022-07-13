<?php include("../template/cabecera.php");  ?>

<?php  
// Condicion ternaria

//enviamos los datos mediante condicion ternaria
$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";


/*AQUI COMENZAMOS LA CONEXION PARA LA BASE DE DATOS
NOTA: LOS DATOS ESTAN CONECTADOS CON OTRAS PARTES DE OTROS FORMULARIOS
$host="localhost";
$bd="sitio";
$usuario="root";
$contrasenia="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);
    if ($conexion) {
        echo "Conectado..... al sistema";
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}*/

include("../config/bd.php");

switch ($accion) {
    case "Agregar":
        //INSERT INTO `libros` (`id`, `nombre`, `imagen`) VALUES (NULL, 'aprender javascript', 'imagen2.jpg'); 
        $sentenciaSQL = $conexion->prepare("INSERT INTO libros (nombre, imagen) VALUES (:nombre,:imagen);");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);

        $fecha= new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        if ($tmpImagen!="") {
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
        }


        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->execute();
        header("Location:productos.php");

        break;
    
    case "Modificar":

        $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();

        if($txtImagen!="") {

            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
    
        //Aqui pregunta si existe una figura y SI es similar la borra sino la mantiene
        //y guarda las imagenes en una carpeta
            if (isset($libro["imagen"]) && ($libro["imagen"]!="imagen.jpg") ) {
                if (file_exists("../../img/".$libro["imagen"])) {
                    unlink("../../img/".$libro["imagen"]);
                }
            }




        $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        }
        header("Location:productos.php");

        break;

    case "Cancelar":
            header("Location:productos.php");
        break;

    case "Seleccionar":

        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre=$libro['nombre'];
        $txtImagen=$libro['imagen'];


        echo "Presionando boton Seleccionar";
        break;

    case "Borrar":

        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

    //Aqui pregunta si existe una figura y SI es similar la borra sino la mantiene
    //y guarda las imagenes en una carpeta
        if (isset($libro["imagen"]) && ($libro["imagen"]!="imagen.jpg") ) {
            if (file_exists("../../img/".$libro["imagen"])) {
                unlink("../../img/".$libro["imagen"]);
            }
        }

        $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        //echo "Presionando boton Borrar";

        header("Location:productos.php");

        break;
        //https://www.youtube.com/watch?v=IZHBMwGIAoI&t=174s
            //1:58:30

}

$sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


//HASTA AQUI TERMINA LA CONEXION

?>

<div class="col-md-5">

    <!-- b4-card-head-foot  -->
    <div class="card">
        <div class="card-header">
            datos de Libros
        </div>
       
    
       
    <div class="card-body">
            

    Formulario de agregar libros

    <!-- enctype="multipart/form-data" Permite manejar incluyendo formulario con imagenes -->
    <form method="POST" enctype="multipart/form-data">

    <div class = "form-group">
    <label for="txtID">ID</label>
    <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ingresar ID">
    </div>

    <div class = "form-group">
    <label for="txtNombre">Nombre</label>
    <input type="text" required class="form-control" value="<?php echo $txtNombre;?>" name="txtNombre" id="txtNombre" placeholder="ingresar nombre del libro">
    </div>

    <div class = "form-group">
    <label for="txtNombre">Imagen:</label>
    <br/>

    <!-- Captura esa imagen nueva o guardada y lo muestra en el form
        formulario de agregar libros -->
    <?php   

        if ($txtImagen!="") {        
    ?>
        <img class="img-thumbnail rounded" src="../../img/<?php  echo $txtImagen ?>" width="50" alt="">
    <?php  }  ?>

    <input type="file" required class="form-control" name="txtImagen" id="txtImagen" placeholder="ingresar imagen del libro">
    </div>



        <!-- b4-bgroup-default despliega del 29 al 33-->
        <div class="btn-group" role="group" aria-label="">
            <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
            <button type="submit" name="accion" <?php echo ($accion!=="Seleccionar")?"disabled":""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
            <button type="submit" name="accion" <?php echo ($accion!=="Seleccionar")?"disabled":""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
        </div>
    </form>
        </div>

    </div>
</div>

<div class="col-md-7">
    tabla de libros(mostrar los datos de los libros)

    <a href="reportes.php">Generar pdf</a>

    
    <!-- b4-table-default -->
    <table class="table table-bordered" id="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($listaLibros as $libro){ ?>
            <tr>
                <td><?php echo $libro['id'];      ?></td>
                <td><?php echo $libro['nombre'];  ?></td>

                <td>
                <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']; ?>" width="50" alt="">

                </td>
                

                <td>


                <form method="post">

                    <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>">
                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                </form>
            
            </td>




            </tr>
            <?php   }  ?>
        </tbody>

    </table>


</div>

<?php include("../template/pie.php");  ?>



<script>

                var tabla = document.querySelector("#tabla");

                var dataTable = new dataTable(tabla,{
                    perPage:3,
                    perPageSelect:[3,6,9,12]
                });

                

</script>