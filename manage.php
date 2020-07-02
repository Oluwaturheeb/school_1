<?php
require_once "class/config.php";
$d = new Db;

if(!Session::check("admin")) {
    Redirect::to("/");
}
$title = "Manage post";
require_once "include/header.php";
$d->advance("blog", ["title", "content", "time", "url", "cover", "views", "id"], [], "", ["id", "desc", $d->getpage("blog", [], 4)]);

$res = $d->result();
$div = "";
foreach ($res as $r) {
	if($r->cover) {
		$cover = $r->cover;
	} else {
		$cover = "assets/img/blogpost.png";
	}
	$con = Utils::wordcount($r->content, 15);
	$div .= <<<__here
		<div class="cover">
			<a href="post.php?url=$r->url">
				<div class="h2">$r->title</div>
			</a>
			<div class="meta my-2">
		  <img src="assets/img/clock.svg"><i>$r->time</i>
			<img src="assets/img/eye.svg"><i>$r->views</i>
			</div>
            <div class="opt">
                <a href="update.php?url=$r->url">Update</a>
                <a href="delete.php?url=$r->url" class="delete" id="$r->id">Delete</a>
            </div>
		</div>
__here;
}
?>
		<div class="blog-list col-12 col-sm-8 mx-auto">
		<?php echo $div;
		if($d->paging) {
			$next = "<a class='paging next' href='?more=$d->next'>Next &raquo;&raquo;</a>";
			if($d->prev) {
				$prev = "<a class='paging prev' href='?more=$d->prev'>&raquo;&raquo; Previous</a>";
			} else {
				$prev = "";
			}
		} else {
			$next = "";
			$prev = "";
		}
			
		?>
		</div>
		<div class="pagings col-12 col-sm-8 mx-auto">
			<?php echo $prev . $next; ?>
		</div>
		<script src="assets/js/admin.js"></script>
<?php
require_once "include/footer.php";