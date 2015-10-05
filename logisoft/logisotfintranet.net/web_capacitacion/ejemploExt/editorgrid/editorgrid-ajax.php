<?php
	//header("Content-Type: text/plain");
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	session_start(); 
	
	$add = $_POST['records'];
	
	/*if(!isset($_SESSION['data'])){
		$data = array( //creates the initial data
			'success'=>true,
			'total'=>11,
			'data'=>array(
				array('id'=>1,'name'=>'John doe','age'=>23,'country'=>'USA')
				array('id'=>2,'name'=>'Taylor Swift','age'=>19,'country'=>'USA'),
				array('id'=>3,'name'=>'Carlos Mena','age'=>22,'country'=>'México'),
				array('id'=>4,'name'=>'Christiano Ronaldo','age'=>24,'country'=>'Portugal'),
				array('id'=>5,'name'=>'Sasha Cohen','age'=>25,'country'=>'USA'),
				array('id'=>6,'name'=>'Christian Van Der Henst','age'=>27,'country'=>'Guatemala'),
				array('id'=>7,'name'=>'Collis Ta\'eed','age'=>31,'country'=>'USA')
			)
		);
		$_SESSION['data'] = $data; //load the data in sessions for the fisrt time
	}else{
		$data = $_SESSION['data']; //get the data if exist in session
	}*/
	
	if(isset($add)){ //if there are records to insert/update
		$records = json_decode(stripslashes($add)); //parse the string to PHP objects
		$ids = array();
		foreach($records as $record){ 
			if(isset($record->newRecordId)){ //records to insert
				$id = count($data['data']);
				/*$info = array(
					'id'=> id,
					'name'=> $record->name,
					'age'=> $record->age,
					'country'=> $record->country
				);*/
				
				$s = "INSERT INTO clienteprueba SET nombre='$record->name', 
				paterno='$record->age', materno='$record->country'";
				mysql_query($s,$l) or die($s);
				
				array_push($data['data'],$info); //add the new record to session
				array_push($ids,array('oldId'=>$record->newRecordId,'id'=>$id));//new id
			}else{ //records to update
				foreach($data['data'] as $key=>$r){ //search the record to update
					if($r['id'] == $record->id){
						$data['data'][$key]['name'] = $record->name; //update the properties
						$data['data'][$key]['age'] = $record->age;
						$data['data'][$key]['country'] = $record->country;
						break;
					}
				}
			}
		}

		//print the success message
		echo json_encode(array(
				'success'=>true,
				'data'=>$ids 
			));
	}else{ 
		//print all records in session
		echo json_encode($data);
	}
?>