<?php
require_once "class/config.php";
$a = new Action();
$v = new Validate();
$d = new Db();
$u = new Utils();

$title = "Admin panel";
require_once "include/header.php";
if(Session::check()):
?>
		<div class="menu">
		<?php if(Session::check("admin")): ?>
			<a href="active-event" id="active-event">Active event</a>
			<a href="add-event" id="add-event">Add event</a>
			<a href="subject" id="add-subject">Add subject</a>
			<a href="add-teacher" id="add-teacher">Add teacher</a>
			<a href="http://blog.giis.com/create.php">Create blog</a>
			<a href="http://blog.giis.com/manage.php">Manage blog</a>
			<a href="teachers" id="teachers" class="active">Teachers</a>
			<a href="report" id="student-report">Students report</a>
		<?php elseif(Session::check("teacher")): ?>
			<a href="student" id="add-student">Add student</a>
			<a href="exam" id="exam">Exam</a>
			<a href="promote" id="promote">Promote student</a>
			<a href="exam" id="reset">Reset exam</a>
			<a href="students" id="student-listing" class="active">Students</a>
			<a href="report" id="student-report">Students report</a>
		<?php endif; ?>
		<span class="close">&times;</span>
		</div>
		<div class="toggler-menu">
			<span></span>
			<span></span>
			<span></span>
		</div>
<?php
endif;
if(Session::check("admin")):
$title = "Admin panel";
//admin
$d->colSelect("teacher", ["id", "pre", "fullname", "class", "picture"], [
	["id", "!=", Session::get("admin")]
]);
print_r($d);
$teacher = $d->result();
?>

		<div class="options col-11 col-sm-8 col-md-6">
			<div class="student-listing mt-4 teachers">
				<div class="h2 my-3">Teachers</div>
				<?php if(!$d->count()): ?>
				<div class="text-center">No teachers yet!</div>
				<?php else: foreach($teacher as $r):?>
				<div class="student-list">
					<img src="<?php echo $r->picture ?>" alt="<?php echo $r->fullname ?>">
					<div>
						<b><?php echo $r->pre . ". " . $r->fullname ?></b>
						<br/>
						<small>Class &raquo; <?php echo $r->class ?></small>
						<br>
						<a href="remove.php?id=<?php echo $r->id ?>" id="<?php echo $r->id ?>" class="btn btn-success remove-teacher">
							Remove teacher
						</a>
					</div>
				</div>
				<hr><?php endforeach;endif; ?>
			</div>
			<div class="add-event mt-4">
				<div class="h2 my-3">Create event</div>
				<form method="post" class="add-event" id="create-event">
					<div class="form-group">
						<label for="event">Select event</label>
						<select name="event" id="event" class="form-control">
							<option value="">
								Select event
							</option>
							<option>Admission</option>
							<option>Exams</option>
							<option>Vacancy</option>
						</select>
					</div>
					<div class="items-to-add">
					</div>
					<div class="form-group">
						<div class="event-info"></div>
						<button class="btn btn-success">Create</button>
					</div>
				</form> 
			</div>
			<div class="student-report mt-4">
				<div class="h2 my-3">Student reports</div>
				<div class="form-group">
					<label>Class</label>
					<select id="report-student" class="form-control">
						<option value="">Select class</option>
						<option>Primary 6</option>
						<option>Jss 1</option>
						<option>Jss 2</option>
						<option>Jss 3</option>
						<option>Sss 1</option>
						<option>Sss 2</option>
						<option>Sss 3</option>
					</select>
				</div>
				<div class="report">
					<table class="table table-stripe">
						<thead>
					 		
						</thead>
						<tbody>
							
						</tbody>
					</table>
	 			</div>
			</div>
			<div class="mt-4 active-event">
				<div class="h2 my-3">Active events</div>
				<?php $d->get_all("event");
				$div = "";
				$eve = $d->result();

				if($d->count()) {
					foreach ($eve as $e) {
						$type = $u->djson($e->types);

						if(is_array($type)) {
							$details = "Start {$type[0]}<br>Ends {$type[1]}";
						}else {
							$details = $e->types;
						}
						$div .= <<<__here
							<div class="each mb-3">
								{$e->name}<span class="delete-event close" id="{$e->id}">&times;</span>
								<br>
								<small>
									$details
								</small>
							</div>
__here;
					}
				}else {
					$div = "No event!";
				}
				?>
				<div class="events">
					<?php echo $div; ?>
					<div class="event-del-info"></div>
				</div>
			</div>
			<div class="add-teacher">
				<form method="post" id="create-teacher" class="">
					<h2>Add teacher</h2>
					<div class="form-group">
						<label for="pre">Prefix</label>
						<select name="pre" id="pre" class="form-control">
							<option value="">Prefix</option>
							<option>Miss</option>
							<option>Mr</option>
							<option>Mrs</option>
						</select>
					</div>
					<div class="form-group">
						<label for="first">Firstname</label>
						<input name="first" type="text" id="first" placeholder="Enter firstname..." class="form-control">
					</div>
					<div class="form-group">
						<label for="last">Lastname</label>
						<input name="last" type="text" id="last" placeholder="Enter lastname..." class="form-control">
					</div>
					<div class="form-group">
						<label for="email">Email</label>
						<input name="email" type="email" id="email" placeholder="Enter email address" class="form-control">
					</div>
					<div class="form-group">
						<label for="status">Status</label>
						<select name="status" id="status" class="form-control">
							<option value="">Status</option>
							<option>Single</option>
							<option>Married</option>
						</select>
					</div>
					<div class="form-group">
						<label for="dob">Date of birth</label>
						<input name="dob" type="date" id="dob" class="form-control">
					</div>
					<div class="form-group">
						<label for="hadd">Address</label>
						<input name="hadd" type="text" id="hadd" placeholder="Enter address..." class="form-control">
					</div>
					<div class="form-group">
						<label for="class">Class</label>
						<select name="class" id="cls" class="form-control">
							<option value="">Select class</option>
							<option>Primary 6</option>
							<option>Jss 1</option>
							<option>Jss 2</option>
							<option>Jss 3</option>
							<option>Sss 1</option>
							<option>Sss 2</option>
							<option>Sss 3</option>
						</select>
					</div>
					<div class="form-group">
						<div class="teacher-info"></div>
						<button class="btn btn-success">Add!</button>
					</div>
				</form>
			</div>
			<div class="add-subject mt-4">
				<div class="h2 my-3">Add subject</div>
				<form method="post" id="create-subject">
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" name="name" id="name" class="form-control" placeholder="Enter subject name...">
					</div>
					<div class="form-group">
						<label>Class</label>
						<select name="class[]" id="class" class="form-control" size="1" multiple>
							<option value="">Select class</option>
							<option>Primary 6</option>
							<option>Jss 1</option>
							<option>Jss 2</option>
							<option>Jss 3</option>
							<option>Sss 1</option>
							<option>Sss 2</option>
							<option>Sss 3</option>
						</select>
					</div>
					<div class="form-group">
						<div class="subject-info"></div>
						<button class="btn btn-success">Add subject</button>
					</div>
				</form>
			</div>
			<div class="exam mt-4">
				<div class="h2 my-3">Set exam</div>
				<div class="set">
					<input type="number" id="set" class="form-control" placeholder="Enter how many question you want to set">
				</div>
				<form method="post" id="" class="form-question" style="display:none;">
					<div class="form-group">
						<label for="question-">Question</label>
						<input type="text" name="question-" id="question-" class="form-control" placeholder="Enter question...">
					</div>
					<div class="form-group">
						<label for="ans-">Answer</label>
						<input type="text" name="ans-" id="ans-" class="form-control" placeholder="Enter answer">
					</div>
					<div class="form-group">
						<label for="opt-a">Option a</label>
						<input type="text" name="opt-a[]" id="opt-a" class="form-control" placeholder="Enter option...">
					</div>
					<div class="form-group">
						<label for="opt-b">Option b</label>
						<input type="text" name="opt-b[]" id="opt-b" class="form-control" placeholder="Enter option...">
					</div>
					<div class="form-group">
						<label for="opt-c">Option c</label>
						<input type="text" name="opt-c[]" id="opt-c" class="form-control" placeholder="Enter option...">
					</div>
					<div class="form-group">
						<label for="opt-d">Option d</label>
						<input type="text" name="opt-d[]" id="opt-d" class="form-control" placeholder="Enter option...">
					</div>
				</form>
			</div>
		</div>

<?php
elseif(Session::check("teacher")):
$title = "Teacher's panel";
require_once "include/header.php";

$a->user_profile("teacher", "teacher", ["password","first","last", "picture", "class"]);
$name = $a->data->first . " " . $a->data->last;
$pic = $a->data->picture;
$cls = $a->data->class;
Session::set("class", $cls);
?>

		<div class="bg-topbom">
			<img src="<?php echo $pic ?>" alt="<?php echo $name; ?>">
			<div>
				<b><?php echo $name; ?></b>
				<br><?php echo $cls; ?>
			</div>
		</div>
<?php
if($v->p_hash("Default000") === $a->data->password):
?>
		<div class="options col-11 col-sm-8 col-md-6 col-lg-5 mx-auto">
			<div class="item-a">
				<form method="post" id="chpwd">
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
			<div class="item-b">
				<form method="post" enctype="multipart/form-data" id="img">
					<div class="h2 my-3">Upload picture</div>
					<div class="form-group">
						<div class="holder">
							<input type="file" name="file[]" id="file" class="file-input">
							<span></span>
						</div>
					</div>
					<div class="form-group">
						<div class="img-info"></div>
						<button class="btn btn-success">Upload</button>
					</div>
				</form>
			</div>
		</div>

<?php else:
$d->advance("student", ["id", "first", "last", "age", "picture", "student_id"], [["class", "=", Session::get("class")], ["opt", "!=", 1]], "and", ["first", "asc", $d->getpage("student", [["class", "=", Session::get("class")]])]);

$res = $d->result();
?>
		<div class="options col-11 mx-auto">
			<div class="student-listing mt-4">
				<div class="h2 my-3">Students</div>
				<?php if(!$d->count()): ?>
				<div class="text-center">No student in this class!</div>
				<?php else: foreach($res as $r):?>
				<div class="student-list">
					<img src="<?php echo $r->picture ?>" alt="<?php echo $r->first ?>">
					<div>
						<b><?php echo $r->first . " " . $r->last ?></b>
						<br/>
						<small>Age &raquo; <?php echo $r->age ?></small>
						<br>
						<small>Id &raquo; <?php echo $r->student_id ?></small>
						<br>
						<a href="remove.php?id=<?php echo $r->id ?>" id="<?php echo $r->id ?>" class="btn btn-success remove-student">
							Remove student
						</a>
					</div>
				</div>
				<hr><?php endforeach;endif; ?>
			</div>
			<div class="promote mt-4">
				<div class="h2 my-3">Students</div>
				<?php if(!$d->count()): ?>
				<div class="text-center">No student in this class!</div>
				<?php else: ?>
				<div class="student-list">
					<form id="promotion">
						<?php foreach($res as $r): ?>
						<div class="form-group select-student">
							<div>
								<input type="checkbox" name="promote[]" class="mr-3" value="<?php echo $r->student_id ?>"><b><?php echo $r->first . " " . $r->last ?></b>
							</div>
						</div>
						<?php endforeach; ?>
						<div class="form-group">
							<label>Class</label>
							<select id="promote-class" name="to" class="form-control">
								<option value="">Select class</option>
								<option>Primary 6</option>
								<option>Jss 1</option>
								<option>Jss 2</option>
								<option>Jss 3</option>
								<option>Sss 1</option>
								<option>Sss 2</option>
								<option>Sss 3</option>
							</select>
						</div>
						<div class="form-group">
							<div class="promote-info"></div>
							<button class="btn btn-success">Promote</button>
						</div>
						<?php endif; ?>
					</form>
				</div>
			</div>
			<div class="add-student mt-4">
				<form method="post" id="create-student" class="">
					<h2 class="my-3">Add student</h2>
					<div class="form-group">
						<label for="first">Firstname</label>
						<input name="first" type="text" id="first" placeholder="Enter firstname..." class="form-control">
					</div>
					<div class="form-group">
						<label for="last">Lastname</label>
						<input name="last" type="text" id="last" placeholder="Enter lastname..." class="form-control">
					</div>
					<div class="form-group">
						<label for="gender">Gender</label>
						<select name="gender" id="gender" class="form-control">
							<option value="">Gender</option>
							<option>Female</option>
							<option>Male</option>
						</select>
					</div>
					<div class="form-group">
						<label for="dob">Date of birth</label>
						<input name="dob" type="date" id="dob" class="form-control" min="1990-01-01" max="2015-01-01">
					</div>
					<div class="form-group">
						<label for="hadd">Address</label>
						<input name="hadd" type="text" id="hadd" placeholder="Enter address..." class="form-control">
					</div>
					<div class="h3 my-3">Parent's section'</div>
					<div class="form-group">
						<label for="pre">Prefix</label>
						<select name="pre" id="pre" class="form-control">
							<option value="">Prefix</option>
							<option>Mr</option>
							<option>Mrs</option>
						</select>
					</div>
					<div class="form-group">
						<label for="p-first">Firstname</label>
						<input name="p-first" type="text" id="p-first" placeholder="Enter firstname..." class="form-control">
					</div>
					<div class="form-group">
						<label for="p-last">Lastname</label>
						<input name="p-last" type="text" id="p-last" placeholder="Enter lastname..." class="form-control">
					</div>
					<div class="form-group">
						<label for="email">Email</label>
						<input name="email" type="email" id="email" placeholder="Enter email address" class="form-control">
					</div>
					<div class="form-group">
						<div class="student-info"></div>
						<button class="btn btn-success">Add!</button>
					</div>
				</form>
				<form method="post" enctype="multipart/form-data" id="img">
					<div class="h2 my-3">Upload picture</div>
					<div class="form-group">
						<div class="holder">
							<input type="file" name="files[]" id="file" class="file-input student">
							<span></span>
						</div>
					</div>
					<div class="form-group">
					 <div class="img-info"></div>
						<button class="btn btn-success">Upload</button>
					</div>
				</form>
			</div>
			<div class="exam mt-4">
				<?php $d->colSelect("subject", ["name"], [["class", "=", Session::get("class")]]);
				$sub = $d->result(); ?>
				<div class="h2 my-3">Set exam</div>
				<p class="note text-left mb-3">
				<small>Note: If there is already a subject saved, any question set will be an update to the subject!</small>
				</p>
				<div class="form-group set">
					<input type="number" id="set" class="form-control" placeholder="Enter number...">
				</div>
				<div class="form-group select">
					<select name="subject" class="form-control" id="exam-sub">
						<option value="">Select subject</option>
						<?php foreach($sub as $r): ?>
							<option><?php echo $r->name; ?></option>
						<?php endforeach; ?>
					</select>
					<div class="subject-info"></div>
				</div>
				<form method="post" id="" class="form-question mt-4">
					
				</form>
			</div>
			<div class="reset mt-4">
				<div class="h2 my-3">Reset exam</div>
				<form method="post" id="exam-reset">
					<div class="form-group">
						<label for="subject">Select subject</label>
						<select class="form-control" id="subject" name="reset">
							<option value="">Select subject</option>
							<option>All</option>
							<?php foreach($sub as $r): ?>
							<option><?php echo $r->name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<div class="reset-info"></div>
						<button class="btn btn-success">Reset</button>
					</div>
				</form>
			</div>
			<?php
			foreach ($res as $r) {
				$d->colSelect("score", ["*"], [["class", "=", Session::get("class")], ["s_id", "=", $r->id]]);
				$subject = [];
				
				for($i = 0; $i < count($sub); $i++) {
					array_push($subject, $sub[$i]->name);
				}
				$tr = "";
				$th = "";
				$td = "";
				$rep = "";
				$subs = 0;
				$subt = 0;
				
				for ($i = 0; $i < count($subject); $i++) {
					if(!$d->count()) {
						$tr = "<tr><td colspan=5>No result to display</td</tr>";
					} else {
						foreach($d->result() as $ss) {
							$data = $u->djson($ss->data);
							$c = $u->m_array_search($subject[$i], $data);

							if($c[$subject[$i]]) {
								$th .= "<th> $subject[$i] </th>";
								$td .= "<td> {$c["score"]} </td>";
							} else {
								$th .= "<th> $subject[$i] </th>";
								$td .= "<td> N/A </td>";
							}
							
							if ($ss->session == 1) {
								$session = $ss->session . "st term";
							} elseif ($ss->session == 2) {
								$session = $ss->session . "nd term";
							} elseif ($ss->session == 3) {
								$session = $ss->session . "rd term";
							}
						$thh = "<thead><th>Session</th>$th</thead>";
						$tr .= "<tr><td>$session</td>$td</tr>";
						$td = ""; $th = "";
						echo $thh;break;
						}
						/*echo $rep .= <<<__here
						<div class="h4 my-3">$r->first $r->last</div>

						<table class="table table-stripe">
							$thh
							<tbody>
								$tr
							</tbody>
						</table>
__here;
						$tr = "";*/
					}


					/*$session = "Total";
					if($c[$subject[$i]]) {
						$score = explode("/", $c["score"]);
						print_r($score);
						if($score[0] != 0 || $score[1] != 0) {
							$subs += $score[0];
							$subt += $score[1];
						}
						$td .= "<td> $subs / $subt </td>";
					} else {
						$td .= "<td> N/A </td>";
					}*/
				}
				echo $tr;
			}
			?>
			<div class="student-report mt-4">
				<div class="h2 my-3">Students report</div>
				<?php echo $rep; ?>
			</div>
		</div>
<?php endif; else:
Redirect::to("login");
endif;	
?>
<script src="assets/js/admin.js"></script>
<?php require_once "include/footer.php";