<?php
require_once "class/config.php";
$a = new Action();
$d = new Db();
$v = new Validate();

$v->validator($_GET, [
	"url" => [
		"require" => true,
		"wordcount" => 1,
		"error" => "Unknown url",
		"field" => ""
	]
]);

if($v->pass()){
	$d->colSelect("blog", ["title", "content", "views", "time", "tags", "id", "cover"], [["url", "=", $v->fetch("url")]]);
} else {
	$title = "server error!";
	require_once "include/header.php";
	Redirect::to(404);
}
if($d->count()) {
	$title = $d->first()->title;
	$a->counter("blog", $d->first()->id);
} else {
	$title = "Post not found!";
}
require_once "include/header.php";
?>

	<div class="my-5 pt-5 mx-auto col-12 col-sm-9 col-md-8">
		<?php if(!$d->count()): require_once "error/error.php"; else: $e = $d->first();?>
		<h1><?php echo $e->title ?></h1>
		<div class="meta mb-3">
			<img src="assets/img/clock.svg"><i><?php echo $e->time ?></i>
			<img src="assets/img/eye.svg"><i><?php echo $e->views ?></i>
		</div>
		<img src="<?php echo $e->cover ?>" class="img-fluid">
		<div class="blog-body mt-4">
			<?php echo nl2br($e->content); ?>
		</div>
		<?php
		$all = "";
		foreach (explode(", ", $e->tags) as $tag) {
			$t = ucfirst($tag);
			$all .= "<a href='search.php?keyword=$tag' class='tag'>$t</a>";
		}
		endif; ?>
		<div class="tags mt-4">
			<?php echo $all ?>
		</div>
	</div>





<?php
require_once "include/footer.php";