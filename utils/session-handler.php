<?php

require_once('database/database.php');

/*
 |------------------------------
 | 		Classe DbSessionHandler
 | Stockage des sessions PHP en base de données (table pa_session).
 | Nécessaire sur Vercel : le système de fichiers des fonctions serverless est éphémère, les sessions fichiers n'y survivent pas d'une requête à l'autre.
 |------------------------------
 */

class DbSessionHandler implements SessionHandlerInterface {

	private function table() {
		return Config::DB_TABLE_PREFIX . 'session';
	}

	private function maxlifetime() {
		$maxlifetime = (int) ini_get('session.gc_maxlifetime');
		return $maxlifetime > 0 ? $maxlifetime : 1440;
	}

	#[\ReturnTypeWillChange]
	public function open($path, $name) {
		return true;
	}

	#[\ReturnTypeWillChange]
	public function close() {
		return true;
	}

	#[\ReturnTypeWillChange]
	public function read($id) {
		$statement = Database::getPdo()->prepare('SELECT data FROM ' . $this->table() . ' WHERE id=:id AND last_activity>=:expiration;');
		$statement->bindValue(':id', $id, PDO::PARAM_STR);
		$statement->bindValue(':expiration', time() - $this->maxlifetime(), PDO::PARAM_INT);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		if ($result === false || !isset($result['data'])) {
			return '';
		}
		return $result['data'];
	}

	#[\ReturnTypeWillChange]
	public function write($id, $data) {
		$statement = Database::getPdo()->prepare('INSERT INTO ' . $this->table() . ' (id, data, last_activity) VALUES (:id, :data, :last_activity) ON DUPLICATE KEY UPDATE data=VALUES(data), last_activity=VALUES(last_activity);');
		$statement->bindValue(':id', $id, PDO::PARAM_STR);
		$statement->bindValue(':data', $data, PDO::PARAM_STR);
		$statement->bindValue(':last_activity', time(), PDO::PARAM_INT);
		return $statement->execute();
	}

	#[\ReturnTypeWillChange]
	public function destroy($id) {
		$statement = Database::getPdo()->prepare('DELETE FROM ' . $this->table() . ' WHERE id=:id;');
		$statement->bindValue(':id', $id, PDO::PARAM_STR);
		return $statement->execute();
	}

	#[\ReturnTypeWillChange]
	public function gc($maxlifetime) {
		$statement = Database::getPdo()->prepare('DELETE FROM ' . $this->table() . ' WHERE last_activity<:expiration;');
		$statement->bindValue(':expiration', time() - $maxlifetime, PDO::PARAM_INT);
		$statement->execute();
		return $statement->rowCount();
	}

}
