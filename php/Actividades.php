<?php

function existe($id, $lista){
    foreach ($lista as $value) {
        if($value['idIndicador'] == $id) {
            return true;
        }
    }
    return false;
}

class Activities {
    function insertActivities($idDebilidad, $idObjetivo, $fecha, $encargado, $actividad){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $query = "INSERT INTO acre_actividades(idDebilidad, idObjetivo, fecha, encargado, actividad) VALUES ($idDebilidad, $idObjetivo, '$fecha', '$encargado', '$actividad')";
        mysql_query($query);
    }

    function editActivities($idActividad,$idDebilidad, $idObjetivo, $fecha, $encargado, $actividad){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "UPDATE acre_actividades set idDebilidad=$idDebilidad, idObjetivo=$idObjetivo, fecha='$fecha', encargado='$encargado', actividad='$actividad' WHERE idActividad = $idActividad";
        mysql_query($query);

        $query = "DELETE FROM acre_actividades_indicadores WHERE idActividad = $idActividad";
        mysql_query($query);

    }

    function act_ind($idActividad, $Idindicador){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $query = "INSERT INTO acre_actividades_indicadores(idActividad, idIndicador) VALUES ($idActividad, $Idindicador)";
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

    function getI($idActividad, $idObjetivo){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);


        if($idActividad == ""){
            $arrIndicadores = [];
            $query = "SELECT * FROM acre_indicadores where idObjetivo = $idObjetivo";
            $resultIndicadores = mysql_query($query);
            while($rowIndicadores = mysql_fetch_assoc($resultIndicadores)) {


                $arrIndicadores[] =  array(
                    "idIndicador"=>$rowIndicadores["idIndicador"],
                    "indicador"=>$rowIndicadores["indicador"],
                    "selected"=>"false"
                );

            }
            return($arrIndicadores);
        }
        else{
            $arrSelect = [];
            $query = "SELECT idIndicador FROM acre_actividades_indicadores WHERE idActividad = $idActividad";
            $resultSelect = mysql_query($query);
            while($rowSelected = mysql_fetch_assoc($resultSelect)) {
                $arrSelect[] = $rowSelected;
            }

            $arrIndicadores = [];
            $query = "SELECT * FROM acre_indicadores where idObjetivo = $idObjetivo";
            $resultIndicadores = mysql_query($query);
            while($rowIndicadores = mysql_fetch_assoc($resultIndicadores)) {

                if(existe($rowIndicadores['idIndicador'],$arrSelect)){
                    $arrIndicadores[] =  array(
                        "idIndicador"=>$rowIndicadores["idIndicador"],
                        "indicador"=>$rowIndicadores["indicador"],
                        "selected"=>"true"
                    );
                }
                else{
                    $arrIndicadores[] =  array(
                        "idIndicador"=>$rowIndicadores["idIndicador"],
                        "indicador"=>$rowIndicadores["indicador"],
                        "selected"=>"false"
                    );
                }

            }
            return($arrIndicadores);
        }
    }

    function getActivities(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $arr = [];
        $query = "SELECT a.idActividad, a.idDebilidad, d.idComponente, a.idObjetivo, a.idActividad, a.fecha, a.encargado, a.actividad, r.descripcion as dimension, c.descripcion as componente, d.descripcion as debilidad, d.causas as causa, o.objetivo as objetivo
            FROM acre_actividades a
            INNER JOIN acre_debilidades d ON a.idDebilidad = d.idDebilidad
            INNER JOIN acre_componentes c ON d.idComponente = c.idComponente
            INNER JOIN acre_dimenciones r ON r.idDimension = c.idDimension
            INNER JOIN acre_objetivos o ON o.idObjetivo = a.idObjetivo";

        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)) {

            $idActividad = $row["idActividad"];
            $idObjetivo = $row["idObjetivo"];

            $arrSelect = [];
            $query = "SELECT idIndicador FROM acre_actividades_indicadores WHERE idActividad = $idActividad";
            $resultSelect = mysql_query($query);
            while($rowSelected = mysql_fetch_assoc($resultSelect)) {
                $arrSelect[] = $rowSelected;
            }

            $arrIndicadores = [];
            $query = "SELECT * FROM acre_indicadores where idObjetivo = $idObjetivo";
            $resultIndicadores = mysql_query($query);
            while($rowIndicadores = mysql_fetch_assoc($resultIndicadores)) {

                if(existe($rowIndicadores['idIndicador'],$arrSelect)){
                    $arrIndicadores[] =  array(
                        "idIndicador"=>$rowIndicadores["idIndicador"],
                        "indicador"=>$rowIndicadores["indicador"],
                        "selected"=>"true"
                    );
                }
                else{
                    $arrIndicadores[] =  array(
                        "idIndicador"=>$rowIndicadores["idIndicador"],
                        "indicador"=>$rowIndicadores["indicador"],
                        "selected"=>"false"
                    );
                }

            }

            $arr[] = array(
                "idActividad"=> $idActividad,
                "idDebilidad"=> $row["idDebilidad"],
                "idComponente"=> $row["idComponente"],
                "idObjetivo"=> $idObjetivo,
                "fecha"=> $row["fecha"],
                "encargado"=> $row["encargado"],
                "actividad"=> $row["actividad"],
                "dimension"=> $row["dimension"],
                "componente"=> $row["componente"],
                "debilidad"=> $row["debilidad"],
                "causa"=> $row["causa"],
                "objetivo"=> $row["objetivo"],
                "indicadores" => $arrIndicadores
            );
        }

        return($arr);
    }
}

$activities = new Activities();
if($_REQUEST['action'] == 'insert'){

    $activities->insertActivities($_REQUEST['idDebilidad'], $_REQUEST['idObjetivo'], $_REQUEST['fecha'], $_REQUEST['encargado'], $_REQUEST['actividad']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'update'){

    $activities->editActivities($_REQUEST['idActividad'], $_REQUEST['idDebilidad'], $_REQUEST['idObjetivo'], $_REQUEST['fecha'], $_REQUEST['encargado'], $_REQUEST['actividad']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'act_ind'){
    $activities->act_ind($_REQUEST['idActividad'], $_REQUEST['Idindicador']);
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
    $var = json_encode($activities->getI($_REQUEST['idA'],$_REQUEST['idO']));
    print_r($var);
}
