<?php
/**DEBUG
require_once('FirePHPCore/fb.php');
*/
require_once('conexion.php');

/*$recibido = json_decode($GET['jiho']);*/

//GLOBALES
$profundidad=800;
$veces = 0;

$qHome = "select pk from nodo where id='numerica'";
conectar();
$home = mysql_query($qHome);
$home = mysql_result($home, 0);
desconectar();

$encodigo = queryArbol($home); 
function queryArbol($pk){
	$GLOBALS["veces"]++;

	$query = "select pk, name, id, data from nodo";
	if($pk!=null) $query = $query." where pk=".$pk;	
	conectar();
	$encodigo = mysql_query($query);
	$encodigo = mysql_fetch_array($encodigo, MYSQL_ASSOC);
	desconectar();
	//
	if($encodigo["data"]!=null){
		$encodigo["data"] = queryData($encodigo["data"]);
	}
	//
	$child = queryHijos($encodigo["pk"]);
		//ojo que la profundidad debe resetearse por nodo, así funciona con una sola raíz...
		if($child!=null && $GLOBALS["veces"] <= $GLOBALS["profundidad"]){
				$n = count($child);
				$matrix = array($n);
				for($x=0; $x<$n; $x++){
					//RECURSIVIDAD
					$matrix[$x] = queryArbol($child[$x]); 
				}
				$encodigo["children"] = $matrix;
		}
		else{
			$encodigo["children"]= array();
		}
	unset($encodigo["pk"]);
	return $encodigo;
}
function queryData($info){
	conectar();
	$data = mysql_query("select important, url, icon, image, descr from data where pk=".$info);
	$data = mysql_fetch_array($data, MYSQL_ASSOC);
	desconectar();
	if($data["important"]=="Y") $data["important"]= true;
	else $data["important"] = false;
	$desc = $data["descr"]; //desc!
	if($desc != null){
		conectar();
		$descr = mysql_query("select descr from descript where pk=".$desc);
		$descr = mysql_fetch_array($descr, MYSQL_ASSOC);
		desconectar();
		//multiples?
		$arreglo = array(1); //formato
		$arreglo[0] = $descr["descr"];
		$data["descr"] = $arreglo;
	}
	$data["desc"] = $data["descr"];
	unset($data["descr"]);
	return $data;
}
function queryHijos($papi){
	conectar();
	$q = "select children from children where father=".$papi;
	$hijos = mysql_query($q);
	$n = mysql_num_rows($hijos);
	if($n != 0){
		$retorno = array($n);
		for($i=0; $i<$n; $i++){
			$fila = mysql_fetch_array($hijos);
			$retorno[$i] = $fila[0];
		}
	}
	else return;
	desconectar();
	return $retorno;
}
$encodigo = json_encode($encodigo);
$encodigo = stripslashes($encodigo);
$encodigo = "mapData(".$encodigo.")"; //no funciona remoto
die($encodigo);
?>
