<?php


class Weaknesses {

    function editCausesWeaknesses($id, $causes){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $query = "UPDATE acre_debilidades SET causas='$causes' WHERE idDebilidad=$id";
        mysql_query($query);
    }

    function editLocationsWeaknesses($id, $locations){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $query = "UPDATE acre_debilidades SET carreras_sedes='$locations' WHERE idDebilidad=$id";
        mysql_query($query);
    }

    function editCriterionsWeaknesses($id, $criterions){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);

        $query = "delete from acre_debilidades_criterios where idDebilidad = $id";
        mysql_query($query);

        if($criterions != "")
        {
            $cr = explode(",",$criterions);
            foreach ($cr as $value) {

                $query1 = "Select idCriterio from acre_criterios where numero = '$value'";
                mysql_query($query1);
                $result1 = mysql_query($query1);
                $row1 = mysql_fetch_assoc($result1);
                $idC = $row1['idCriterio'];

                $query = "INSERT INTO acre_debilidades_criterios(idDebilidad, idCriterio) VALUES ($id,$idC)";
                mysql_query($query);
            }
        }
    }

    function getWeaknesses(){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $arr = [];

        $query = "SELECT d.idDebilidad, d.descripcion, c.descripcion as componente, c.idComponente as idC, di.descripcion as dimension, d.carreras_sedes, d.causas FROM acre_debilidades d inner JOIN acre_componentes c on d.idComponente = c.idComponente inner JOIN acre_dimenciones di on c.idDimension = di.idDimension";

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
                           'idC'=>$row['idC'],
                           'dime'=>$row['dimension'],
                           'carreras'=>$row['carreras_sedes'],
                           'causas'=>$row['causas'],
                           'criterios'=>$crit_arr);
        }
        return($arr);
    }


    function insertWeaknesses($weaknessName, $idC){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "INSERT INTO acre_debilidades(descripcion, idComponente) VALUES ('$weaknessName', $idC)";
        mysql_query($query);
    }

    function editWeaknesses($weaknessId, $weaknessName, $idC){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "UPDATE acre_debilidades SET descripcion='$weaknessName', idComponente=$idC WHERE idDebilidad=$weaknessId";
        mysql_query($query);
    }

    function removeWeaknesses($weaknessId){
        include '../bd/acceso.php';
        $conn = mysql_connect ($host, $user, $pass);
        mysql_select_db($db, $conn);
        $query = "DELETE FROM acre_debilidades WHERE idDebilidad=$weaknessId";
        mysql_query($query);
    }
}

$weakness = new Weaknesses();

if($_REQUEST['action'] == 'editCauses'){
    $weakness->editCausesWeaknesses($_REQUEST['weaknessId'], $_REQUEST['causes']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}

if($_REQUEST['action'] == 'editLocations'){
    $weakness->editLocationsWeaknesses($_REQUEST['weaknessId'], $_REQUEST['locations']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}

if($_REQUEST['action'] == 'editCriterions'){
    $weakness->editCriterionsWeaknesses($_REQUEST['weaknessId'], $_REQUEST['criterions']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}

if($_REQUEST['action'] == 'remove'){
    $weakness->removeWeaknesses($_REQUEST['weaknessId']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}
if($_REQUEST['action'] == 'insert'){
    $weakness->insertWeaknesses($_REQUEST['weaknessName'], $_REQUEST['idC']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}
if($_REQUEST['action'] == 'edit'){
    $weakness->editWeaknesses($_REQUEST['weaknessId'], $_REQUEST['weaknessName'], $_REQUEST['idC']);
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}
if($_REQUEST['action'] == 'get'){
    $var = json_encode($weakness->getWeaknesses());
    print_r($var);
}
