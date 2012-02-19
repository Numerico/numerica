
<?php
function conectar(){
	mysql_connect("localhost", "root", "anikulapo") or die("Error de usuario, host o contraseña");
	mysql_select_db("numerica") or die("Nombre de la _db_ incorrecto");
	mysql_query("SET NAMES UTF8");
}
function desconectar(){
	mysql_close();
}
?>
