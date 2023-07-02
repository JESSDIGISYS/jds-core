<?php
namespace App\Core\DB;
use App\Core\Model;
use App\Core\Application;


abstract class DbModel extends Model {
	abstract static public function tableName(): string;

	abstract public function attributes(): array;

	abstract static public function primaryKey() : string;

	public function save() {
		$tableName = $this->tableName();
		$attributes = $this->attributes();
		$db = Application::$app->db;
		$sql = 'INSERT INTO ' . $tableName . ' (' . implode(',', array_keys($attributes)) . ') 
		VALUES (' . implode(',', array_values($attributes)) . ')';
		
		$db->query($sql);
		foreach (array_keys($attributes) as $attribute) {
			$db->bind((':'.$attribute), $this->{$attribute});
		}
		if ($db->execute()) {
			// $db->log('User successfully added');
			return true;
		}
	}
	
	static public function findOne($where) { // [email => zura@example.com, fname => zura]
		$db = Application::$app->db;

		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = 'SELECT * FROM ' . $tableName . ' WHERE ';
		$sql .= implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
		$db->query($sql);
		foreach ($where as $key => $item) {
				$db->bind(":$key", trim($item));
		}
		return $db->classObj(static::class);
		// return $db->single();
	}
}


