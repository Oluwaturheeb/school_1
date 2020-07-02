<?php
require_once "class/config.php";
$title = "Login";
require_once "include/header.php";
?>
        <form method="post" id="auth" class="col-11 col-sm-6 col-lg-5 mx-auto">
			<div class="h2 mb-4">Login</div>
            <div class="note">Student login with either enter fullname or Parent email with student ID</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input name="email" id="email" placeholder="Enter your email address..." type="text" class="form-control">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input name="password" id="password" placeholder="Enter password..." type="password" class="form-control">
			</div>
			<div class="form-group">
				<div class="admin-info"></div>
				<button class="btn btn-success">Login</button>
			</div>
			<div class="auth-opt text-center small my-4">
				<a href="fpass.php">Forgot password?</a>
			</div>
		</form>
		<style>
			.row {
				margin-left: 0 !important;
			}
		</style>
<?php require_once "include/footer.php";