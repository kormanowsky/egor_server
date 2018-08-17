<?php 
/**
 * Events - РјРµС…Р°РЅРёР·Рј СЃРѕР±С‹С‚РёР№
 * @author M.Kormanowsky
 * @uses Storage
 * @version 1.0 (11.04.2018)
 */
class Events{
    /**
     * Р­РєР·РµРјРїР»СЏСЂ С…СЂР°РЅРёР»РёС‰Р°. РўСѓС‚ Р±СѓРґСѓС‚ С…СЂР°РЅРёС‚СЊСЃСЏ РѕР±СЂР°Р±РѕС‚С‡РёРєРё
     * @var Storage
     */
    public static $storage;
    /**
     * РџРѕРґРєР»СЋС‡РµРЅРёРµ РѕР±СЂР°Р±РѕС‚С‡РёРєР° Рє СЃРѕР±С‹С‚РёСЋ
     * @param string $event
     * @param callable $handler
     * @param number $priority
     * @return number
	 * @method on
     */
    public static function on($event, $handler, $priority = 1){
        return Events::$storage->add($event, [
            'priority' => $priority, 
            'handler' => $handler
        ]);
    }
    /**
     * Р—Р°РїСѓСЃРє СЃРѕР±С‹С‚РёСЏ
     * @param string $event
     * @param mixed ...$args
     * @return boolean
	 * @method run
     */
    public static function run($event, ...$args){
        foreach (Events::$storage->get($event, ["priority", "DESC"]) as $event_handler){
            call_user_func_array($event_handler['handler'], $args);
        }
    }
    /**
     * РћС‚РєР»СЋС‡РµРЅРёРµ РѕР±СЂР°Р±РѕС‚С‡РёРєР°
     * @param string $event
     * @param number $handler_id
     * @return boolean
	 * @method off
     */
    public static function off($event, $handler_id){
        return Events::$storage->remove($event, $handler_id);
    }
}
//РћР±СЉРµРєС‚ Storage
Events::$storage = new Storage;
