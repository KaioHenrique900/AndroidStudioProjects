<?php
$response = array();

$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);


if (isset($_POST['email'])){
    $email = trim($_POST['email']);
    $query = pg_query($con, "SELECT id_usuario FROM usuario WHERE email='$email'");

    $rowEmail = pg_fetch_array($query);

    $userId = $rowEmail['id_usuario'];
    $queryCurtidas = pg_query($con, "SELECT * from curte where fk_id_usuario='$userId'");

    if (pg_num_rows($queryCurtidas)>0){
        while ($rowCurtidas = pg_fetch_array($queryCurtidas)) {
            $id_piada = $rowCurtidas['fk_id_piada'];
            $piadasCurtidas[]=$id_piada;
        }
        
    }

    $queryIdPiada = pg_query($con, "SELECT id_piada FROM piada WHERE fk_id_usuario = $userId");

    if (pg_num_rows($queryIdPiada)>0){
        $response["piadas"] = array();

        while ($row = pg_fetch_array($queryIdPiada)) {
            $id_piada = $row['id_piada'];

            $queryPiada = pg_query($con, "SELECT * FROM piada WHERE id_piada = $id_piada");
            $currentPiada = pg_fetch_array($queryPiada);

            $piada = array();
            $piada["id_piada"] = $currentPiada["id_piada"];
            $piada["titulo"] = $currentPiada["titulo"];
            $piada["descricao"] = $currentPiada["descricao"];
            $piada["data_publicacao"] = $currentPiada["data_publicacao"];
            $id_usuario = $currentPiada['fk_id_usuario'];

            $queryUser = pg_query($con, "SELECT nome FROM usuario WHERE id_usuario = $id_usuario");
            $nameUser = pg_fetch_array($queryUser);

            $piada["nome_usuario"] = $nameUser['nome'];

            $piadaId = $row['id_piada'];
            $queryCount = pg_query($con, "SELECT COUNT(fk_id_piada) as count from curte where fk_id_piada='$piadaId'");
            $resultCount = pg_fetch_array($queryCount);
            $piada["likes"] = $resultCount["count"];

            $piada["curtida"] = 0;
            for($i=0;$i<count($piadasCurtidas);$i++){
                if($piada["id_piada"] == $piadasCurtidas[$i]){
                    $piada["curtida"] = 1;
                }
            }

            array_push($response["piadas"], $piada);
       }
        $response["success"] = 1;
    }

    else{
        $response["success"] = 0;
        $response["error"] = "Usuário não possui piadas";
    }
}

else{
    $response["success"] = 0;
    $response["error"] = "Algo está errado";
}



pg_close($con);
echo json_encode($response);


?>