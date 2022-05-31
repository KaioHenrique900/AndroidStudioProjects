<?php
$response = array();

$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);

if (isset($_POST['id_usuario'])){
    $id_usuario = trim($_POST['id_usuario']));
    $queryUser = pg_query($con, "SELECT * FROM usuario WHERE id_usuario=$id_usuario");

    if (pg_num_rows($queryUser)>0){
        $response["user"] = Array();
        $user = Array();
        $row = pg_fetch_array($queryUser);

        $user['id_usuario'] = $row['id_usuario'];
        $user['nome'] = $row['nome'];
        $user['email'] = $row['email'];
        $user['senha'] = $row['senha'];
        $user['data_nasc'] = $row['data_nasc'];

        $response["user"] = $user;
        $response["success"] = 1;
    }
}

else{
    $response["success"] = 0;
    $response["error"] = "Algo está errado";
}

pg_close($con);
echo json_encode($response);

?>