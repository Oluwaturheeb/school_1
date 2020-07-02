<?php
require_once "class/config.php";
$v = new Validate();
$d = new Db;

$title = $v->fetch("keyword");
require_once "include/header.php";

$v->validator($_GET, [
    "keyword" => [
        "require" => true,
        "error" => "",
        "field" => ""
    ]
]);

if($v->pass()) {
    $d->search("blog", ["time", "title", "url"], [["time", "like", $v->fetch("keyword")], ["title", "like", $v->fetch("keyword")], ["tags", "like", $v->fetch("keyword")]]);
    $div = "";
    $found = "<div class='found'>Found ". $d->count() ." result for your search!</div>";

    if($d->count()) {
        foreach ($d->result() as $e) {
            $div .= <<<__here
            <div class="cover">
                <a href="post.php?url=$e->url">
                    <small>
                        <img src="assets/img/search.svg">
                        http://blog.giis.com/post?url=$e->url
                    </small>
                    <div class="h2">$e->title</div>
                </a>
                <div class="meta my-2">
              <img src="assets/img/clock.svg"><i>$e->time</i>
                </div>
            </div>
__here;
        }
    }else {
        $div = "No result found for your search <b>" . $v->fetch("keyword") . "</b>";
    }
    echo "<div class='blog-list col-12 col-sm-8 mx-auto'>$found $div</div>";
}