<?php


class Activities {
    function insertActivities($idDebilidad, $idObjetivo, $idIndicador, $fecha, $encargado, $actividad){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        if($idIndicador == '')
            $query = "INSERT INTO acre_actividades(idDebilidad, idObjetivo, fecha, encargado, actividad) VALUES ($idDebilidad, $idObjetivo, '$fecha', '$encargado', '$actividad')";
        else
            $query = "INSERT INTO acre_actividades(idDebilidad, idObjetivo, idIndicador, fecha, encargado, actividad) VALUES ($idDebilidad, $idObjetivo, $idIndicador, '$fecha', '$encargado', '$actividad')";

        mysql_query($query);
    }

    function editActivities($idActividad,$idDebilidad, $idObjetivo, $idIndicador, $fecha, $encargado, $actividad){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "UPDATE acre_actividades set idDebilidad=$idDebilidad, idObjetivo=$idObjetivo, idIndicador=$idIndicador, fecha='$fecha', encargado='$encargado', actividad='$actividad' WHERE idActividad = $idActividad";
        mysql_query($query);
    }

    function removeActivities($activityId){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "DELETE FROM acre_actividades WHERE idActividad=$activityId";
        mysql_query($query);
    }

    function getO($id){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $ob_arr = [];
        $query2 = "select idObjetivo, objetivo from acre_objetivos WHERE idDebilidad = $id";
        $result2 = mysql_query($query2);
        while($row2 = mysql_fetch_assoc($result2)) {
            $ob_arr[] = $row2;
        }
        return($ob_arr);
    }

    function getI($id){
            include '../bd/acceso.php';
            $conn = mysql_connect ($host, $user, $pass);
            mysql_select_db($db, $conn);

            $ob_arr = [];
            $query2 = "select idIndicador, indicador from acre_indicadores WHERE idObjetivo = $id";
            $result2 = mysql_query($query2);
            while($row2 = mysql_fetch_assoc($result2)) {
                $ob_arr[] = $row2;
            }
            return($ob_arr);
        }

    function getActivities(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $arr = [];
        $query = "SELECT a.idActividad, a.idDebilidad, d.idComponente, a.idObjetivo, a.idIndicador, a.idActividad, a.fecha, a.encargado, a.actividad, r.descripcion as dimension, c.descripcion as componente, d.descripcion as debilidad, d.causas as causa, o.objetivo as objetivo , i.indicador as indicador
                  FROM acre_actividades a
                  INNER JOIN acre_debilidades d ON a.idDebilidad = d.idDebilidad
                  INNER JOIN acre_componentes c ON d.idComponente = c.idComponente
                  INNER JOIN acre_dimenciones r ON r.idDimension = c.idDimension
                  INNER JOIN acre_objetivos o ON o.idObjetivo = a.idObjetivo
                  INNER JOIN acre_indicadores i ON i.idIndicador = a.idIndicador";

        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)) {
            $arr[] = $row;
        }

        $query = "SELECT a.idActividad, a.idDebilidad, d.idComponente, a.idObjetivo, a.idIndicador, a.idActividad, a.fecha, a.encargado, a.actividad, r.descripcion as dimension, c.descripcion as componente, d.descripcion as debilidad, d.causas as causa, o.objetivo as objetivo , '' as indicador FROM acre_actividades a INNER JOIN acre_debilidades d ON a.idDebilidad = d.idDebilidad INNER JOIN acre_componentes c ON d.idComponente = c.idComponente INNER JOIN acre_dimenciones r ON r.idDimension = c.idDimension INNER JOIN acre_objetivos o ON o.idObjetivo = a.idObjetivo where a.idIndicador is null";

        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)) {
            $arr[] = $row;
        }

        return($arr);
    }
}

$activities = new Activities();
if($_REQUEST['action'] == 'insert'){

    $activities->insertActivities($_REQUEST['idDebilidad'], $_REQUEST['idObjetivo'], $_REQUEST['idIndicador'], $_REQUEST['fecha'], $_REQUEST['encargado'], $_REQUEST['actividad']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'update'){

    $activities->editActivities($_REQUEST['idActividad'], $_REQUEST['idDebilidad'], $_REQUEST['idObjetivo'], $_REQUEST['idIndicador'], $_REQUEST['fecha'], $_REQUEST['encargado'], $_REQUEST['actividad']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'get'){
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'remove'){
    $activities->removeActivities($_REQUEST['activityId']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'getO'){
    $var = json_encode($activities->getO($_REQUEST['idD']));
    print_r($var);
}

if($_REQUEST['action'] == 'getI'){
    $var = json_encode($activities->getI($_REQUEST['idI']));
    print_r($var);
}
