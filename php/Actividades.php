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

            $arrTareas = [];
            $query = "SELECT t.idActividad, t.idTarea, t.descripcion, p.nombre, p.idProfesor, t.inicio, t.final, t.estado, t.observaciones from acre_tareas t
                        INNER JOIN profesores p on t.idProfesor = p.idProfesor
                        where t.idActividad = $idActividad";
            $resultTareas = mysql_query($query);
            while($rowTareas = mysql_fetch_assoc($resultTareas)) {
                $arrTareas[] = $rowTareas;
            }

            $arrArchivos = [];
            $query = "SELECT nombre from acre_archivos where idActividad = $idActividad";
            $resultArchivos = mysql_query($query);
            while($rowArchivos = mysql_fetch_assoc($resultArchivos)) {
                $arrArchivos[] = $rowArchivos;
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
                "indicadores" => $arrIndicadores,
                "tareas" => $arrTareas,
                "archivos" => $arrArchivos
            );
        }

        return($arr);
    }

    function getProfesores(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $profesores = [];
        $query = "SELECT idProfesor, nombre FROM profesores where codCarrera = 'CA'";
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)) {
            $profesores[] = $row;
        }
        return($profesores);
    }

    function removeTask($taskId){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "DELETE FROM acre_tareas WHERE idTarea=$taskId";
        mysql_query($query);
    }

    function insertTask($idActividad,$descripcion,$idProfesor,$inicio,$final,$estado){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $query = "INSERT INTO acre_tareas(idActividad,descripcion,idProfesor,inicio,final,estado)
                   VALUES ($idActividad,'$descripcion',$idProfesor,'$inicio','$final','$estado')";
        mysql_query($query);
    }

    function editTask($idTask,$descripcion,$idProfesor,$inicio,$final,$estado){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "UPDATE acre_tareas SET descripcion='$descripcion',idProfesor=$idProfesor,inicio='$inicio',final='$final',estado='$estado' WHERE idTarea = $idTask";
        mysql_query($query);
    }

    function upload(){

        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $path = str_replace("\\", "/", substr(getcwd(),0, strlen(getcwd())-3));
        $target_dir = $path."Documentos/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        $id = $_REQUEST['idActividad'];
        $n = basename($_FILES["file"]["name"]);
        $query = "INSERT INTO acre_archivos(idActividad,nombre) VALUES ($id, '$n')";
        mysql_query($query);
    }

    function editOb($idTask,$observaciones){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "UPDATE acre_tareas SET observaciones='$observaciones' WHERE idTarea = $idTask";
        mysql_query($query);
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

if($_REQUEST['action'] == 'upload'){
    $activities->upload();
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

if($_REQUEST['action'] == 'getProfesores'){
    $var = json_encode($activities->getProfesores());
    print_r($var);
}


if($_REQUEST['action'] == 'removeTask'){
    $activities->removeTask($_REQUEST['taskId']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'insertTask'){
    $activities->insertTask($_REQUEST['idActividad'],$_REQUEST['descripcion'],$_REQUEST['idProfesor'],$_REQUEST['inicio'],$_REQUEST['final'],$_REQUEST['estado']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'updateTask'){
    $activities->editTask($_REQUEST['idTask'],$_REQUEST['descripcion'],$_REQUEST['idProfesor'],$_REQUEST['inicio'],$_REQUEST['final'],$_REQUEST['estado']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}

if($_REQUEST['action'] == 'updateOb'){
    $activities->editOb($_REQUEST['idTarea'],$_REQUEST['observaciones']);
    $var = json_encode($activities->getActivities());
    print_r($var);
}
