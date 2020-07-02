<?php
require_once 'class/config.php';

Action::logout();
Redirect::to("login.php");