<?php
require_once "class/config.php";

if(!Session::check("admin")){
	Redirect::to("/");
}

$title = "Blog";
require_once "include/header.php";
?>

		<div class="col-12 col-sm-8 col-md-6 mx-auto">
			<div class="h2 my-3">
				Create post
			</div>
			<form method="post" id="create-blog">
				<div class="display-files form-group">
					<?php if(Session::check("file")): ?>
					<label>Select cover</label>
					<?php echo Utils::display(Session::get("file")); else: ?>
					<a href="attachment" id="attachment">Add attachment</a>
					<input type="hidden" value="assets/img/default.png" name="cover" id="cover">
					<?php endif; ?>
				</div>
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" name="title" id="title" class="form-control" placeholder="Enter blog title...">
				</div>
				<div class="form-group">
					<label for="title">Tags</label>
					<input type="text" name="tags" id="tags" class="form-control" placeholder="Enter blog tags...">
					<small class="warning p-2">Comma separated values</small>
				</div>
				<div class="form-group">
					<label for="content">Blog content</label>
					<textarea name="content" id="content" class="form-control" placeholder="Enter blog content..." rows="10" cols="10"></textarea>
				</div>
				<div class="form-group">
					<div class="create-info"></div>
					<button class="btn btn-success">Create</button>
					<?php if(Session::check("file")): ?>
					<script>
						if($('.display-files input:checked').length == 0) {
							$('#create-blog .btn').attr({'disabled': 'disabled'});
						}
					</script>
					<?php endif; ?>
				</div>
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

<?php
require_once "include/footer.php";