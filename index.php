<?php
require_once "class/config.php";
$a = new Action();
$d = new Db();

$title = "Guide Intellect Islamic School";
require_once "include/header.php";
$d->get_all("event");
$event = $d->result();
?>

		<div class="col-12 col-sm-8">
			<h3>Welcome to Guide Intellect Islamic School!</h3>
			<div class="reviews">
			<?php 
			$d->customQuery("select name, time, content, rating from review limit 5");
			if($d->count()){
				$image = scandir("./assets/img/reviews");
				array_shift($image);
				array_shift($image);
				
				for($i = 0; $i < $d->count(); $i++) {
					$img = "assets/img/reviews/{$image[$i]}";
					
					$r = $d->result()[$i];
					echo <<<__here
					<div class="rev-each">
						<header>
							<img src="$img">
							<div>
								<b>$r->name</b><br/>
								<img src="assets/img/star.png"> $r->rating<br/>
								<img src="assets/img/clock.svg"> $r->time
							</div>
						</header>
						<div class="content">
							$r->content
						</div>
					</div>
__here;
				}
			}
			?>
			</div>
		</div>
		<div class="col-12 col-sm-4">
			<div class="events">
				<div class="header">Notification</div>
				<?php
				$div = "";
				foreach ($event as $e) {
					$type = $e->types;
					$name = $e->name;
					if($name == "Exams") {
						$data = "$type examination is in progress, <a href='exam.php'>click here to continue &raquo;</a>";
					} elseif ($name == "Admission") {
						$type = Utils::djson($type);
						$data = "Admission is currently in progress! <br><small>Starting from &raquo; $type[0] <br> Ends &raquo; $type[1] <br/> <a href='admission.php'>Click here to continue &raquo;</a></small>";
					} elseif ($name == "Vacancy") {
						$data = "We are currently in need of $type";
					}
					echo <<<__here
						<div class="mb-3">
							$data
                            <hr>
						</div>
__here;
					}
				?>
			</div>
		</div>
		<style>
			.row {
				margin-left: 0 !important;
			}
		</style>
<?php
require_once "include/footer.php";