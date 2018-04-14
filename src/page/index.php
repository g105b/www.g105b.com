<?php
namespace App\Page;

use App\Blog\ArticleList;
use Gt\WebEngine\FileSystem\Path;

class IndexPage extends \Gt\WebEngine\Logic\Page {
	public function go() {
		$this->outputArticles($this->input->get("page") ?? 1);
	}

	protected function outputArticles(int $page) {
		$path = implode(DIRECTORY_SEPARATOR, [
			Path::getDataDirectory(),
			"blog",
		]);

		foreach(new ArticleList($path) as $id => $article) {
			$t = $this->document->getTemplate("preview");
			$t->bind([
				"title" => $article->getTitle(),
				"date" => $article->getDate()->format("jS F Y"),
				"preview" => $article->getPreview(),
				"link" => $article->getLink(),
			]);
			$t->insertTemplate();
		}
	}
}