<?php

session_start();
// connecting to db
$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);

// array for JSON response
$response = array();

$result = pg_query($con, "SELECT * from categoria");
if(pg_num_rows($result)){
	$categorias = pg_fetch_array($result);
	$response['success'] = 1;
	$response['categorias'] = $categorias;
}
else {
	$response["success"] = 0;
	$response["error"] = "Error BD: ".pg_last_error($con);
}

pg_close($con);
echo json_encode($response);

?>
