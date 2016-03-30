<?php


class Criterions {

    function insertCriterions($criterionName, $criterionDesc){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "INSERT INTO acre_criterios(numero,descripcion) VALUES ('$criterionName', '$criterionDesc')";
        mysql_query($query);
    }

    function editCriterions($criterionId, $criterionName, $criterionDesc){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "UPDATE acre_criterios SET numero='$criterionName', descripcion='$criterionDesc'  WHERE idCriterio=$criterionId";
        mysql_query($query);
    }

    function removeCriterions($criterionId){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "DELETE FROM acre_criterios WHERE idCriterio=$criterionId";
        mysql_query($query);
    }

    
    function getCriterions(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $arr = [];
        $query = "Select * from acre_criterios";        
        $result = mysql_query($query);        
        while($row = mysql_fetch_assoc($result)) {
            $deb_arr = [];
            $id = $row['idCriterio'];
            
            $query1 = "select descripcion from acre_debilidades d inner join acre_debilidades_criterios dc
                       on d.idDebilidad = dc.idDebilidad
                       where dc.idCriterio = $id"; 
            
            $result1 = mysql_query($query1);
            while($row1 = mysql_fetch_assoc($result1)) {
                $deb_arr[] = array('desc'=>($row1['descripcion']));
            }

            $arr[] = array('id'=>$id,
                           'num'=>($row['numero']),
                           'desc'=>($row['descripcion']),
                           'debilidades'=>$deb_arr);
        }
        return($arr);
    }
}

$criterion = new Criterions();

if($_REQUEST['action'] == 'insert'){
    $criterion->insertCriterions($_REQUEST['criterionName'], $_REQUEST['criterionDesc']);
    $var = json_encode($criterion->getCriterions());
    print_r($var);
}
if($_REQUEST['action'] == 'edit'){
    $criterion->editCriterions($_REQUEST['criterionId'], $_REQUEST['criterionName'], $_REQUEST['criterionDesc']);
    $var = json_encode($criterion->getCriterions());
    print_r($var);
}
if($_REQUEST['action'] == 'remove'){
    $criterion->removeCriterions($_REQUEST['criterionId']);
    $var = json_encode($criterion->getCriterions());
    print_r($var);
}
if($_REQUEST['action'] == 'get'){
    $var = json_encode($criterion->getCriterions());
    print_r($var);
}
