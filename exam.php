<?php
require_once "class/config.php";
$d = new Db();

if(!Session::get()){
	Redirect::to("/");
}
$title = "Exam center";
require_once "include/header.php";

$d->colSelect("event", ["types"], [["name", "=", "Exams"]]);
$eve = $d->first();
if($d->count()):
	$sss = $eve->types[0];
	$d->customQuery("select data from score where (s_id = ? and session = ?) and class = ?", [Session::get("student"), $sss, Session::get("class")]);

	if($d->count() == 0){
		$d->colSelect("exam", ["*"], [["class", "=", Session::get("class")]]);
	}else{
		$data = $d->first()->data;
		$data = Utils::djson($data);
		$to = [];
		$sub = [];
		foreach ($data as $sub) {
			foreach ($sub as $key => $val) {
				if($val == 0) {
					array_push($to, $key);
					break;
				}
			}
		}
		foreach ($to as $val) {
			$d->colSelect("exam", ["*"], [["class", "=", Session::get("class")], ["subject", "=", $val]]);
			if($d->count()) {
				break;
			}
		}
//		$d->colSelect("exam", ["*"], [["class", "=", Session::get("class")], ["subject", "=", $to]]);
	}
	if($d->count()):

	$r = $d->first();
	Session::set("subject", $r->subject);
	$q = explode("superexam", $r->question);
	$a = explode("superexam", $r->opt_a);
	$b = explode("superexam", $r->opt_b);
	$c = explode("superexam", $r->opt_c);
	$d = explode("superexam", $r->opt_d); 

	$all = Utils::json(["question" => $q, "a" => $a, "b" => $b, "c" => $c, "d" => $d]);
?>
<div class="exam col-11 col-sm-8 col-md-6">
	<div class="my-3">
		<h3><?php echo $r->subject ?></h3>
		<small></small>
	</div>
	<div class="e-question mb-3"><span></span></div>
	
	<div class="option">
		<div class="e-a mb-3" id="e-a"><input accesskey="a" name="opt_a" value="A" type="checkbox"> A. <span></span></div>
		<div class="e-b mb-3" id="e-b"><input accesskey="b" name="opt_b" value="B" type="checkbox"> B. <span></span></div>
		<div class="e-c mb-3" id="e-c"><input accesskey="c" name="opt_c" value="C" type="checkbox"> C. <span></span></div>
		<div class="e-d mb-3" id="e-d"><input accesskey="d" name="opt_d" value="D" type="checkbox"> D. <span></span></div>
	</div>
	<div class="exam-info"></div>
	<div class="control-btn my-3">
	 	<button class="btn btn-success" type="button" id="next">Next question</button>
	</div>
</div>

<script>
	var loop = <?php echo $all ?>;
	var info = $('.exam-info');
	
	var q = loop.question;
	var a = loop.a;
	var b = loop.b;
	var c = loop.c;
	var d = loop.d;
	var count = q.length;
	var next = 1;
	var init = 0;
	var arr = [];
	$('.e-question span').html(q[0]);
	$('.e-a span').html(a[0]);
	$('.e-b span').html(b[0]);
	$('.e-c span').html(c[0]);
	$('.e-d span').html(d[0]);
	$('.my-3 small').text('No ' + next +' of ' + count);
	
	$('.control-btn button').click(function(){
		var val = $('.option input:checkbox:checked').val();
		if(!v.empty(val, 'hjfy')){
			info.html('');
			$('.option input:checkbox').prop('checked', false);
			$('.option div').removeClass('active');
		
			$('.e-question span').html(q[0 + next]);
			$('.e-a span').html(a[0 + next]);
			$('.e-b span').html(b[0 + next]);
			$('.e-c span').html(c[0 + next]);
			$('.e-d span').html(d[0 + next]);

			if(next != count){
				next++;
			}

			$('.my-3 small').text('No ' + next +' of ' + count);

			if(arr.length != count){
				arr.push(val);
			}

			if(arr.length == count){
				var con = confirm('You are about to submit!');
				if(con){
					$.ajax({
						data: {'exam-ans': arr},
						success: e => {
							if(e == 'ok'){
								info.html("Submitted");
								v.redirect();
							}else{
								v.dError(e, true)
								info.html(e.msg);
							}
						},
						error: e => {
							info.html(e)
						}
					});
				} else {
					arr.pop();
				}
			}
		}else {
			info.html('Select an answer!');
		}
		
		
		
		
		
		
		
		
		
		
		//formular
		/*if(att == 'prev') {
		var att = $(this).attr('id');
			var inc = next - 1;
			next--;
		}else if (att == 'next') {
			inc = next + 1;
		}
		if (next < 1){
			inc = 1;
			next = 0;
		}*/
		
		/*if(next != count){
			$('.my-3 small').text('No ' + inc +' of ' + count);
			if(att == 'prev') {
				 next--;
			}else if (att == 'next') {
				next++;
			}
		}*/
	});
	
	$('.exam .option div').click(function(){
		var id = $(this).attr('id');
		$(this).addClass('active').siblings().removeClass('active');
		$(this).children('input').prop('checked', true);
		$(this).siblings().children('input').prop('checked', false);
	});
	
</script>
<?php else: ?>
<h1 class="my-4">No exam!</h1>
<?php
endif;
else:
?>
<h1 class="my-4">Not yet time for exam!</h1>
<?php endif; ?>


<div class="menu">
	<a href="student.php">Home</a>
	<div class="subject">
	<?php if(!empty($data)): 
	foreach($data as $sub):
		foreach($sub as $key => $val):
			if($val == 1):
				echo "<div class='done'>$key</div>";break;
			else:
				echo "<div class='next'>$key</div>";break;
			endif;
		endforeach;
	endforeach;
	endif;?>
	</div>
</div>
<div class="toggler-menu">
	<span></span>
	<span></span>
	<span></span>
</div>


<?php
require_once "include/footer.php";