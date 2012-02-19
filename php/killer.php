<?php
require_once('conexion.php');
function eliminacion($id){
	$query = "select nodo.pk as nodo, data.pk as data, descript.pk as descript
		from nodo, data, descript
		where nodo.data = data.pk
		and data.descr = descript.pk
		and nodo.id = '".$id."'";
	conectar();
	$query = mysql_query($query);
	$pks = mysql_fetch_array($query);
	$query = "DELETE FROM descript WHERE pk=".$pks["descript"];
	mysql_query($query);
	$query = "DELETE FROM data WHERE pk=".$pks["data"];
	mysql_query($query);
	$query = "DELETE FROM nodo WHERE pk=".$pks["nodo"];
	mysql_query($query);
	abortion($pks["nodo"]);
}
function abortion($pk){
	$query = "DELETE FROM children WHERE children=".$pk; //siempre soy hijo de un padre
	conectar();
	mysql_query($query);
	desconectar();
}
//TODO si elimino un padre, eliminar también sus hijos, o que pasen a ser hijos del abuelo
?>
