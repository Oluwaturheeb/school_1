<!DOCTYPE html>

<html>
	<head>
		<meta lang="en">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="assets/js/jquery/jquery.js"></script>
		<link rel="stylesheet" href="assets/boot/bootstrap.css">
		<link rel="stylesheet" href="assets/css/stylesheet.css">
		<script src="assets/js/Validate.js" ></script>
		<title><?php echo $title ?> - GIS</title>
	</head>
	<body>
		<div class="global"></div>
		<nav class="fixed-top">
			<a href="/" class="logo">GIIS</a>
			<div class="bar-container">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="links">
<!--
				<div class="search">
					<form class="" id="search" method="post">
						<div class="input-group">
							<input class="form-control" name="search" id="search-keyword">
							<span class="input-group-btn">
								<button class="btn btn-success">Search!</button>
							</span>
						</div>
					</form>
				</div>
-->
				<li>
					<a href="/" class="">Home</a>
				</li>
				<?php if(Session::check("student")): ?>
				<li>
					<a href="student.php">Profile</a>
				</li>
				<?php elseif (Session::check("admin") || Session::check("teacher")): ?>
				<li>
					<a href="admin.php" class="search-link">Account</a>
				</li>
				<?php endif; ?>
				<li>
					<a href="search.php" class="search-link">Search</a>
				</li>
				<li>
					<a href="fees.php">Pay fees</a>
				</li>
				<li>
					<a href="http://blog.giis.com">Blog</a>
				</li>
				<?php if(@count($_SESSION)): ?>
				<li>
					<a href="logout.php">Logout</a>
				</li>
				<?php else: ?>
				<li>
					<a href="about.php">About</a>
				</li>
				<li>
					<a href="login.php">Login</a>
				</li>
				<?php endif; ?>
			</div>
		</nav>
		<div class="container">
			<div class="row">
				