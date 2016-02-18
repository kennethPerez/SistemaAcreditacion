<?php


class Components {    
    function insertComponents(){
    }

    function editComponents(){
    }

    function removeComponents(){
    }

    function getComponents(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        
        $arr = [];
        $query = "Select * from acre_componentes";        
        $result = mysql_query($query);        
        while($row = mysql_fetch_assoc($result)) {
            $deb_arr = [];
            
            $idC = $row['idComponente'];            
            $query1 = "Select descripcion from acre_debilidades where idComponente = $idC";            
            $result1 = mysql_query($query1);
            while($row1 = mysql_fetch_assoc($result1)) {
                $deb_arr[] = array('desc'=>($row1['descripcion']));
            }
            
            $idD = $row['idDimension'];
            $dimen = mysql_fetch_assoc(mysql_query("Select descripcion from acre_dimenciones where idDimension = $idD"));

            $arr[] = array('id'=>$idC,
                           'desc'=>($row['descripcion']),
                           'debilidades'=>$deb_arr,
                           'dimension'=>($dimen['descripcion']));
        }
        return($arr);
    }
}

$component = new Components();

if($_REQUEST['action'] == 'insert'){
    $component->insertComponents();
    $var = json_encode($component->getComponents());
    print_r($var);
}
if($_REQUEST['action'] == 'edit'){
    $component->editComponents();
    $var = json_encode($component->getComponents());
    print_r($var);
}
if($_REQUEST['action'] == 'remove'){
    $component->removeComponents();
    $var = json_encode($component->getComponents());
    print_r($var);
}
if($_REQUEST['action'] == 'get'){
    $var = json_encode($component->getComponents());
    print_r($var);
}





