<?php
namespace App\Blog;

use DateTime;

class Article {
	protected $id;
	protected $markdown;

	public function __construct(string $pathName) {
		$fileName = pathinfo($pathName, PATHINFO_FILENAME);
		$this->id = strtok($fileName, ".");
		$this->date = $this->getDateFromId($this->id);
		$this->markdown = file_get_contents($pathName);
	}

	public function getTitle():string {
		return strstr($this->markdown, "\n", true);
	}

	public function getDate():DateTime {
		return $this->date;
	}

	public function getPreview():string {
		return str_repeat("test ", 105);
	}

	public function getLink():string {
		list($dateString, $title) = explode("_", $this->id, 2);
		$dateString = str_replace("-", "/", $dateString);
		return "/" . implode("/", [
			"blog",
			$dateString,
			$title,
		]);
	}

	protected function getDateFromId(string $id):DateTime {
		$dateString = substr(
			$id,
			0,
			strpos($id, "_")
		);
		return new DateTime($dateString);
	}
}