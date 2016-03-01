<?php


class Weaknesses {    
    function insertWeaknesses($criterionName){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn); 
        $query = "INSERT INTO acre_criterios(descripcion) VALUES ('$criterionName')";
        mysql_query($query);
    }
    
    function editWeaknesses($criterionId, $criterionName){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn); 
        $query = "UPDATE acre_criterios SET descripcion='$criterionName' WHERE idCriterio=$criterionId";
        mysql_query($query);
    }
    
    function removeWeaknesses($criterionId){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn); 
        $query = "DELETE FROM acre_criterios WHERE idCriterio=$criterionId";
        mysql_query($query);
    }
    
    function getWeaknesses(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $arr = [];
        
        $query = "SELECT d.idDebilidad, d.descripcion, c.descripcion as componente, di.descripcion as dimension, d.carreras_sedes, d.causas, d.objetivos, d.indicadores FROM acre_debilidades d inner JOIN acre_componentes c on d.idComponente = c.idComponente inner JOIN acre_dimenciones di on c.idDimension = di.idDimension";        
        
        $result = mysql_query($query);  
        
        while($row = mysql_fetch_assoc($result)) {
            $crit_arr = [];
            $id = $row['idDebilidad'];
            
            $query1 = "SELECT c.* from acre_criterios c inner join acre_debilidades_criterios dc on c.idCriterio = dc.idCriterio where dc.idDebilidad = $id"; 
            
            $result1 = mysql_query($query1);
            while($row1 = mysql_fetch_assoc($result1)) {
                $crit_arr[] = array('num'=>($row1['numero']));
            }

            $arr[] = array('id'=>$id,
                           'desc'=>$row['descripcion'],
                           'comp'=>$row['componente'],
                           'dime'=>$row['dimension'],
                           'carreras'=>$row['carreras_sedes'],
                           'causas'=>$row['causas'],
                           'objetivos'=>$row['objetivos'],
                           'indicadores'=>$row['indicadores'],
                           'criterios'=>$crit_arr);
        }
        return($arr);
    }
}

$weakness = new Weaknesses();

if($_REQUEST['action'] == 'insert'){
    $weakness->insertWeaknesses($_REQUEST['criterionName']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}
if($_REQUEST['action'] == 'edit'){
    $weakness->editWeaknesses($_REQUEST['criterionId'], $_REQUEST['criterionName']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}
if($_REQUEST['action'] == 'remove'){
    $weakness->removeWeaknesses($_REQUEST['criterionId']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}
if($_REQUEST['action'] == 'get'){
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}






