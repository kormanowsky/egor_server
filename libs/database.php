<?php
/**
 * Database - СЂР°Р±РѕС‚Р° СЃ Р±Р°Р·РѕР№ РґР°РЅРЅС‹С…
 * @uses mysqli
 * @author M.Kormanowsky
 * @version 2.2 (04.04.2018)
 */
class Database{
    /**
     * РЎРѕРµРґРёРЅРµРЅРёРµ СЃ СЃРµСЂРІРµСЂРѕРј Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @var object|null
     */
    private static $conn = null;
    /**
     * РЎРµСЂРІРµСЂ Р±Р°Р·С‹ Р±Р°РЅРЅС‹С…
     * @var string
     */
    private static $dbserver = '';
    /**
     * РРјСЏ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @var string
     */
    private static $dbuser = '';
    /**
     * РџР°СЂРѕР»СЊ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @var string
     */
    private static $dbpass = '';
    /**
     * РРјСЏ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @var string
     */
    private static $dbname = '';
    /**
     * РўРµРєСЃС‚ РїРѕСЃР»РµРґРЅРµРіРѕ Р·Р°РїСЂРѕСЃР°
     * @var string
     */
    private static $last_query = '';
    /**
     * Р¤РѕСЂРјР°С‚ РІСЂРµРјРµРЅРё
     * @var string
     */
    public static $time_format = "Y-m-d H:i:s";
    /**
     * РџРѕРґРєР»СЋС‡РµРЅРёРµ Рє СЃРµСЂРІРµСЂСѓ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @method connect
     * @param string $dbserver РЎРµСЂРІРµСЂ Р±Р°Р·С‹ Р±Р°РЅРЅС‹С…
     * @param string $dbuser РРјСЏ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @param string $dbpass РџР°СЂРѕР»СЊ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @param string $dbname РРјСЏ Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @param string $log_file РџСѓС‚СЊ РґРѕ Р»РѕРі-С„Р°Р№Р»Р°
     * @return boolean
     */
    public static function connect(string $dbserver,string $dbuser,string $dbpass,string $dbname){
        self::$dbserver = $dbserver;
        self::$dbuser = $dbuser;
        self::$dbpass = $dbpass;
        self::$dbname = $dbname;
        self::$conn   = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if(self::$conn instanceof mysqli){
    		self::$conn->set_charset('utf8');
    		Events::run(__FUNCTION__ . "_success");
    		return true;
    	}else{
    		Events::run(__FUNCTION__ . "_error");
    		return false;
    	}
    }
    /**
     * РџРµСЂРµРїРѕРґРєР»СЋС‡РµРЅРёРµ Рє СЃРµСЂРІРµСЂСѓ Р±Р°Р·С‹ Р±Р°РЅРЅС‹С…
     * @method reconnect
     */
    public static function reconnect(){
        self::deconnect();
        self::connect(self::$dbserver,self::$dbuser,self::$dbpass,self::$dbname);
    }
    /**
     * Р­РєСЂР°РЅРёСЂРѕРІР°РЅРёРµ СЃС‚СЂРѕРєРё
     * @method escape
     * @param string $str РЎС‚СЂРѕРєР° РґР»СЏ СЌРєСЂР°РЅРёСЂРѕРІР°РЅРёСЏ
     * @return string
     */
    public static function escape($str){
      if(!is_string($str))
        return $str;
      return self::$conn->escape_string($str);
    }
    /**
     * РћС€РёР±РєР°
     * РџРѕР»СѓС‡РµРЅРёРµ РѕС€РёР±РєРё
     * @method error
     * @return string
     */
    public static function error(){
        return self::$conn->error;
    }
    /**
     * РџРѕР»СѓС‡РµРЅРёРµ РёРЅРґРµРЅС‚РёС„РёРєР°С‚РѕСЂР° РІСЃС‚Р°РІР»РµРЅРЅРѕР№ СЃС‚СЂРѕРєРё
     * @method get_insert_id
     * @return integer
     */
    public static function get_insert_id(){
        return self::$conn->insert_id;
    }
    /**
     * РџРѕР»СѓС‡РµРЅРёРµ С‚РµРєСЃС‚Р° РїРѕСЃР»РµРґРЅРµРіРѕ Р·Р°РїСЂРѕСЃР°
     * @method get_last_query
     * @return string
     */
    public static function get_last_query(){
        return self::$last_query;
    }
	/**
	 * РћР±СЂР°Р±РѕС‚РєР° where-СЃРµРєС†РёР№
	 * @method parse_where_clauses
	 * @param array $args РђСЂРіСѓРјРµРЅС‚С‹ С„СѓРЅРєС†РёР№ select(), insert(), delete(), update()
	 * @return array
	 */
	private static function parse_where_clauses(array &$args){
		if(!array_key_exists('where', $args) || !is_array($args['where']))
			$where_result = '';
		else{
    		$where_clauses = array_values($args['where']);
    		$where_result = "WHERE ";
    		foreach($where_clauses as $key=>$clause){
    			if(is_array($clause)){ //РњР°СЃСЃРёРІС‹ - С„РёР»СЊС‚СЂС‹, СЃС‚СЂРѕРєРё - СЃРІСЏР·РєРё, СЃРєРѕР±РєРё, РґСЂСѓРіРѕРµ
					if(is_array($clause[0])){
						$field = $clause[0];
						foreach($field as $k=>$v){
							$field[$k] = self::escape($v);
						}
						$_field = $field[0];
						$_jsonp = '"$.' . implode(".", array_slice($field, 1)) . '"';
						$field = '`' . $_field . "`->" . $_jsonp;
					}else{
					  $field = "`" . self::escape($clause[0]) . "`";
					}
					$compare = self::escape($clause[1]);
					$value = $clause[2];
					if(is_string($value)){
						$value = "'" . self::escape($clause[2]) . "'";
					}else if(is_null($value)){
						$_compare = "IS";
						if($compare == "!="){
						  $_compare .= " NOT";
						}
						$value = "NULL";
						$compare = $_compare;
				    }
					$where_result .= "$field $compare $value ";
				}else if(is_string($clause)){
					$linker = self::escape($clause);
					$where_result .= strtoupper($linker) . " ";
				}
    		}
    		$where_result = trim($where_result);
		}
		$args['where'] = $where_result;
	}
	/**
	 * РћР±СЂР°Р±РѕС‚РєР° РЅР°Р±РѕСЂРѕРІ РґР°РЅРЅС‹С…
	 * @method parse_dataset
	 * @param array $args РђСЂРіСѓРјРµРЅС‚С‹ С„СѓРЅРєС†РёР№ select(), insert(), delete(), update()
	 * @return array
	 */
	private static function parse_dataset(array &$args){
	    if(array_key_exists('data', $args) && is_array($args['data'])){
            $data = $args['data'];
            foreach($data as $key=>$value){
                unset($data[$key]);
                $key = self::escape($key);
                if(is_object($value) || is_array($value)){
                    $value = json_encode($value);
                }
                $value = self::escape($value);
                $data[$key] = $value;
            }
            $args['data'] = $data;
	    }
	}
	/**
	 * РћР±СЂР°Р±РѕС‚РєР° Р°СЂРіСѓРјРµРЅС‚РѕРІ
	 * @method parse_args
	 * @param array $defaults РђСЂРіСѓРјРµРЅС‚С‹ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ
	 * @param array $args РђСЂРіСѓРјРµРЅС‚С‹ С„СѓРЅРєС†РёР№ select(), insert(), delete(), update()
	 * @return array
	 */
	private static function parse_args(array $defaults, array &$args){
	    $args = parse_args($defaults, $args);
	    self::parse_where_clauses($args);
	    self::parse_dataset($args);
	}
    /**
     * Р’С‹Р±РѕСЂРєР° РёР· Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @method select
     * @param array $args
     * @return boolean|array
     */
    public static function select(array $args){
        self::parse_args([
            'field' => false, // РїРѕР»Рµ РґР»СЏ РІС‹Р±РѕСЂРєРё
            'table' => false, // С‚Р°Р±Р»РёС†Р°
            'where' => false, // С„РёР»СЊС‚СЂС‹
            'orderby' => false, // РїРѕР»Рµ РґР»СЏ СѓРїРѕСЂСЏРґРѕС‡РёРІР°РЅРёСЏ
            'max_results' => false, // РњР°РєСЃ. С‡РёСЃР»Рѕ СЂРµР·СѓР»СЊС‚Р°С‚РѕРІ
            'offset' => false, //РЎРґРІРёРі
        ], $args);
        $query = "SELECT ";
        //РџСЂРѕРІРµСЂРёРј, РїРµСЂРµРґР°Р»Рё Р»Рё РїРѕР»Рµ РґР»СЏ РІС‹Р±РѕСЂРєРё?
        if($args['field']){
            //Р•СЃР»Рё РґР°, С‚Рѕ РїСЂРѕРІРµСЂСЏРµРј, РѕРґРЅРѕ РёР»Рё РЅРµСЃРєРѕР»СЊРєРѕ РїРѕР»РµР№ РїРµСЂРµРґР°Р»Рё Рё РґРѕР±Р°РІР»СЏРµРј РёС… РІ Р·Р°РїСЂРѕСЃ
            if(is_array($args['field'])){
                $query .= '`' . implode('`,`',$args['field']) . '`';
            }else{
                $query .= "`" . $args['field'] . "`";
            }
        }else{
            //Р•СЃР»Рё РЅРµС‚, РіРѕРІРѕСЂРёРј СЃРµСЂРІРµСЂСѓ Р‘Р” РІС‹Р±СЂР°С‚СЊ РІСЃРµ РїРѕР»СЏ
            $query .= " * ";
        }
        if($args['table']){
            $query .= " FROM ";
            //РџСЂРѕРІРµСЂСЏРµРј, РёР· РѕРґРЅРѕР№ РёР»Рё РЅРµСЃРєРѕР»СЊРєРёС… С‚Р°Р±Р»РёС† РІС‹Р±РёСЂР°С‚СЊ Рё РґРѕР±Р°РІР»СЏРµРј СЌС‚Сѓ РёРЅС„РѕСЂРјР°С†РёСЋ РІ Р·Р°РїСЂРѕСЃ
            if(is_array($args['table'])){
                $query .= '`' . implode('`,`',$args['table']) . '`';
            }else{
                $query .= "`" . $args['table'] . "`";
            }
        }else{
            Events::run(__FUNCTION__ . '_error', 1, 'РќРµС‚ С‚Р°Р±Р»РёС†С‹');
            return false; //РќРµС‚ С‚Р°Р±Р»РёС†С‹, РЅРµ Р·РЅР°РµРј, РѕС‚РєСѓРґР° РІС‹Р±РёСЂР°С‚СЊ РЅР°РґРѕ
        }

        //РџСЂРѕРІРµСЂСЏРµРј, РїРµСЂРµРґР°Р»Рё Р»Рё С„РёР»СЊС‚СЂС‹?
        if($args['where'])
			$query .= " " . $args['where'];
        //РџСЂРѕРІРµСЂСЏРµРј, РїРµСЂРµРґР°РЅРѕ Р»Рё РїРѕР»Рµ РіСЂСѓРїРїРёСЂРѕРІРєРё
        if($args['orderby'] && is_array($args['orderby'])){
            $query .= ' ORDER BY `' . $args['orderby'][0] . '` ' . strtoupper($args['orderby'][1]);
        }
        //РџСЂРѕРІРµСЂСЏРµРј, РѕРіСЂР°РЅРёС‡РµРЅС‹ Р»Рё СЂРµР·СѓР»СЊС‚Р°С‚С‹ Р·Р°РїСЂРѕСЃР°
        if($args['max_results']){
            $query .= ' LIMIT ' . $args['max_results'];
        }
        //РџСЂРѕРІРµСЂСЏРµРј, РїРµСЂРµРґР°РЅ Р»Рё СЃРґРІРёРі
        if($args['offset']){
            $query .= ' OFFSET ' . $args['offset'];
        }
        //Р”РµР»Р°РµРј Р·Р°РїСЂРѕСЃ
        if(is_string($args['table'])){
            $result = self::query($query, $args['table']);
        }else{
            $result = self::query($query);
        }
        return $result;
    }
    /**
     * Р’С‹Р±РѕСЂРєР° СЃС‚СЂРѕРєРё РёР· Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @method select_row
     * @param array $args
     * @return boolean|array
     */
    public static function select_row(array $args){
        $result = self::select($args);
        return $result ? $result[0] : $result;
    }
    /**
     * Р’С‹Р±РѕСЂРєР° СЃС‚СЂРѕРєРё РїРѕ ID РёР· Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @method select_row_by_id
     * @param array $args
     * @return boolean|array
     */
    public static function select_row_by_id(array $args){
        $args = parse_args([
            'table' => false, //РўР°Р±Р»РёС†Р°
            'id' => false, //id
        ], $args);
        $_args = [
            'table' => $args['table'],
            'where' => [
                ['id', '=', $args['id']],
            ]
        ];
        return self::select_row($_args);
    }
    /**
     * Р’СЃС‚Р°РІРєР° СЃС‚СЂРѕРєРё РІ Р±Р°Р·Сѓ РґР°РЅРЅС‹С…
     * @method insert
     * @param array $args
     * @return boolean|number
     */
    public static function insert(array $args){
        self::parse_args([
            'table' => false, //С‚Р°Р±Р»РёС†Р°
            'data' => false, //РґР°РЅРЅС‹Рµ (РїРѕР»Рµ => Р·РЅР°С‡РµРЅРёРµ)
        ], $args);
        if(!$args['table'] || !$args['data']){
            Events::run(__FUNCTION__ . '_error', 1, 'РќРµС‚ С‚Р°Р±Р»РёС†С‹ РёР»Рё РґР°РЅРЅС‹С…');
            return false;
        }
        //РќР°С‡РёРЅР°РµРј СЃРѕР·РґР°РІР°С‚СЊ Р·Р°РїСЂРѕСЃ
        $query = "INSERT INTO `". $args['table'] ."` (`";
        //Р‘РµСЂРµРј РєР»СЋС‡Рё РёР· РїСЂРёС€РµРґС€РµРіРѕ РјР°СЃСЃРёРІР°
        $query .= implode("`,`",array_keys($args['data']));
        $query .= "`) VALUES ('";
        //Р‘РµСЂРµРј Р·РЅР°С‡РµРЅРёСЏ РёР· РїСЂРёС€РµРґС€РµРіРѕ РјР°СЃСЃРёРІР°
        $query .= implode("','",$args['data']);
        $query .= "')";
        //Р”РµР»Р°РµРј Р·Р°РїСЂРѕСЃ
        $result = self::query($query);
        return $result === true ? self::get_insert_id() : false;
    }
    /**
     * РћР±РЅРѕРІР»РµРЅРёРµ РІ Р±Р°Р·Рµ РґР°РЅРЅС‹С…
     * @method update
     * @param array $args
     * @return boolean
     */
    public static function update(array $args){
        self::parse_args([
            'field' => false, // РїРѕР»Рµ РґР»СЏ РІС‹Р±РѕСЂРєРё
            'table' => false, // С‚Р°Р±Р»РёС†Р°
			'where' => false, // С„РёР»СЊС‚СЂС‹
            'data'  => false, //РґР°РЅРЅС‹Рµ (РїРѕР»Рµ => Р·РЅР°С‡РµРЅРёРµ)
        ], $args);
        if(!$args['table'] || !$args['data']){
            Events::run(__FUNCTION__ . '_error', 1, 'РќРµС‚ С‚Р°Р±Р»РёС†С‹ РёР»Рё РґР°РЅРЅС‹С…');
            return false;
        }
        //РќР°С‡РёРЅР°РµРј СЃРѕР·РґР°РІР°С‚СЊ Р·Р°РїСЂРѕСЃ
        $query = "UPDATE `". $args['table'] ."` SET ";
        //Р”РѕР±Р°РІР»СЏРµРј РІ Р·Р°РїСЂРѕСЃ РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ
        $new_data = [];
        foreach($args['data'] as $field=>$value){
            $new_data[] = "`$field` = '$value'";
        }
        $query .= implode(',',$new_data);
        //РџСЂРѕРІРµСЂСЏРµРј, РїРµСЂРµРґР°Р»Рё Р»Рё С„РёР»СЊС‚СЂС‹?
		if($args['where'])
			$query .= " " . $args['where'];
        //Р”РµР»Р°РµРј Р·Р°РїСЂРѕСЃ
        $result = self::query($query);
        return (bool) $result;
    }
    /**
     * РЈРґР°Р»РµРЅРёРµ РёР· Р±Р°Р·С‹ РґР°РЅРЅС‹С…
     * @method remove
     * @param array $args
     * @return boolean
     */
    public static function remove(array $args){
        self::parse_args([
            'table' => false, // С‚Р°Р±Р»РёС†Р°
            'where' => false, // С„РёР»СЊС‚СЂС‹
        ], $args);
        if(!$args['table'] || !strlen($args['where'])){
            Events::run(__FUNCTION__ . '_error', 1, 'РќРµС‚ С‚Р°Р±Р»РёС†С‹ РёР»Рё СѓСЃР»РѕРІРёР№');
            return false;
        }
        //РќР°С‡РёРЅР°РµРј СЃРѕР·РґР°РІР°С‚СЊ Р·Р°РїСЂРѕСЃ
        $query = "DELETE FROM `".$args['table']."` " . $args['where'];
        //Р”РµР»Р°РµРј Р·Р°РїСЂРѕСЃ
        $result = self::query($query);
        //Р•СЃР»Рё Р·Р°РїСЂРѕСЃ СѓСЃРїРµС€РµРЅ, РІРѕР·РІСЂР°С‰Р°РµРј true
        return (bool) $result;
    }
    /**
     * Р’С‹РїРѕР»РЅРµРЅРёРµ SQL-Р·Р°РїСЂРѕСЃР°
     * @method query
     * @param string $query SQL-Р·Р°РїСЂРѕСЃ
     * @param string $table С‚Р°Р±Р»РёС†Р° Р·Р°РїСЂРѕСЃР°
     * @return boolean|array
     */
    public static function query(string $query, string $table = null){
        //Р”РµР»Р°РµРј Р·Р°РїСЂРѕСЃ
        $result = self::$conn->query($query);
        self::$last_query = $query;
        //Р•СЃР»Рё СЂРµР·СѓР»СЊС‚Р°С‚ РЅРµ mysqli_result - РѕС‚РґР°РµРј РµРіРѕ
        if(!($result instanceof mysqli_result))
            return $result;
        else{
            $output = [];
            if($table){
                $table = ucfirst(trim($table));
                $table = substr($table, 0, -1);
                while(!class_exists($table) && strlen($table)){
                    $table = substr($table, 0, -1);
                }
                $class = strlen($table) ? $table : 'stdClass';
            }else{
                $class = "stdClass";
            }
            while($row = json_decode_object($result->fetch_object($class))){
                $output[] = $row;
            }
            return $output;
        }
    }
    /**
     * Р—Р°РєСЂС‹С‚РёРµ СЃРѕРµРґРёРЅРµРЅРёСЏ
     * @method deconnect
     * @return boolean
     */
    public static function deconnect(){
        self::$conn->close();
        self::$last_query = '';
        Events::run(__FUNCTION__ . "_success");
        return true;
    }
}
?>
