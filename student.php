<?php
require_once "class/config.php";

$d = new Db();
$a = new Action();
$u = new Utils();

if(!Session::check("student"))
	Redirect::to("login.php");

$d->join(["student", "subject"], ["name", "a.class", "fullname", "picture"], [["a.class", "=", "b.class"]], ["left"], [["a.id", "=", Session::get("student")]], "", ["name", "asc"]);
$title = $d->first()->fullname;

Session::set("class", $d->first()->class);
$s = $d->first();
$sub = $d->result();

$d->colSelect("score", ["*"], [["s_id", "=", Session::get("student")]]);
$sc = $d->result();

if(!$d->count()) {
	$report = "No result to display";
} else {
	$subject = [];
	for($i = 0; $i < count($sub); $i++) {
		array_push($subject, $sub[$i]->name);
	}
	$th = "";
	$td = "";
	//$report = "No result to display/";
	$rep = "";
	$cur = "";
	foreach($sc as $ss) {
		$data = Utils::djson($ss->data);
		for ($i = 0; $i < count($subject); $i++) {
			if ($ss->session == 1) {
				$class = $ss->class;
				$session = $ss->session . "st term";

				$c = $u->m_array_search($subject[$i], $data);

				if($c[$subject[$i]]) {
					$th .= "<th> $subject[$i] </th>";
					$td .= "<td> {$c["score"]} </td>";
				} else {
					$th .= "<th> $subject[$i] </th>";
					$td .= "<td> N/A </td>";
				}
			} elseif ($ss->session == 2) {
				$class = "";
				$session = $ss->session . "nd term";

				$c = $u->m_array_search($subject[$i], $data);

				if($c[$subject[$i]]) {
					$th .= "<th> $subject[$i] </th>";
					$td .= "<td> {$c["score"]} </td>";
				} else {
					$th .= "<th> $subject[$i] </th>";
					$td .= "<td> N/A </td>";
				}
			} elseif ($ss->session == 3) {
				$class = "";
				$session = $ss->session . "rd term";

				$c = $u->m_array_search($subject[$i], $data);

				if($c[$subject[$i]]) {
					$th .= "<th> $subject[$i] </th>";
					$td .= "<td> {$c["score"]} </td>";
				} else {
					$th .= "<th> $subject[$i] </th>";
					$td .= "<td> N/A </td>";
				}
			}
		}
		if($ss->class == Session::get("class")) {
			$cur .= <<<__here
			<div class="h2 my-3">$class</div>
			<div class="h4 my-3">$session Report</div>

			<table class="table table-stripe">
				<thead>
					<th>Subjects</th>
					$th
				</thead>
				<tbody>
					<tr>
						<td>Score</td>
						$td
					</tr>
				</tbody>
			</table>
__here;
		$td = ""; $th = "";
		} else {
			$rep .= <<<__here
			<div class="h2 my-3">$class</div>
			<div class="h4 my-3">$session Report</div>

			<table class="table table-stripe">
				<thead>
					<th>Subjects</th>
					$th
				</thead>
				<tbody>
					<tr>
						<td>Score</td>
						$td
					</tr>
				</tbody>
			</table>
__here;
		$td = ""; $th = "";
		}
	}
	$report = $cur . $rep;
}
require_once "include/header.php";

?>
		<div class="menu">
			<a href="exam.php">Exam</a>
			<a href="student-report" class="active" id="student-report">Report</a>
			<span class="close">&times;</span>
		</div>
		<div class="toggler-menu">
			<span></span>
			<span></span>
			<span></span>
		</div>
		 
		<div class="bg-topbom">
			<img src="/<?php echo $s->picture ?>">
			<div>
				<b><?php echo $s->fullname; ?></b>
				<br><small><?php echo $s->class; ?></small>
			</div>
		</div>
		
		<div class="options col-11 mx-auto">
			<div class="student-report mt-4">
				<?php echo $report;?>
			</div>
		</div>
<?php
require_once "include/footer.php";