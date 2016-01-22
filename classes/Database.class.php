<?php
include "config/database.php";

class DB {
	public $dbh = NULL;

	public function __construct($dsn, $user, $password) {
		try {
			$this->dbh = new PDO($dsn, $user, $password);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			echo __LINE__.$e->getMessage();
		}
	}

	public function __destruct() {
		$this->dbh = NULL;
	}

	public function insert($sql, $array) {
		try {
			$sth = $this->dbh->prepare($sql);
			foreach ($array as $key => $value) {
				$array[$key] = htmlentities($value);
			}
			return $sth->execute($array);
		}
		catch(PDOException $e) {
			$message = $e->getMessage();
			if ($e->getCode() == '23000') {
				preg_match("/Duplicate entry '(.*)' for key '(.*)'/", $message, $results);
				return ["error" => "dupes", "value" => $results[1], "field" => $results[2]];
			} else {
				echo "error (l.".__LINE__.")\n".$e->getCode()."<br>";
				return false;
			}
		}
	}

	public function update($sql, $array) {
		try {
			$sth = $this->dbh->prepare($sql);
			return $sth->execute($array);
		}
		catch(PDOException $e) {
			echo "error (l.".__LINE__.")\n".$e->getMessage()."<br>";
			return false;
		}
	}

	public function delete($sql, $array) {
		try {
			$sth = $this->dbh->prepare($sql);
			return $sth->execute($array);
		}
		catch(PDOException $e) {
			echo "error (l.".__LINE__.")\n".$e->getMessage()."<br>";
			return false;
		}
	}

	public function find($sql, $array) {
		try {
			$this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$sth = $this->dbh->prepare($sql);
			$sth->execute($array);
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$result = [];
			while ($res = $sth->fetch()) {
				$result[] = $res;
			}
			return $result;
		}
		catch(PDOException $e) {
			echo "error (l.".__LINE__.")\n".$e->getMessage()."<br>";
			return false;
		}
	}
}
?>