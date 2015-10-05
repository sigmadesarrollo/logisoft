<?php

	$dataDB = array(
				array(
					"name"=>"MySQL",
					"desc"=>"The world's most popular open source database",	
					"logo"=>"mysql.png"
				),
				array(
					"name"=>"PostgreSQL",
					"desc"=>"The world's advanced open source database",					
					"logo"=>"postgresql.png"
				),
				array(
					"name"=>"Oracle",
					"desc"=>"The world's largest enterprise software company",
					"logo"=>"oracle.png"				
				),
	);
	
	$o = array(
			"num"=>count($dataDB),			
			"data"=>$dataDB
		);
	echo json_encode($o);
?>
