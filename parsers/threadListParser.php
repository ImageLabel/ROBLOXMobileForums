<?php

class NoSuchForumException extends Exception {}
class ForumParseException extends Exception {}

class Forum extends EnhancedObject {
	public $name;
	public $desc;
	public $id;
	public $pageNum = 1;

	private function __construct() {}

	public static function byId($id) {
		$forumXML = simplexml_load_file('forums.xml')->xpath("//forum[@id='$id']");
		if (!$forumXML) throw new NoSuchForumException();

		$forum = new Forum();
		$forum->name = $forumXML[0]->name;
		$forum->desc = $forumXML[0]->desc;
		//$forum->name = "ROBLOX Forum";
		//$forum->desc = "";
		$forum->id   = $id;

		return $forum;
	}

	public function getUrl() {
		return "http://www.roblox.com/Forum/ShowForum.aspx?ForumID={$this->id}&PageIndex={$this->pageNum}";
	}

	public function loadPage() {
		$html = @file_get_contents($this->url);
		if(!$html) throw new NoSuchForumException();

		libxml_use_internal_errors(true);
		$page = new DOMDocument();
		$page -> preserveWhiteSpace = false;
		$page -> loadHTML($html);

		// Handle the errors in blue boxes the forum sometimes gives
		$errorBox = $page->getElementById("ctl00_cphRoblox_ErrorTitle");
		if($errorBox) {
			throw new NoSuchForumException();
		}

		return $page;
	}

	public function loadTotalPages() {
		// Error if thread doesn't exist
		$holder = $this->page->getElementById("ctl00_cphRoblox_ThreadView1_ctl00_Pager");
		if (!$holder) throw new ThreadParseException();

		// Get the total number of pages
		$pagination = $holder->childNodes->item(0)->nodeValue;
		$matches = array();
		if(preg_match('/page\s+[\d,]+\s+of\s+([\d,]+)/i', $pagination, $matches)) {
			return (int) str_replace(",", "", $matches[1]);
		}
	}

	public function loadThreads() {
		$holder = $this->page->getElementById('ctl00_cphRoblox_ThreadView1_ctl00_ThreadList');
		if (!$holder) {
			throw new ForumParseException();
		}

		$threads = array();

		foreach($holder->childNodes as $threadRow) {
			$cells = $threadRow->getElementsByTagName('td');
			if ($cells->length <= 1) continue;

			$thread = new Thread();
			$thread->totalPages = 1;
			$thread->title = $cells->item(1)->getElementsByTagName('a')->item(0)->nodeValue;

			if ($cells->item(1)->childNodes->length > 1) {
				// Handling multiple pages
				$titleWithPages = $cells->item(1)->nodeValue;
				$pagesArray = explode(",",$titleWithPages);
				$thread->totalPages = (int) substr(end($pagesArray), 0, -1);
			}
			$thread->id      = (int) substr($cells->item(1)->getElementsByTagName('a')->item(0)->getAttribute('href'),28);
			$thread->author  = User::byName(substr($cells->item(3)->nodeValue, 5));

			//Turn hyphens into 0s (helps clarity)
			$thread->replies = (int) str_replace("-","0",$cells->item(4)->nodeValue);
			$thread->views   = (int) str_replace("-","0",$cells->item(5)->nodeValue);

			// Handling pinned posts
			$lastPostedSect = $cells->item(5)->nodeValue;
			if (substr($lastPostedSect,0,11) == "Pinned Post") {
				$thread->pinned = true;
				$thread->lastPoster = substr($lastPostedSect,14);
			}
			else {
				$thread->pinned = false;
				// This is needed to display last poster info correctly on threads from before today
				if (substr($lastPostedSect,0,5) == "Today") {
					$thread->lastPoster = substr($lastPostedSect,19);
				}
				else {
					$thread->lastPoster = substr($lastPostedSect,23);
				}
			}

			$threads[] = $thread;
		}
		return $threads;
	}
}
?>
