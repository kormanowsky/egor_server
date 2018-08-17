<?php
/**
 * Storage - С…СЂР°РЅРёР»РёС‰Рµ
 * @author M.Kormanowsky
 * @version 1.0 (11.04.2018)
 */
class Storage{
    /**
     * РњР°СЃСЃРёРІ СЃ РґР°РЅРЅС‹РјРё С…СЂР°РЅРёР»РёС‰Р°
     * @var array
     */
    private $storage = [];
    /**
     * РЈСЃС‚Р°РЅРѕРІРєР° РёР·РЅР°С‡Р°Р»СЊРЅС‹С… РґР°РЅРЅС‹С… С…СЂР°РЅРёР»РёС‰Р°
     * @param array $data
     */
    public function __construct($data = []){
        $this->storage = $data;
    }
    /**
     * Р”РѕР±Р°РІР»РµРЅРёРµ РґР°РЅРЅС‹С… РІ С…СЂР°РЅРёР»РёС‰Рµ
     * @param string $key РљР»СЋС‡ РґР»СЏ РґРѕР±Р°РІР»РµРЅРёСЏ
     * @param array $data Р”Р°РЅРЅС‹Рµ РґР»СЏ РґРѕР±Р°РІР»РµРЅРёСЏ
     * @return number ID РґРѕР±Р°РІР»РµРЅРЅС‹С… РґР°РЅРЅС‹С…
	 * @method add
     */
    public function add($key, $data){
        if(!array_key_exists($key, $this->storage)){
            $this->storage[$key] = [];
        }
        $id = count($this->storage[$key]);
        $this->storage[$key][] = $data;
        return $id;
    }
    /**
     * РЈРґР°Р»РµРЅРёРµ РёР· С…СЂР°РЅРёР»РёС‰Р°
     * @param string $key РљР»СЋС‡ РґР»СЏ СѓРґР°Р»РµРЅРёСЏ
     * @param string $id ID РґР»СЏ СѓРґР°Р»РµРЅРёСЏ
	 * @method remove
     */
    public function remove($key, $id){
        unset($this->storage[$key][$id]);
    }
    /**
     * РџРѕР»СѓС‡РµРЅРёРµ РґР°РЅРЅС‹С… РїРѕ РєР»СЋС‡Сѓ СЃ СЃРѕСЂС‚РёСЂРѕРІРєРѕР№
     * @param string $key РљР»СЋС‡
     * @param boolean|array|callable $sort РљР°Рє СЃРѕСЂС‚РёСЂРѕРІР°С‚СЊ РґР°РЅРЅС‹Рµ
     * @return array|boolean
	 * @method get
     */
    public function get($key, $sort = false){
        if(!array_key_exists($key, $this->storage)){
            $this->storage[$key] = [];
        }
        $s = (array) $this->storage[$key];
		if(is_callable($sort)){
			$sort_fn = $sort;
		}else if(is_array($sort)){
			$sort_fn = function($a, $b) use ($sort){
                $modifier = $sort[1] == "ASC" ? 1 : -1;
                if($a[$sort[0]] <= $b[$sort[0]]){
                    $result = -1;

                }else{
                    $result = 1;
                }
                return $result * $modifier;
            };
		}else{
			return false;
		}
        usort($s, $sort_fn);
        return $s;
    }
	/**
	 * РџСЂРѕРІРµСЂРєР° РЅР°Р»РёС‡РёСЏ РєР»СЋС‡Р° $key РІ С…СЂР°РЅРёР»РёС‰Рµ 
	 * @param string $key РљР»СЋС‡ РґР»СЏ РїСЂРѕРІРµСЂРєРё
	 * @return boolean
	 * @method has_key 
	 */
    public function has_key($key){
      return array_key_exists($key, $this->storage);
    }
}
