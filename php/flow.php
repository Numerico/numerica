<?php 
/*Debug
require_once('FirePHPCore/fb.php');
*/
require_once('conexion.php');
require_once('killer.php');

$flag = $_POST["flag"];
$rootz = $_POST["coord"];

switch($flag){
	case "insertar":
		$descript = $_POST["descript"];
		$url = $_POST["url"];
		$image = $_POST["image"];
		$icon = $_POST["icon"];
		$important = $_POST["important"];
			$descr = 0;
		$id = $_POST["id"];
		$name = $_POST["nombre"];
			$data = 0;
		if($image==null) $image="";
		if($icon==null) $icon="";
		insertar($descript, $url, $image, $icon, $important, $id, $name);
		break;
	case "edit":
		$descript = $_POST["descript"];
		errata($descript);
		break;
	case "nombra":
		$name = $_POST["nombre"];
		verbum($name);
		break;
    case "foto":
        $archivo = $_POST["archivo"];
        cambiarImagen($archivo);
        break;
	default:
		fb("you tryin' a crack this mtfk!");
		die("DANGER! This error might compromise your mental security!");
}

function insertar($descript, $url, $image, $icon, $important, $id, $name){
	//código sucio
	eliminacion('editar');
	//
	conectar();
	$query = "INSERT INTO descript (descr) VALUES ('".$descript."');";
	mysql_query($query);
	$descr = mysql_insert_id();
	$query = "INSERT INTO data (url, image, icon, important, descr) VALUES('".$url."', '".$image."', '".$icon."', '".$important."', ".$descr.");";
	mysql_query($query);
	$data = mysql_insert_id();
	$query = "INSERT INTO nodo (id, name, data) VALUES('".$id."', '".$name."', ".$data.");";
	mysql_query($query);
	$data = mysql_insert_id();
	if($GLOBALS["rootz"]!=null){
		$query = "SELECT pk FROM nodo WHERE id='".$GLOBALS["rootz"]."'";
		$query = mysql_query($query);
		$darth = mysql_fetch_array($query);
		$query = "INSERT INTO children (father, children) VALUES(".$darth["pk"].", ".$data.")";
		mysql_query($query);
		//mysql_error unique key
	}
	desconectar();
}

function errata($descript){;
	if($GLOBALS["rootz"]==null) return;
	$query = "UPDATE descript SET descr='".$descript."' WHERE pk=(SELECT descr FROM data WHERE pk=(SELECT data FROM nodo WHERE id='".$GLOBALS["rootz"]."'))";
	conectar();
	mysql_query($query);
	desconectar();
}

function verbum($name){
	if($GLOBALS["rootz"]==null) return;
	$query = "UPDATE nodo SET name='".$name."' WHERE id='".$GLOBALS["rootz"]."'"; //UNIQUE
	conectar();
	mysql_query($query);
	desconectar();
		//código sucio
		if($GLOBALS["rootz"]=="editar"){
			sinrespeto($name);
		}
}
//TODO: GUID
function sinrespeto($string){
	$process = strstr($string, ' ', true); //y si ya esta, junta hasta el siguiente
	if($process != null) $string = $process; 
	$string = strtolower($string);
	$query = "UPDATE nodo SET id='".$string."' WHERE id='editar'";
	conectar();
	mysql_query($query);
	desconectar();
}

function cambiarImagen($archivo){
    //TODO: si ya tiene archivo, borrarlo
    $archivo ="ajaxUpload/server/uploads/".$archivo;
    $query = "UPDATE data SET image = '".$archivo."',
                              icon = '".$archivo."'
              WHERE pk=(SELECT data FROM nodo WHERE id='".$GLOBALS["rootz"]."')"; //por ahora icon=image
    conectar();
    mysql_query($query);
    desconectar();
}
?>


