<?php
require_once "class/config.php";
$title = "Forgot password";
require_once "include/header.php";
?>
				<div class="options col-11 col-sm-8 col-md-6 col-lg-5 mx-auto">
					<div>
						<form id="fpass" method="post">
							<div class="h2 my-3">Verify your email</div>
							<div class="form-group">
								<label for="fpass-email">Email</label>
								<input type="email" name="fpass-email" id="fpass-email" placeholder="Enter your email address" class="form-control">
							</div>
							<div class="form-group">
								<div class="fpass-info"></div>
								<button class="btn btn-success">Check</button>
							</div>
						</form>
					</div>
					<div>
						<form method="post" id="chpwd" class="fpass">
							<div class="h2 my-3">Create new password</div>
							<div class="form-group">
								<label for="password">New password</label>
								<input type="password" name="pass" id="password" placeholder="Create new password..." class="form-control">
							</div>
							<div class="form-group">
								<label for="v-pass">Verify password</label>
								<input type="password" name="v-pass" id="v-pass" placeholder="Confirm new password..." class="form-control">
							</div>
							<div class="form-group">
								<div class="chpwd-info"></div>
								<button class="btn btn-success">Create</button>
							</div>
						</form>
					</div>
				</div>
<?php
require_once "include/footer.php";