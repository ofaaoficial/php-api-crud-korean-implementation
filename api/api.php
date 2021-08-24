<?php
$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_GET['request']) ? $_GET['request'] : "";
$uri = explode("/", $request);
$command = isset($uri[0]) ? $uri[0] : "";
$id = isset($uri[1]) ? $uri[1] : 0;
$token = isset($_GET['token']) ? $_GET['token'] : '';
$dbh = new PDO("mysql:host=localhost; dbname=restful; charset=utf8","root","");
if( $command == "friend" ) {
	if( $method == "GET" ) {
		$sql = "SELECT * FROM friends";
		if( $id ) $sql .= " WHERE id={$id}";
		$sql .= ' ORDER BY id DESC';	
		if( $sth = $dbh->query($sql) ) {
			$friends = $sth->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($friends);
		}
	} elseif( $method == "DELETE" ) {
		$count = 0;
		if( $id ) {
			$sql = "DELETE FROM friends WHERE id={$id}";
			if( $sth = $dbh->query($sql) ) {
				$count = $sth->rowCount();
			}
		}
		echo $count;
	}elseif( $method == 'PUT' ){
		$count = 0;
		
		$putData  = file_get_contents('php://input');
		$data = array();
		parse_str($putData, $data);						

		if(	$data['name'] && 
			$data['city'] && 
			$data['email'] && 
			$data['phone'] && 
			$data['id']){
				
				$sql = "UPDATE friends SET name = '{$data['name']}', city = '{$data['city']}', email = '{$data['email']}', phone = '{$data['phone']}' WHERE id = {$data['id']}";

				if( $sth = $dbh->query($sql) ) {
					$count = $sth->rowCount();
				}
		}

		echo $count;
	}elseif( $method == 'POST' ){
		$count = 0;

		$data = $_POST;

		if(	$data['name'] && 
			$data['city'] && 
			$data['email'] && 
			$data['phone']){
				
				$sql = "INSERT INTO friends SET name = '{$data['name']}', city = '{$data['city']}', email = '{$data['email']}', phone = '{$data['phone']}'";
																
				if( $sth = $dbh->query($sql) ) {
					$count = $sth->rowCount();
				}
		}

		echo $count;
	}
}
