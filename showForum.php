<?php
require_once "templates/page.php";
require_once 'parsers/threadParser.php';
require_once "parsers/threadListParser.php";

try {
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$id = (int) $_GET['id'];

		$forum = Forum::byId($id);

		if (isset($_GET['page']) && is_numeric($_GET['page'])) {
			$forum->pageNum = $_GET['page'];
		}
	}
	else throw new NoSuchForumException();

	templatePage($forum->name.' | ROBLOX Forums', function() use ($forum) { ?>
		<!--Threads-->
		<ul data-role="listview">
			<!--Intro -->
			<li><h3><?php echo $forum->name; ?></h3><p><?php echo $forum->desc; ?></p></li>
			<!--/Intro-->
			<li data-role = "list-divider">Threads</li>
			<?php foreach($forum->threads as $thread): ?>
				<?php if($thread->pinned): ?><li data-icon='star'><?php else: ?><li><?php endif ?>
				<a href="showThread.php?id=<?= $thread->id ?>">
					<h3 style='white-space: normal'><?= $thread->title ?></h3>
					<p>
						<font color='#2489CE'><?= $thread->author->name ?></font>
						<b><?= $thread->replies ?></b> Replies
						<b><?= $thread->views ?></b> Views
						<b><?= $thread->lastPoster ?></b>
				<?php if($thread->totalPages != 1): ?>
						<div class='ui-li-count'><?= $thread->totalPages ?></div>
				<?php endif ?>
					</p>
				</a>
			</li>
			<?php endforeach ?>
		</ul>
		<!--/Threads-->
	<?php }, $forum->totalPages > 1 ? function() use ($forum) { ?>
		<div style = "text-align: center;" data-role="controlgroup" data-type="horizontal">
		<?php if ($forum->pageNum != 1): ?>
			<a href='showForum.php?id=<?= $forum->id ?>&page=<?= $forum->pageNum - 1 ?>'
			   data-theme='e' data-role='button' data-icon='arrow-l' data-iconpos='notext'>Previous</a>
		<?php endif ?>
			<a href='#' data-role='button' data-theme='c'><?= $forum->pageNum ?> of <?= $forum->totalPages?></a>
		<?php if ($forum->pageNum != $forum->totalPages): ?>
			<a href='showForum.php?id=<?= $forum->id ?>&page=<?= $forum->pageNum + 1 ?>'
			   data-theme='e' data-role='button' data-icon='arrow-r' data-iconpos='notext'>Next</a>
		<?php endif ?>
		</div>
	<?php } : NULL, function() {});
}
catch(NoSuchForumException $e) {
	templatePage("404 | ROBLOX Forums", function() use ($e, $id) { ?>
		<p><strong>Error:</strong> Forum #<?= $id ?> not found</p>
	<?php });
}