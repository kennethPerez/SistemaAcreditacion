<?php


class Dimentions {    
    function insertDimentions($dimentionName){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn); 
        $query = "INSERT INTO acre_dimenciones(descripcion) VALUES ('$dimentionName')";
        mysql_query($query);
    }
    
    function editDimentions($dimentionId, $dimentionName){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn); 
        $query = "UPDATE acre_dimenciones SET descripcion='$dimentionName' WHERE idDimension=$dimentionId";
        mysql_query($query);
    }
    
    function removeDimentions($dimentionId){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn); 
        $query = "DELETE FROM acre_dimenciones WHERE idDimension=$dimentionId";
        mysql_query($query);
    }
    
    function getDimention(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $arr = [];
        $query = "Select * from acre_dimenciones";        
        $result = mysql_query($query);        
        while($row = mysql_fetch_assoc($result)) {
            $comp_arr = [];
            $id = $row['idDimension'];
            $query1 = "Select descripcion from acre_componentes where idDimension = $id";        
            $result1 = mysql_query($query1);
            while($row1 = mysql_fetch_assoc($result1)) {
                $comp_arr[] = array('desc'=>($row1['descripcion']));
            }

            $arr[] = array('id'=>$id,
                           'desc'=>($row['descripcion']),
                           'comp'=>$comp_arr);
        }
        return($arr);
    }
}

$dimention = new Dimentions();

if($_REQUEST['action'] == 'insert'){
    $dimention->insertDimentions($_REQUEST['dimentionName']);
    $var = json_encode($dimention->getDimention());
    print_r($var);
}
if($_REQUEST['action'] == 'edit'){
    $dimention->editDimentions($_REQUEST['dimentionId'], $_REQUEST['dimentionName']);
    $var = json_encode($dimention->getDimention());
    print_r($var);
}
if($_REQUEST['action'] == 'remove'){
    $dimention->removeDimentions($_REQUEST['dimentionId']);
    $var = json_encode($dimention->getDimention());
    print_r($var);
}
if($_REQUEST['action'] == 'get'){
    $var = json_encode($dimention->getDimention());
    print_r($var);
}





