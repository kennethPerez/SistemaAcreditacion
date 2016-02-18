<?php

include '../bd/acceso.php';
$conn = mysql_connect ($host, $user, $pass);
mysql_select_db($db, $conn);

$user = $_REQUEST['user'];
$pass = md5($_REQUEST['pass']);

$query = "select p.idProfesor, pu.tipo, p.cedula, p.nombre 
          from profesores p
          inner join profesores_usuarios pu on p.idProfesor = pu.idProfesor
          inner join usuarios u on pu.idusuario = u.idUsuario
          where usuario = '$user' and clave = '$pass'";

$result = mysql_query($query);        
$row = mysql_fetch_assoc($result);
$array = array('idProfesor' => $row['idProfesor'],
               'cedula' => $row['cedula'],
               'tipo' => $row['tipo'],
               'nombre' => utf8_encode($row['nombre']));




print_r(json_encode($array));
