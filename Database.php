<?php

class Database extends PDO {
	private $host = 'localhost';
	private $dbname = 'weather';
	private $user = 'root';
	private $password = '12345678';

	public function __construct() {
		parent::__construct('mysql:host='.$this->host.';dbname='.$this->dbname, $this->user, $this->password, 
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));		
	}
	
	public function Insert($array, $table) {
		
		$q = "INSERT INTO $table (";
		$keys = array_keys($array);
		$last_key = end($keys);
		foreach ($array as $key => $value){
			if($last_key == $key)
				$q .= "`".$key."`";
			else
				$q .= "`".$key."`, ";
		}
		
		$q .= ") VALUES(";
		foreach ($array as $key => $value) {
			if($last_key == $key)
				$q .= "'".$value."'";
			else
				$q .= "'".$value."', ";
		}
		
		$q .= ")";
		$sth = $this->prepare($q);
		$res = $sth->execute();
		return $res;
	}
	
	public function Update($array, $table, $id) {
		$q = "UPDATE $table SET ";
		$keys = array_keys($array);
		$last_key = end($keys);
		foreach($array as $key=>$value){
			if($last_key == $key)
				$q .= $key ." = '".$value."' ";
			else
				$q .= $key ." = '".$value."', ";
		}
		
		$q .= "WHERE id = $id LIMIT 1";
		$sth = $this->prepare($q);
		$res = $sth->execute();
		return $res;
	}
	
	public function Delete($id, $table) {
		$q = "DELETE FROM ".$table." WHERE id = ".$id;
		$sth = $this->prepare($q);
		$res = $sth->execute();
		return $res;
	}
	
	public function SelectDirectQuery($query) {
		$sth = $this->prepare($query);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$res = $sth->execute();
		$count =  $sth->rowCount();
		
		if($count > 0) {
			$data = $sth->fetchAll();
			return $data;
		} 
		else {
			return false;
		}
	}

	public function getWarmestMonthId() {
		$q = "SELECT Id FROM summaryWeather WHERE HighAvg=(SELECT MAX(HighAvg) FROM summaryWeather)";
		return $this->SelectDirectQuery($q);
	}
	public function getColdestMonthId() {
		$q = "SELECT Id FROM summaryWeather WHERE HighAvg=(SELECT MIN(HighAvg) FROM summaryWeather)";
		return $this->SelectDirectQuery($q);
	}

	public function updateWarmestMonth($id) {
		$q = "UPDATE summaryWeather SET Warmest = 1 WHERE Id =". $id;
		$sth = $this->prepare($q);
		$res = $sth->execute();
		return $res;
	}

	public function updateColdestMonth($id) {
		$q = "UPDATE summaryWeather SET Coldest = 1 WHERE Id =". $id;
		$sth = $this->prepare($q);
		$res = $sth->execute();
		return $res;
	}

	public function getSummaryWeather() {
		$q = "SELECT * FROM summaryWeather";
		return $this->SelectDirectQuery($q);
	}
}