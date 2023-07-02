<?php

namespace App\Core\DB;
use App\Core\Application;

use \PDO;
// use App\Core\Application;
class Database {
	private PDO $dbHandler;
	private $statement;

	public function __construct(array $config) {
		// $config comes from Application
		$this->dbHandler = new PDO($config['dsn'], $config['user'], $config['password']);
		$this->dbHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}

	public function applyMigrations() {
		$this->createMigrationsTable();
		$appliedMigrations = $this->getAppliedMigrations();
		$files = scandir(Application::$ROOT_DIR.'/migrations');
		$toApplyMigrations = array_diff($files, $appliedMigrations);
		$newMigrations = [];
		foreach ($toApplyMigrations as $migration) {
			if ($migration === '.' || $migration === '..' || $migration === 'm000.php') {
				continue;
			}
			require_once Application::$ROOT_DIR.'/migrations/' . $migration;
			$className = pathinfo($migration, PATHINFO_FILENAME);
			$instance = new $className();
			$this->log("Applying migration $migration");
			$instance->up();
			$this->log("Applied migration $migration");
			$newMigrations[] = $migration;
		}

		 if (!empty($newMigrations)) {
			$this->saveMigrations($newMigrations);
		 } else {
			$this->log('All migrations are applied');
		 }
	}

	public function createMigrationsTable() {
		$sql = 'CREATE TABLE IF NOT EXISTS migrations (
			id int NOT NULL AUTO_INCREMENT, 
			migid varbinary(12) NOT NULL,
			migration varchar(255),
			created_at timestamp DEFAULT current_timestamp,
			PRIMARY KEY (migid),
			UNIQUE KEY id (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
			COMMENT="Migrations Table";
			';
		$this->query($sql);
		$this->execute();

	}

	public function getAppliedMigrations() {
		$sql = "SELECT migration FROM migrations";
		$this->query($sql);
		return $this->getColumns();
	}
	
	public function saveMigrations(array $migrations) {
		foreach ($migrations as $m) {
			$sql = 'INSERT INTO migrations (migid, migration) ';
			$sql .= 'VALUES ';
			$sql .= '(:id, :mig) ';
			$this->query($sql);
			unset($sql);
			$this->bind(':id', $this->get_new_id());
			$this->bind(':mig', $m);
			$this->execute();
		}
	}

	public function log($message) {
		echo '[' . date('m-d-Y H:i:s') . '] - ' . $message.PHP_EOL; 
	}

	public function query(string $sql) {
		$this->statement = $this->dbHandler->prepare($sql);
	}

	public function bind(string $parameter, $value, $type = null) {
		switch (is_null($type)) {
			case is_int($value):
				$type = PDO::PARAM_INT;
				break;

			case is_bool($value):
				$type = PDO::PARAM_BOOL;
				break;

			case is_null($value):
				$type = PDO::PARAM_NULL;
				break;

			default:
				$type = PDO::PARAM_STR;
		}
		$this->statement->bindValue($parameter, $value, $type);
	}

	public function execute() {
		return $this->statement->execute();
	}

	// return an array
	public function resultSet() {
		if ($this->execute()) {
			return $this->statement->fetchAll(PDO::FETCH_OBJ);
		}
	}

	// return a specific row as an object
	public function single() {
		if ($this->execute()) {
			return $this->statement->fetch(PDO::FETCH_OBJ);
		}
	}

	public function classObj($class) {
		if ($this->execute()) {
			return $this->statement->fetchObject($class);
		}
	}

	public function getColumns() {
		if ($this->execute()) {
			return $this->statement->fetchAll(PDO::FETCH_COLUMN);
		}
	}

	// get's the row count
	public function rowCount() {
		return $this->statement->rowCount();
	}



	// generate a 12 digit
	public function get_new_id($length = 12, $symbol = FALSE) {
		$id = "";
		$counter = 0;
		$gni = array();
		while (strlen($id) < $length) {
			$gni = $this->get_random_letter($symbol, $counter);
			$counter = $gni['counter'];
			$id .= $gni['letter'];
			unset($gni);
			$gni = array();
		}
		return $id;
		// return chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . "00";
	}

	// get a random letter and return it
	private function get_random_letter($symbol = FALSE, $cnt = 0) {
		$letter = "";
		$lettNum = 4;
		switch ($this->get_random_value(1, ($symbol ? 4 : 3))) { // change 3 to 4 when symbols are defined in case
			case 1:
				$letter = chr($this->get_random_value(48, 57)); // number
				break;
			case 2:
				$letter = chr($this->get_random_value(65, 90)); // upper case letter
				break;
			case 3:
				$letter = chr($this->get_random_value(97, 122)); // lower case letter
				break;
			case 4:
				if ($cnt == 0) { // only allow 1 symbol
					$letter = ($symbol ? $this->get_random_symbol(1, 7) : ""); // symbols
					$cnt++;
				}
				break;
		}
		return ['letter' => $letter, 'counter' => $cnt];
	}

	private function get_random_symbol($min = 1, $max = 7) {
		$symbol = "";
		switch ($this->get_random_value($min, $max)) {
			case 1:
				$symbol = chr($this->get_random_value(58, 64)); // : ; < = > ? @
				break;

			case 2:
				$symbol = chr(91); // [
				break;

			case 3:
				$symbol = chr($this->get_random_value(93, 94)); // ] ^
				break;

			case 4:
				$symbol = chr(123); // {
				break;

			case 5:
				$symbol = chr($this->get_random_value(125, 126)); // } ~
				break;

			case 6:
				$symbol = chr($this->get_random_value(33, 38)); // ! " # $ % &
				break;

			case 7:
				$symbol = chr($this->get_random_value(40, 47)); // ( ) * + , - . /
				break;
		}
		return $symbol;
	}

	// get a randome value and return it
	private function get_random_value($min = 1, $max = 3) {
		return mt_rand($min, $max);
	}


}