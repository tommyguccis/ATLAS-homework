<?php

namespace App\Models;

use Nette;
use Dibi;
use Nette\SmartObject;

final class FilmManager
{
	private $database;

	public function __construct(Dibi\Connection $database)
	{
		$this->database = $database;
	}

	public function getFilms()
	{
		return $this->database->query('SELECT * FROM film');
	}

	public function getGenres()
	{
		return $this->database->fetchPairs("SELECT id, name FROM genre");
	}

	public function getFilm(int $filmId)
	{
		return $this->database->fetch('
		SELECT film.id, title, length, landscape, genre.name as genre, genre.id as genre_id, country
		FROM film INNER JOIN genre ON film.genre_id = genre.id
		WHERE film.id = ?', $filmId);
	}

	public function saveFilm(array $data)
	{
		$this->database->query("INSERT INTO film %v", $data);

		return $this->database->getInsertId();
	}

	public function deleteFilm(int $filmId)
	{
		$this->database->query("DELETE FROM film WHERE id = ?", $filmId);
	}

	public function updateFilm(array $data, int $filmId)
	{
		$this->database->query('UPDATE film SET %a WHERE id = ?', $data, $filmId);
	}

	public function addCreator(int $filmId, int $creatorId)
	{
		$this->database->query("INSERT INTO fi_cr VALUES (?, ?)", $filmId, $creatorId);
	}

	public function getCreatorsListNot(int $filmId)
	{
		return $this->database->query("
		SELECT first_name, last_name, creators.id, name as creator_type
		FROM creators INNER JOIN creators_type ON creators.type_id = creators_type.id

		EXCEPT
	
		SELECT first_name, last_name, creators.id, name as creator_type
		FROM fi_cr INNER JOIN creators ON creator_id = creators.id INNER JOIN creators_type ON creators.type_id = creators_type.id
		WHERE film_id = ?", $filmId);
	}

	public function getCreatorsList(int $filmId)
	{
		return $this->database->query("
		SELECT first_name, last_name, creators.id, name as creator_type
		FROM fi_cr INNER JOIN creators ON creator_id = creators.id INNER JOIN creators_type ON creators.type_id = creators_type.id
		WHERE film_id = ?", $filmId);
	}

	public function removeCreator(int $filmId, int $creatorId)
	{
		$this->database->query("DELETE FROM fi_cr WHERE film_id = ? AND creator_id = ?", $filmId, $creatorId);
	}
}