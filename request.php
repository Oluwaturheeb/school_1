<?php
#sleep(2);

//calling classes

require_once 'class/config.php';

$d = new Db();
$a = new Action();
$u = new Utils();
$v = new Validate();


//requests

// unique data

if(isset($_POST[1],$_POST[2],$_POST[3])){
	$v->validator($_POST, [
		1 => [
			"require" => true,
			"wordcount" => 1,
			"field" => "",
			"error" => ""
		],
		2 => [
			"require" => "true",
			"field" => "",
			"error" => ""
		],
		3 => [
			"require" => "true",
			"field" => "",
			"error" => ""
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else {
		$d->colSelect(lcfirst($v->fetch(1)), ["id"], [[lcfirst($v->fetch(2)), "=", $v->fetch(3)], ["class", "=", Session::get("class")]]);
		
		if($d->error()){
			echo $d->error();
		}else{
			if($d->count()){
				echo "avail";
			}else{
				echo "ok";
			}
		}
	}
}

if(isset($_POST['email'], $_POST['password'])){
	$v->validator($_POST, [
		"email" => ["require" => true, "field" => "email", "error" => ""],
		"password" => ["require" => true, "min" => 8, "max" => 64, "field" => "password", "error" => ""]
	]);
	
	if(!$v->pass()){
		echo $u->json(["stat" => "error" ,"error" => $v->error()]);
	}else{
        $a->login(["teacher", "student"], [
			["id", "class", "'teacher' as fullname"],
			["id", "class", "'student' as fullname"]
		],
            [
				[
					[
						["email", "fullname"], ["or", "="], $v->fetch("email")],
            			["password", "=", $v->p_hash($v->fetch("password"))]], 
				"same"]
        );
		
		if($a->error()){
			echo $u->json(["stat" => "error", "error" => $a->error()]);
		}else{
			if($a->data->fullname == "teacher"){
				$key = 1;
				if(empty($a->data->class)) {
					Session::set("admin", $a->data->id);
				} else {
					Session::set("teacher", $a->data->id);
					Session::set("class", $a->data->class);
				}
			} elseif ($a->data->fullname == "student") {
				$key = 2;
				Session::set("student", $a->data->id);
				Session::set("class", $a->data->class);
			}
			echo $u->json(["stat" => "ok", "res" => $key]);
		}
	}
}

if(isset($_POST["name"], $_POST["class"])){
	$v->validator($_POST, [
		"name" => [
			"require" => true,
			"error" => "",
			"field" => "subject name"
		],
		"class" => [
			"multiple" => true,
			"error" => "Kindly select an item from the list!",
			"field" => "Class"
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		if(count($v->fetch("class"))){
			$i = 1;
			foreach($v->fetch("class") as $d){
				$a->create("subject", [
					"class" => $d,
					"name" => $v->fetch("name")
				]);
				if($a->error()){
					echo $a->error();
					break;
				}
				
				if(count($v->fetch("class")) == $i){
					echo "ok";
				}$i++;
			}
		}
	}
}

if(isset($_POST['event'])) {
	if(!empty($_POST["job"])){
		$var = "job";
	} elseif (!empty($_POST["start"])) {
		$var = "start";
	} else if (!empty($_POST["session"])) {
		$var = "session";
	}
	
	if($var != 'start') {
		$val = "require";
	} else {
		$val = "multiple";
	}

	
	$v->validator($_POST, [
		"event" => [
			"require" => true,
			"error" => "Select event name",
			"field" => ""
		],
		$var => [
			$val => true,
			"error" => "This field cannot be empty",
			"field" => ""
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	} else {
		if(is_array($v->fetch($var))) {
			$save = $u->json($v->fetch($var));
		} else {
			$save = $v->fetch($var);
		}
		$a->create("event", [
		 "name" => $v->fetch("event"),
		 "types" => $save
		]);
		
		if($a->error()) {
			echo $a->error();
		} else {
			echo "ok";
		}
	}
}

if(isset($_POST["event-id"])) {
	$v->validator($_POST, [
		"event-id" => [
			"require" => true,
			"number" => true,
			"error" => "Cannot understand the argument passed",
			"field" => "event"
		]
	]);
	
	if(!$v->pass()) {
		echo $v->error();
	} else {
		$d->delete("event", [["id", "=", $v->fetch("event-id")]]);
		
		if(!$d->count()){
			echo $d->error();
		} else {
			echo "ok";
		}
	}
}

if(isset($_POST['first'], $_POST['last'], $_POST['status'])){
	$v->validator($_POST, [
		"pre" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "prefix",
			"error" => ""
		],
		"first" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "firstname",
			"error" => ""
		],
		"last" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "lastname",
			"error" => ""
		],
		"email" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "email",
			"email" => true,
			"unique" => "teacher",
			"error" => "There another account with the same email address!"
		],
		"status" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "status",
			"error" => ""
		],
		"dob" => [
			"require" => true,
			"field" => "Date of birth",
			"error" => ""
		],
		"hadd" => [
			"require" => true,
			"wordcount" => 4,
			"field" => "address",
			"error" => ""
		],
		"class" => [
			"require" => true,
			"field" => "class",
			"error" => ""
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		$a->create("teacher", [
			"pre" => $v->fetch("pre"),
			"first" => $v->fetch("first"),
			"last" => $v->fetch("last"),
			"fullname" =>  $v->fetch("first") . " " . $v->fetch("last"),
			"status" => $v->fetch("status"),
			"level" => 0,
			"age" => $u->age($v->fetch("dob")),
			"dob" => $v->fetch("dob"),
			"class" => $v->fetch("class"),
			"hadd" => $v->fetch("hadd"),
			"email" => $v->fetch("email"),
			"password" => $v->p_hash("Default000")
		]);
		
		if($a->error()){
			echo $a->error();
		}else{
			echo "ok";
		}
	}
}

if(isset($_POST['fpass-email'])){
	$v->validator($_POST, [
		"fpass-email" => ["require" => true, "wordcount" => 1, "field" => "email", "error" => "Sorry, this email does not match any account on this server!"]
		]);
		
		if(!$v->pass()){
			echo $v->error();
		}else{
			$d->colSelect("teacher", ["id"], [["email", "=", $v->fetch("fpass-email")]]);
			
			if($d->count()){
				echo "ok";
				Session::set("email", $v->fetch("fpass-email"));
			}else{
				echo "Sorry, this email does not match any account on this server!";
			}
		}
}

if(isset($_POST["pass"], $_POST["v-pass"])){
	$v->validator($_POST, [
		"pass" => [
			"require" => true,
			"wordcount" => 1,
			"min" => 8,
			"max" => 64,
			"field" => "password",
			"error" => "too short"
		],
		"v-pass" => [
			"require" => true,
			"wordcount" => 1,
			"min" => 8,
			"max" => 64,
			"match" => "pass",
			"field" => "verify password",
			"error" => "password do not match!!!"
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		if(Session::check("teacher")){
			$a->update("teacher", ["password" => $v->p_hash($v->fetch("pass"))], Session::get("teacher"));
		}else{
			$a->update("teacher", ["password" => $v->p_hash($v->fetch("pass"))], ["email", Session::get("email")]);
			Session::del("email");
		}
		
		if($a->error()){
			echo $a->error();
		}else{
			echo "ok";
		}
	}
}

//add student {}

if(isset($_POST["p-first"], $_POST["p-last"], $_POST["gender"])){
	$v->validator($_POST, [
		"first" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "Student firstname",
			"error" => ""
		],
		"last" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "Student lastname",
			"error" => ""
		],
		"gender" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "gender",
			"error" => ""
		],
		"dob" => [
			"require" => true,
			"field" => "date of birth",
			"error" => ""
		],
		"hadd" => [
			"require" => true,
			"wordcount" => 4,
			"field" => "address",
			"error" => ""
		],
		"p-first" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "parent firstname",
			"error" => ""
		],
		"p-last" => [
			"require" => true,
			"wordcount" => 1,
			"field" => "parent lastname",
			"error" => ""
		],
		"email" => [
			"require" => true,
			"wordcount" => 1,
			"email" => true,
			"field" => "email",
			"error" => ""
		],
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		$gen = $u->gen();
		$a->create("student", [
			"first" => $v->fetch("first"),
			"last" => $v->fetch("last"),
			"gender" => $v->fetch("gender"),
			"dob" => $v->fetch("dob"),
			"age" => $u->age($v->fetch("dob")),
			"hadd" => $v->fetch("hadd"),
			"p_first" => $v->fetch("p-first"),
			"p_last" => $v->fetch("p-last"),
			"email" => $v->fetch("email"),
			"joined" => date("m, Y"),
			"class" => Session::get("class"),
			"fullname" => $v->fetch("first") . " " .$v->fetch("last"),
			"student_id" => $gen, 
			"password" => $v->p_hash($gen)
		]);
		
		if($a->error()){
			echo $a->error();
		}else{
			Session::set("student", $a->id());
			echo "ok";
		}
	}
}

if(isset($_POST['question'], $_POST['ans'])){
	$v->validator($_POST, [
		"subject" => [
			"require" => true,
			"field" => "",
			"error" => "Select a subject"
		],
		"question" => [
			"multiple" => true,
			"error" => " field is empty",
			"field" => "A question"
		],
		"ans" => [
			"multiple" => true,
			"error" => " field is empty",
			"field" => "An answer "
		],
		"opt-a" => [
			"multiple" => true,
			"error" => "field is empty",
			"field" => "An option "
		],
		"opt-b" => [
			"multiple" => true,
			"error" => "field is empty",
			"field" => "An option "
		],
		"opt-c" => [
			"multiple" => true,
			"error" => "field is empty",
			"field" => "An option "
		],
		"opt-d" => [
			"multiple" => true,
			"error" => "field is empty",
			"field" => "An option "
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		$d->colSelect("exam", ["*"], [["class", "=", Session::get("class")], ["subject", "=", $v->fetch("subject")]]);
		
		if($d->count()){
			$r = $d->first();
			
			$opt_a = explode("superexam", $r->opt_a);
			array_push($opt_a, $u->arr2str("superexam", $v->fetch('opt-a')));
			$opt_b = explode("superexam", $r->opt_b);
			array_push($opt_b, $u->arr2str("superexam", $v->fetch('opt-b')));
			$opt_c = explode("superexam", $r->opt_c);
			array_push($opt_c, $u->arr2str("superexam", $v->fetch('opt-c')));
			$opt_d = explode("superexam", $r->opt_d);
			array_push($opt_d, $u->arr2str("superexam", $v->fetch('opt-d')));
			$q = explode("superexam", $r->question);
			array_push($q, $u->arr2str("superexam", $v->fetch("question")));
			$ans = explode("superexam", $r->answer);
			array_push($ans, $u->arr2str("superexam", $v->fetch("ans")));
			
			$a->update("exam", [
				"question" => $u->arr2str('superexam', $q),
				"answer" => $u->arr2str('superexam', $ans),
				"opt_a" => $u->arr2str('superexam', $opt_a),
				"opt_b" => $u->arr2str('superexam', $opt_b),
				"opt_c" => $u->arr2str('superexam', $opt_c),
				"opt_d" => $u->arr2str('superexam', $opt_d)
			],[
				["class", "=", Session::get("class")],
				["subject", "=", $v->fetch("subject")]
			]);
			
			if($a->error()){
				echo $a->error();
			}else{
				echo "ok";
			}
		}else{
			$a->create("exam", array(
				"class" => Session::get("class"),
				"subject" => $v->fetch("subject"),
				"question" => $u->arr2str("superexam", $v->fetch("question")),
				"answer" => $u->arr2str("superexam", $v->fetch("ans")),
				"opt_a" => $u->arr2str("superexam", $v->fetch("opt-a")),
				"opt_b" => $u->arr2str("superexam", $v->fetch("opt-b")),
				"opt_c" => $u->arr2str("superexam", $v->fetch("opt-c")),
				"opt_d" => $u->arr2str("superexam", $v->fetch("opt-d")),
			));
			
			if($a->error()){
				echo $a->error();
			}else{
				echo "ok";
			}
		}
	}
}

if(isset($_POST["exam-ans"])){
	$v->validator($_POST, [
		"exam-ans" => [
			"multiple" => true,
			"field" => "",
			"error" => ""
		]
	]);
	if (!$v->pass()) {
		echo $v->error();
	} else {
		$d->join(["subject", "event"], ["a.name", "types"], [["class", "!=", "b.name"]], ["left"], [["class", "=",Session::get("class")], ["b.name", "=", "exams"]]);
		$sess = $d->first()->types;
		$name = $d->result();
		$arr = [];

		$d->colselect("exam", ["answer", "class as s_id"], [["subject", "=", Session::get("subject")], ["class", "=", Session::get("class")]]);

		$re = $d->first();
		#calculating the scores
		
		$db = explode("superexam", $re->answer);
		$ans = $v->fetch("exam-ans");
		$score = count($db) - count(array_diff_assoc($db, $ans));
		$total = count($db);
		
//		$d->colSelect("score", ["data"], [["s_id", "=", Session::get("student")], ["session", "=", $sess . " and class = " . Session::get("class")]]);
		$d->customQuery("select data from score where (s_id = ? and session = ?) and class = ?", [Session::get("student"), $sess, Session::get("class")]);

		// checking if dia is data in db
		
		if ($d->count()) { 
			$re = $d->first();
			$data = $re->data;
		  	$data = Utils::djson($data);
		  	$sub = [];
			foreach($data as $key){
				foreach($key as $k => $v){
					if($k == Session::get("subject")){
						$res = [Session::get("subject") => 1, "score" => $score . " / " . $total];
						array_push($arr, $res);
						break;
					}else{
						array_push($arr, $key);
						break;
					}
				}
		  	}
		} else {
			for ($i = 0; $i < count($name); $i++) {
				$sub = $name[$i]->name;
				if (Session::get("subject") == $sub) {
					array_push($arr, [$sub => 1, "score" => $score . " / " . $total]);
				}else {
					array_push($arr, [$sub => 0, "score" => 0]);
				}
			}
		}
		
		if ($d->count()) {
			$a->update("score", ["data" => $u->json($arr)], [["s_id", "=", Session::get("student")], ["session", "=", $sess . " and class=" . Session::get("class")]]);
		} else {
			$a->create("score",[
				"class" => Session::get("class"),
				"s_id" => Session::get("student"),
				"Session" => $sess,
				"data" => $u->json($arr)
			]);
		}
		
		if($a->error()){
			echo $a->error();
		} else {
			echo "ok";
		}
	}
}

if(isset($_POST["reset"])){
	$v->validator($_POST, [
		"reset" => [
			"require" => true,
			"error" => " field cannot be empty!",
			"field" => "error"
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else {
		if($v->fetch("reset") === "All"){
			$d->delete("exam", [["class", "=", Session::get("class")]]);
		}else {
			$d->delete("exam", [["class", "=", Session::get("class")], ["subject", "=", $v->fetch("subject")]]);
		}
		if($d->count()){
			echo "ok";
		}else {
			echo $d->error();
		}
	}
}

if(isset($_POST["student-report"])) {
	$v->validator($_POST, [
		"student-report" => [
			"require" => true,
			"field" => "student-report",
			"error" => "Kindly select a class"
		]
	]);
	
	if(!$v->pass()) {
		echo $v->error();
	} else {
		$d->colSelect("subject", ["name"], [["class", "=", $v->fetch("student-report")]]);
		$sub = $d->result();
		$s = "";
		
		foreach($sub as $r) {
			$s .= "<th>$r->name</th>";
		}

		$d->join(["student", "score"], ["fullname", "data"], [["a.id", "=", "s_id"]], ["left"], [["a.class", "=", $v->fetch("student-report")], ["opt", "!=", 1]]);
		$re = $d->result();
		$tr = "";
		$td = "";

		for ($i = 0; $i < $d->count(); $i++) {
			$name = $re[$i]->fullname;
			$score = Utils::djson($re[$i]->data);
			if($score[$i]){
				for ($z = 0; $z < count($score); $z++) {
					if($score[$z]["score"]){
						$sc = $score[$z]["score"];
					} else {
						$sc = "N/A";
					}
					$td .= "<td>{$sc}</td>";
				}
			} else {
				$td = "<td>N/A</td>";
			}
			$tr .= "<tr><td>{$name}</td>{$td}</td>";
//			$s = "";
			$td = "";
			
		}
		echo json_encode(["subject" => $s, "report" => $tr]);
	}
}

if(isset($_POST["promote"], $_POST["to"])) {
	$v->validator($_POST, [
		"promote" => [
			"field" => "student",
			"error" => "select a student",
			"require" => true,
			"multiple" => true
		],
		"to" => [
			"field" => "class",
			"error" => "Select class",
			"require" => true
		]
	]);
	
	if(!$v->pass()) {
		echo $v->error();
	} else {
//		transaction
		$s = "";
		foreach($v->fetch("promote") as $p) {
			$s .= "update student set class={$v->fetch("to")} where student_id = {$p};";
		}
		$d->customQuery("START TRANSACTION;{$s} COMMIT;");
		print_r($d);
//		print_r($_POST);
	}
}

if(isset($_POST["remove-teacher"])) {
	$v->validator($_POST, [
		"remove-teacher" => [
			"require" => true, 
			"number" => true,
			"error" => "A numeric value is expected for this function!",
			"field" => "teacher info"
		]
	]);
	
	if (!$v->pass()) {
		echo $v->error();
	} else {
		$a->update("teacher", ["opt" => 1, "password" => null], $v->fetch("remove-teacher"));

		if($a->error()) {
			echo $a->error();
		} else {
			echo "ok";
		}
	}
}

if(isset($_POST["remove-student"])) {
	$v->validator($_POST, [
		"remove-student" => [
			"require" => true, 
			"number" => true,
			"error" => "A numeric value is expected for this function!",
			"field" => "student info"
		]
	]);
	
	if (!$v->pass()) {
		echo $v->error();
	} else {
		$a->update("student", ["opt" => 1, "student_id" => null, "password" => null], $v->fetch("remove-student"));
		print_r($a);
		if($a->error()) {
			echo $a->error();
		} else {
			echo "ok";
		}
	}
}

if (isset($_POST["promote"], $_POST["student"])) {
	$v->validator($_POST, [
		"require" => true,
		"number" => true
	]);
	
	if(!$v->pass()) {
		echo $v->error();
	} else {
		$a->update("student", [
			"class" => $v->fetch("promote")
		], $v->fetch("student"));
		
		if($a->error()) {
			echo $a->error();
		} else {
			echo "ok";
		}
	}
}

if(isset($_POST["review"])) {
	$v->validator($_POST, [
		"fullname" => [
		 "require" => true,
		 "wordcount" => 2,
		 "field" => "fullname",
		 "error" => "cannot be empty!"
		],
		"email" => [
		 "require" => true,
		 "email" => true,
		 "field" => "email",
		 "error" => "cannot be empty!"
		],
		"rating" => [
		 "require" => true,
		 "number" => true,
		 "field" => "fullname",
		 "error" => "cannot be empty!"
		],
		"review" => [
		 "require" => true,
		 "wordcount" => 5,
		 "field" => "review",
		 "error" => "cannot be empty!"
		]
	]);
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		$a->create("review", [
			"name" => $v->fetch("fullname"),
			"email" => $v->fetch("email"),
			"rating" => $v->fetch("rating"),
			"content" => $v->fetch("review"),
			"time" => $u->curr_time()
		]);
		
		if($a->error()) {
			echo $a->error();
		}else{
			echo "ok";
		}
	}
}

if(isset($_FILES["file"])){
	$v->uploader("file");
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		$a->update("teacher", ["picture" => $v->complete_upload("assets/img/teacher/")], Session::get("teacher"));
		
		if($a->error()){
			echo $a->error();
		}else{
			echo "ok";
		}
	}
}

if(isset($_FILES["files"])){
	$v->uploader("files");
	
	if(!$v->pass()){
		echo $v->error();
	}else{
		$a->update("student", ["picture" => $v->complete_upload("assets/img/student/")], Session::get("student"));
		
		if($a->error()){
			echo $a->error();
		}else{
			echo "ok";
		}
	}
}
