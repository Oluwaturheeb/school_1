<?php

class Validate {
	private $_pass = false, $_errors = array(), $_db;
	
	public function __construct(){
		$this->_db = Db::instance();
	}
	
	public function validator($src, $fields = array()){
		foreach($fields as $field => $options){
			$input = @trim($src[$field]);
			
			foreach($options as $rule => $value){
				$field_name = ucfirst($options['field']);
				$field_error = ucfirst($options['error']);
				
				if($rule == "required" && empty($input)){
					$this->addError("$field_name is required");
				}else{
					switch($rule){
						case "email":
							if(!strpos($input, ".") || !strpos($input, "@")){
								$this->addError($field_error);
							}
							break;
						case "match":
							if($input !== $src[$value]){
								$this->addError("Password do not match!");
							}
							break;
						case "max":
							if(strlen($input) > $value){
								$this->addError("Maximum character exceeded for $field_name field");
							}
							break;
						case "min":
							if(strlen($input) < $value){
								$this->addError("$field_name should be at least minimum of $input character!");
							}
							break;
						case "number":
							if(!is_numeric($input)){
								$this->addError("$field_name should have a numeric value!");
							}
							break;
						case "unique":
							$this->_db->colSelect($value, array($field_name), array(
								array($field_name, "=", $input)
							));
							if($this->_db->error()){
								$this->addError($this->_db->error());
							}else{
								if($this->_db->count() != 0){
									$this->addError($field_error);
								}
							}
							break;
						case "wordcount":
							$cal = $value - str_word_count($input);
							if(str_word_count($input) < $value){
								$this->addError("$field_name should have at least $value words! Remain $cal.");
							}
							break;
						case "multiple":
							if(!count(array_filter($src[$field]))){
								$this->addError($field_name . $field_error);
							}
					}
				}
			}
		}
		
		if(empty($this->_errors)){
			$this->_pass = true;
		}
	}
	
	public function uploader($data){
		$src = $_FILES;
		$folder = "assets/tmp/";
		$types = array('image/pjpeg', 'image/jpeg', 'image/gif', 'image/bmp', 'image/png', 'video/mpeg', 'video/mp4', 'video/quicktime', 'video/mpg', 'video/x-msvideo', 'video/x-ms-wmv', 'video/3ggp', 'audio/mid', 'audio/mp4', 'audio/mp3', 'audio/ogg', 'audio/wav', 'audio/3gpp', 'audio/mpeg', 'application/x-zip-compressed', 'application/x-msdownload');

		$file = $src[$data];
		$tmp = $file['tmp_name'];
		$name = $file['name'];
		$type = $file['type'];
		$size = $file['size'];
		$count = count($name);
		
		if($count > 5){
			$count = 5;
		}

		for($i = 0;$i < $count;$i++){
			if(!empty($name[$i])){
				if(!array_search($type[$i], $types)){
					$this->addError("$name[$i] type not supported!");
				}else{
					if(!move_uploaded_file($tmp[$i], $folder.$name[$i])){
						$this->addError("$name[$i] could not be uploaded!");
					}else{
						$file_name[] = $name[$i];
						$file_data[] = $folder.$name[$i];

						Session::set(
							"file", array(
								"name" => $file_name,
								"tmp" => $file_data
							)
						);
						$this->_pass = true;
					}
				}
			}
		}
	}
	
	public function complete_upload($dest){
		if(empty($dest)){
			throw new Exception("This method requires exactly 1 argument none given.");
		}else{
			$files = '';
			if(Session::check("file")){
				$file = Session::get("file");
				foreach($file as $key => $value){
					if($key == "name"){
						foreach($value as $name){
							copy("assets/tmp/".$name, $dest.$name);
							$files .= $dest.$name. "_str_";
						}
					}
					$file = array_filter(explode("_str_", $files));
					if(count($file) == 1){
						return $file[0];
					}else{
						return $file;
					}
				}
			}
			return false;
		}
	}
	
	public function p_hash($hash){
		return hash("sha256", $hash);
	}
	
	private function addError($error = ""){
		$this->_errors[] = $error;
	}
	
	public function error(){
		foreach($this->_errors as $error){
			return $error ."<br/>";
		}
	}
	
	public function pass(){
		return $this->_pass;
	}
	
	public function bound($src = "post"){
		switch($src){
		    case "post":
		        (isset($_POST)) ? true : false;
		        break;
		    case "get":
		        return (isset($_GET)) ? true : false;
		        break;
		}
		return false;
	}
	
	public function fetch($data){
		if(!empty($_POST)){
			if(is_array($_POST[$data])){
				return array_filter(array_map([$this, "filter"], $_POST[$data]));
			}else{
				return $this->filter($_POST[$data]);
			}
		}elseif(!empty($_GET)){
			if(is_array($_GET[$data])){
				return array_filter($_GET[$data]);
			}else{
				return $this->filter($_GET[$data]);
			}
		}
		return false;
	}
	
	public function filter($str){
		return htmlentities(trim((ucfirst($str))), ENT_QUOTES, "utf-8");
	}
	
	/*public function get($data, $src = 'post'){
		if ($src == 'post'){
			$src = $_POST;
		}else {
			$src = $_GET;
		}
		
		if(count($data) == 1){
			return (isset($rc[$data[0]])? true : false);
		}else{
			$ext = "";
			
			for ($i = 0; $i < count($data); $i++) {
				$ext = $src[$data[$i]];
			}
		}
		
		
	}*/
}