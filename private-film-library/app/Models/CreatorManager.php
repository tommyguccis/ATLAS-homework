<?php

namespace App\Models;

use Nette;
use Dibi;
use Nette\SmartObject;

final class CreatorManager
{
	private $database;

	public function __construct(Dibi\Connection $database)
	{
		$this->database = $database;
	}

	public function getCreators()
	{
		return $this->database->query('SELECT * FROM creators');
	}

	public function getCreatorType()
	{
		return $this->database->fetchPairs("SELECT id, name FROM creators_type");
	}

	public function getCreator(int $creatorId)
	{
		return $this->database->fetch('
		SELECT creators.id, first_name, last_name, date_of_birth, country, creators_type.name as type, type_id
		FROM creators INNER JOIN creators_type ON creators.type_id = creators_type.id
		WHERE creators.id = ?', $creatorId);
	}

	public function saveCreator(array $data)
	{
		$this->database->query("INSERT INTO creators %v", $data);

		return $this->database->getInsertId();
	}

	public function deleteCreator(int $creatorId)
	{
		$this->database->query("DELETE FROM creators WHERE id = ?", $creatorId);
	}

	public function updateCreator(array $data, int $creatorId)
	{
		$this->database->query('UPDATE creators SET %a WHERE id = ?', $data, $creatorId);
	}

	
}