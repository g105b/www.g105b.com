<?php
namespace App\Blog;

use DirectoryIterator;
use Iterator;

class ArticleList implements Iterator {
	protected $fileList;
	protected $iteratorIndex;
	protected $iteratorMap;

	public function __construct(string $path) {
		if(!is_dir($path)) {
			throw new ArticlePathNotFoundException($path);
		}

		$this->fileList = $this->getFileList($path);
	}

	protected function getFileList(string $path):array {
		$fileList = [];

		foreach(new DirectoryIterator($path) as $fileInfo) {
			if(!$fileInfo->isFile()) {
				continue;
			}

			$ext = $fileInfo->getExtension();
			if($ext !== "md") {
				continue;
			}

			$fileList [] = $fileInfo->getPathname();
		}

		return $fileList;
	}

	/**
	 * @link http://php.net/manual/en/iterator.rewind.php
	 */
	public function rewind():void {
		$this->iteratorIndex = 0;
	}

	/**
	 * @link http://php.net/manual/en/iterator.next.php
	 */
	public function next():void {
		$this->iteratorIndex ++;
	}

	/**
	 * @link http://php.net/manual/en/iterator.valid.php
	 */
	public function valid():bool {
		return isset($this->fileList[$this->iteratorIndex]);
	}

	/**
	 * @link http://php.net/manual/en/iterator.current.php
	 */
	public function current():Article {
		if(!isset($this->iteratorMap[$this->key()])) {
			$this->iteratorMap[$this->key()] = new Article(
				$this->fileList[$this->iteratorIndex]
			);
		}

		return $this->iteratorMap[$this->key()];
	}

	/**
	 * @link http://php.net/manual/en/iterator.key.php
	 */
	public function key():string {
		return $this->getId(
			$this->fileList[$this->iteratorIndex]
		);
	}

	protected function getId(string $pathName):string {
		$fileName = pathinfo($pathName, PATHINFO_FILENAME);
		return strtok($fileName, ".");
	}
}