<?php
require_once "class/config.php";

if(!Session::check("admin")) {
	Redirect::to("/");
}

$d = new Db();
$v = new Validate();
$a = new Action();

$v->validator($_GET, [
	"url" => [
		"require" => true,
		"field" => "url",
		"error" => "cannot find the post you are looking for"
	]
]);

if($v->pass()) {
	$d->colSelect("blog", ["for_update", "title", "tags", "files", "id"], [["url", "=", $v->fetch("url")]]);
	
	if($d->count()) {
		$r = $d->first();
		$title = $r->title;
		Session::set("blog_id", $r->id);
		
		if($r->files) {
			$f = "<label>Select cover</label>";
			$f .= Utils::display(explode("-----", $r->files));
		} elseif (Session::check("file")) {
			$f = "<label>Select cover</label>";
			$f .= Utils::display(Session::get("file"));
		} else {
			$f = <<<__here
			<a href="attachment" id="attachment">Add attachment</a>
			<input type="hidden" value="assets/img/default.png" name="cover" id="cover">
__here;
		}
		$file = <<<__here
			<div class="display-files form-group">
				$f
			</div>
__here;
		$display = <<<__here
		$file
		<div class="form-group">
					<label for="up-title">Title</label>
					<input type="text" name="up-title" id="up-title" class="form-control" placeholder="Enter blog title..." value="$r->title">
				</div>
				<div class="form-group">
					<label for="up-title">Tags</label>
					<input type="text" name="up-tags" id="up-tags" class="form-control" placeholder="Enter blog tags..." value="$r->tags">
					<small class="warning p-2">Comma separated values</small>
				</div>
				<div class="form-group">
					<label for="content">Blog content</label>
					<textarea name="up-content" id="up-content" class="form-control" placeholder="Enter blog content..." rows="10" cols="10">$r->for_update</textarea>
				</div>
				<div class="form-group">
					<div class="create-info"></div>
					<button class="btn btn-success">Create</button>
					<script>
						if($('.display-files input')) {
							if($('.display-files input:checked').length == 0) {
								$('#create-blog .btn').attr({'disabled': 'disabled'});
							}
						}
					</script>
				</div>
__here;
	} else {
		$display = "Cannnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnt";
	}
	require_once "include/header.php";
	
	echo <<<__here
	<div class="col-12 col-sm-8 col-md-6 mx-auto">
		<div class="h2 my-3">
			Update post
		</div>
		<form method="post" id="up-blog">
			$display
		</form>
	</div>
	<div class="col-sm-4">
		<div class="h2 my-3">Utility</div>
			<div class="utils">
				Add <a href="#heading" class="utils">sub heading</a><br> 
				Add inline <a href="#inline" class="utils">text</a><br> 
				Add arabic <a href="#block" class="utils">text</a><br> 
				Add bold <a href="#bold" class="utils">text</a>
			</div>
		</div>
		<div class="attachment mx-auto">
			<form method="post" id="attache">
				<span class="close">&times;</span>
				<div class="my-3 h3">Add attachment</div>
				<div class="form-group">
					<input type="file" name="attache[]" id="file" class="form-control" multiple>
				</div>
				<div class="form-group">
					<div class="attache-info"></div>
					<button class="btn btn-success">Upload files</button>
				</div>
			</form>
		</div>
		<script src="assets/js/admin.js"></script>
__here;
}

require_once "include/footer.php";