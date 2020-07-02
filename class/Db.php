<?php

class Db{
	private $_pdo, $_query, $_error = false, $_result = null, $_instance, $_count, $_lastid;
	public $paging = false, $next = 0, $prev;
	
	public function __construct(){
		try{
			$this->_pdo = new PDO("mysql:host=". config::get("db/host") .";dbname=" . config::get("db/database"), config::get("db/user"), config::get("db/password"));
		} catch(Exception $e){
			$this->_error = $e->getMessage();
		}
	}
	
	public static function instance(){
		if(!isset(self::$_instance)){
			$_instance = new Db();
		}
		return $_instance;
	}
	
	public function gen($where = [], $concat, $tt = 2, $sep = "or"){
		$op = "";$value = "";
		for($i = 0, $j = 1;$i < count($where); $i++, $j++){
			$v1 = $where[$i][0];
			$v2 = $where[$i][1];
			$v3 = $where[$i][2];

			if(is_array($v1)){
				if(is_array($v2)) {
					$v2_con = $v2[0];
					$v2_sign = $v2[1];
				} else {
					$v2_con = "or";
					$v2_sign = $v2;
				}
				$op .= "({$v1[0]} {$v2_sign} ?  {$v2_con} $v1[1] {$v2_sign} ?)";
				$value .= "{$v3}, {$v3}";
			}else {
				$op .= "{$v1} {$v2} ?";
				$value .= "{$v3}";
			}
			
			if($j < count($where)){
				$value .= ", ";
				if($j == $tt){
				    $op .= " {$sep} ";
				}else{
				    $op .= " {$concat} ";
				}
			}
		}
		$value = explode(", ", $value);
		$op = "where {$op}";
		return [$op, $value];
	}
	
	public function query($sql, $clauses = array()){
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)){
			if(count($clauses)){
				$i = 1;
				foreach($clauses as $clause){
					$this->_query->bindValue($i, $clause);
					$i++;
				}
			}

			if($this->_query->execute()){
				$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}else{
				$this->_error = $this->_query->errorInfo()[2];
			}
		}
		$this->_lastid = $this->_pdo->lastInsertId();
		return $this;
	}
	
	public function lastId() {
		return $this->_lastid;
	}
	
	/*
		** USAGE **
		** table --> dataset,
		** where --> array of key & value
	*/
	
	/*public function get_all($table, $where =[]){ 
		$query = "select * from {$table} where $where[0] = ?"; 
		$this->query($query, [$where[1]]);
	}*/
	
	public function get_all($table, $where = [], $concat = "and"){ 
		if(empty($where)) {
			$where = "";
			$val = [];
		} else {
			$where = $this->gen($where, $concat);
			$val = $where[1];
			$where = $where[0];
		}
		
		$query = "select * from {$table} {$where}"; 
		$this->query($query, $val);
	}
	
	/*
		** USAGE **
		$sql --> "select id from dataset where user = ?";
		$ops --> array(values);
	*/
	
	public function customQuery($sql, $ops = array()){
		if(is_array($ops)){
			$this->query($sql, $ops);
		}else{
			$this->_error = "customQuery expect an array for second parameter";
		}
	}
	
	/*
		** USAGE **
		** action <--> delete or select
		** table <--> dataset
		** where <--> multi-dimentional array
			array(
				array('id', '=', '1')
			)
		** concat --> default to "and" but can be change or not included in the parameter
	
	*/
	
	public function action($action, $table, $where = array(), $concat = "and"){
		if(count($where)) {
			$gen = $this->gen($where, $concat);
			$wia = $gen[0];
			$val = $gen[1];
		} else {
			$wia = "";
			$val = [];
		}
		
		$query = "{$action} from {$table} {$wia}";
		$this->query($query, $val);
	}
	
	/*
		** USAGE **
		** colSelect **
		** table --> dataset,
		** cols --> array('id', 'content', 'title')
		** ops --> multi-dimentional array
			array(
				array('id', '=', '1')
			)
	
	*/
	
	public function colSelect($table, $cols = array(), $ops = array(), $concat = "and"){
		$cols = implode(", ", $cols);
		$action = "select {$cols}";
		$this->action($action, $table, $ops, $concat);
	}
	
	
	public function join ($table = [], $cols = [], $match = [], $type = [], $where = [], $concat = "and", $sort = ["a.id", "desc", 10]) {
		$sub = ""; $once = ""; $on  = ""; $con = ""; $o = "";
		$abc = range("a", "z");
		$col = implode(", ", $cols);
		
		for ($i = 0, $j = 1; $i < count($table); $i++, $j++) {
			if($j <= count($match)) {
				if($type) {
					$join = " {$type[$i]} join ";
				} else {
					$join = " join ";
				}
				
				$on = " on {$match[$i][0]} {$match[$i][1]} {$match[$i][2]}";
			}
			$tab = "{$table[$i]} {$abc[$i]}";
			if($j == 1) {
				$o .= $on;
				$once .= $tab . $join;
			} else {
				if($o){
					$once .= $tab . $o;
					$o = "";
				} else {
					$con .= $join . $tab . $on;
				}
			}
		}
		$sub =  $once . $con;
		if($where){
			$gen = $this->gen($where, $concat);
			$wia = $gen[0];
			$value = $gen[1];
		} else {
			$wia = "";
			$value = [];
		}
		if(is_array($sort)){
			$s = "order by {$sort[0]} {$sort[1]}";
			if (count($sort) == 3) {
				$s .= " limit {$sort[2]}";
			}
		} else if (!empty($sort)) {
			$s = "limit {$sort}";
		} else {
			$sort = "";
		}
		$query = "select {$col} from {$sub} {$wia} {$s}";
		$this->query($query, $value);
	}
	
	public function union($table = [], $cols = [], $where = [], $con) {
		$q = ""; $v = [];
		
		foreach ($table as $num => $tab) {
			$col = implode(", ", $cols[$num]);
			if (!empty($where)) {
				$gen = $this->gen($where[$num], $con[$num]);
				$v = array_merge_recursive($v, $gen[1]);
				$w = $gen[0];
			} else {
				$w = "";
			}
			
			$q .= "select {$col} from {$tab} {$w}";
			
			if($num+1 < count($table)) {
				$q .= " union ";
			}
		}
		$this->query($q, $v);
	}
	
	/*
		** USAGE **
		** table <--> dataset
		** where <--> multi-dimentional array
			array(
				array('id', '=', '1')
			)
		** concat --> default to "and" but can be change or not included in the parameter

	*/
	
	public function delete($table, $where, $concat = "and"){
		$this->action("delete", $table, $where);
	}
	
	/*
		** USAGE **
		
		** table --> dataset
		$cols --> arrays of key/value pair
			keys => column name in db
			value => values to inserted
		array("title" => "some title", "content"  => "some content")
	*/
	
	public function insert($table, $cols = array()){
		$col = "";	$val = "";	$prep = "";	$i = 1;
		
		foreach($cols as $column => $value){
			$col .= $column;
			$val .= $value;
			$prep .= "?";
			
			if($i < count($cols)){
				$col .= ", ";
				$val .= "_____ ";
				$prep .= ", ";
			}
			$i++;
		}
		$query = "insert into {$table}({$col}) values({$prep})";                               
		$val = explode("_____ ", $val);
		$db= $this->query($query, $val);
		if($db->count()){
			return true;
		}
		return false;
	}
	
	/*
		** USAGE **
		table --> dataset,
		set --> array(keys => values) e.g
			array(
				"name" => "my name",
				... => ...
			),
		fields --> array(
			array(
				"column", "operator", "value" 
			),
			array(
				"column", "operator", "value" 
			)
		)
		concat --> default to;
	*/
	
	public function dbUpdate($table, $set = array(), $fields = array(), $concat = "and"){
		$col = "";	$value = "";	$i = 1;
		
		foreach($set as $keys => $values){
			$col .= "{$keys} = ?";
			$value .= "{$values}";
			
			if($i < count($set)){
				$col .= ", ";
				$value .= ",,,,, ";
			}
			$i++;
		}
		
		$g = $this->gen($fields, $concat);
		
		$values = explode(",,,,, ", $value);
		$val = array_merge($values, $g[1]);
		$query = "update {$table} set {$col} {$g[0]}";
		$this->query($query, $val);
		if(!$this->error()){
			if($this->count()) {
				return true;
			}
		}
		return false;
	}
	
	/*
	** search
	** USAGE

	** table  -> dataset
	** cols = the solumns to select
	** to -> multi-dimentional array -> array(
		array(
			column, operator, values
		)
	),
	** limit and ordering	
	*/

	public function search($table, $cols, $to = array(), $limit = "order by id desc limit 5"){
		if(is_array($to)){
			/* Where clause */

			$field = "";	$val = ""; $col = "";

			for($i = 0, $j = 1; $i < count($to); $i++, $j++){
				$field .= "{$to[$i][0]} {$to[$i][1]} ?";
				$val .= "%{$to[$i][2]}%";
				if($j <= count($cols)){
					$col .= $cols[$i];
					if($j < count($cols)){
						$col .= ", ";
					}
				}
				if($j < count($to)){
					$field .= " or ";
					$val .= ", ";
				}
			}
			$sel = $col;
			$values = explode(", ", $val);
			$sql = "select {$sel} from {$table} where {$field} {$limit}";
			$this->query($sql, $values);
		}
	}
	
	public function first(){
		return $this->result()[0];
	}
	public function error(){
		return $this->_error;
	}
	
	public function result(){
		return $this->_result;
	}
	
	public function count(){
		return $this->_count;
	}
	
	/* USAGE OF ADVANCE 
		
		$table = the table to query
		cols = the columns to select
		order = an array of single element and must be key/value pair
			array(
				key => the field to sort with
				value => ordering type "asc or desc"
			)
		$where = a multi-dimentional array 
			array(
				array(
					"column", "operator", "values"
				)
			)	
			
			or optionally a custom where clause
			array(
			    "where c", array(values)
			)
			e.g
			
			array("id != ?", array("20"));
			
		$concat = prefered way of joining the where clause
			values = "and / or"
	
	*/
	
	public function advance ($table, $cols = [], $where = [], $concat = "and", $sort = []){
		if ($where) {
			if (is_array($where[0])) {
				$gen = $this->gen($where, $concat);
				$value = $gen[1];
				$where = $gen[0];
			} else {
				$value = $where[1];
				$where = $where[0];
			}
		} else {
			$value = [];
			$where = "";
		}
		if ($sort) {
			$s = "order by {$sort[0]} {$sort[1]}";
			if (count($sort) == 3) {
				$s .= " limit {$sort[2]}";
			}
		} else {
			$s = "";
		}
		$cols = implode(", ", $cols);

		$sql = "select {$cols} from {$table} {$where} $s";
		$this->query($sql, $value);
	}
	
	public function getpage($table, $wia = [], $ppage = 5, $src = "more"){
		$this->colSelect($table, ["id"], $wia);
		$last = ceil($this->count() / $ppage);
		
		if (isset($_GET[$src])) {
			$each = $_GET[$src];
			if (is_numeric($each)) {
				if ($each == $last) {
					$each = $last;
				}
			} else {
				$each = 1;
			}
		} else {
			$each = 1;
		}

		if ($last > 1) {
			if ($each < $last) {
				$next = $each + 1;
				$prev = $each - 1;
			} else if ($each >= $last) {
				$next = $last;
				$prev = $last - 1;
			}
			$this->paging = true;
			$this->next = $next;
			$this->prev = $prev;
		}

		$limit = ($each - 1) * $ppage . ",$ppage";
		return $limit;
	}
}