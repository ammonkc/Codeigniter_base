<?php
/**
 * Flare is a very simple, elegant yet powerful
 * Object Relational Mapper for CodeIgniter applications.
 *
 * @package Flare
 * @license Creative Commons Attribution License http://creativecommons.org/licenses/by/2.0/uk/
 * @link http://bitbucket.org/jamierumbelow/flare
 * @version 1.0.0
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @copyright Copyright (c) 2009, Jamie Rumbelow <http://jamierumbelow.net>
*/

/**
 * Adds support for get_called_class() in PHP5.2. It
 * allows us to get the name of the current class in a
 * static context, allowing us do the clever table guessing.
 *
 * It's introduced in the core in PHP >5.3, so check that
 * it's not defined before we try to define it. It's also a
 * nasty hack, so if we can we want to use the native one. 
 *
 * @package flare
 * @author 
 */
if(!function_exists('get_called_class')) { 
function get_called_class($bt = false,$l = 1) { 
    if (!$bt) $bt = debug_backtrace(); 
    if (!isset($bt[$l])) throw new Exception("Cannot find called class -> stack level too deep."); 
    if (!isset($bt[$l]['type'])) { 
        throw new Exception ('type not set'); 
    } 
    else switch ($bt[$l]['type']) { 
        case '::': 
            $lines = file($bt[$l]['file']); 
            $i = 0; 
            $callerLine = ''; 
            do { 
                $i++; 
                $callerLine = $lines[$bt[$l]['line']-$i] . $callerLine; 
            } while (stripos($callerLine,$bt[$l]['function']) === false); 
            preg_match('/([a-zA-Z0-9\_]+)::'.$bt[$l]['function'].'/', 
                        $callerLine, 
                        $matches); 
            if (!isset($matches[1])) { 
                // must be an edge case. 
                throw new Exception ("Could not find caller class: originating method call is obscured."); 
            } 
            switch ($matches[1]) { 
                case 'self': 
                case 'parent': 
                    return get_called_class($bt,$l+1); 
                default: 
                    return $matches[1]; 
            } 
            // won't get here. 
        case '->': switch ($bt[$l]['function']) { 
                case '__get': 
                    // edge case -> get class of calling object 
                    if (!is_object($bt[$l]['object'])) throw new Exception ("Edge case fail. __get called on non object."); 
                    return get_class($bt[$l]['object']); 
                default: return $bt[$l]['class']; 
            } 

        default: throw new Exception ("Unknown backtrace method type"); 
    } 
} 
}

/**
 * Pluralise a word, pretty damn accurately.
 *
 * @param string $word The word to pluralise
 * @return string
 * @author Jamie Rumbelow
 */
function pluralize($word) {
	$plural = array(
		'/(quiz)$/i' => '\1zes',
		'/^(ox)$/i' => '\1en',
		'/([m|l])ouse$/i' => '\1ice',
		'/(matr|vert|ind)ix|ex$/i' => '\1ices',
		'/(x|ch|ss|sh)$/i' => '\1es',
		'/([^aeiouy]|qu)ies$/i' => '\1y',
		'/([^aeiouy]|qu)y$/i' => '\1ies',
		'/(hive)$/i' => '\1s',
		'/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
		'/sis$/i' => 'ses',
		'/([ti])um$/i' => '\1a',
		'/(buffal|tomat)o$/i' => '\1oes',
		'/(bu)s$/i' => '\1ses',
		'/(alias|status)/i'=> '\1es',
		'/(octop|vir)us$/i'=> '\1i',
		'/(ax|test)is$/i'=> '\1es',
		'/s$/i'=> 's',
		'/$/'=> 's');

	$uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep', 'water');

	$irregular = array(
		'person' => 'people',
		'man' => 'men',
		'child' => 'children',
		'sex' => 'sexes',
		'move' => 'moves');

	$lowercased_word = strtolower($word);

	foreach ($uncountable as $_uncountable){
		if(substr($lowercased_word,(-1*strlen($_uncountable))) == $_uncountable){
			return $word;
		}
	}

	foreach ($irregular as $_plural=> $_singular){
		if (preg_match('/('.$_plural.')$/i', $word, $arr)) {
			return preg_replace('/('.$_plural.')$/i', substr($arr[0],0,1).substr($_singular,1), $word);
		}
	}

	foreach ($plural as $rule => $replacement) {
		if (preg_match($rule, $word)) {
			return preg_replace($rule, $replacement, $word);
		}
	}
	return false;
}

/**
 * Singularise a word - do the opposite of
 * pluralising.
 *
 * @param string $word The word to singularise
 * @return string
 * @author Jamie Rumbelow
 */
function singularize($word) {
	$singular = array(
		'/(quiz)zes$/i' => '\\1',
		'/(matr)ices$/i' => '\\1ix',
		'/(vert|ind)ices$/i' => '\\1ex',
		'/^(ox)en/i' => '\\1',
		'/(alias|status)es$/i' => '\\1',
		'/([octop|vir])i$/i' => '\\1us',
		'/(cris|ax|test)es$/i' => '\\1is',
		'/(shoe)s$/i' => '\\1',
		'/(o)es$/i' => '\\1',
		'/(bus)es$/i' => '\\1',
		'/([m|l])ice$/i' => '\\1ouse',
		'/(x|ch|ss|sh)es$/i' => '\\1',
		'/(m)ovies$/i' => '\\1ovie',
		'/(s)eries$/i' => '\\1eries',
		'/([^aeiouy]|qu)ies$/i' => '\\1y',
		'/([lr])ves$/i' => '\\1f',
		'/(tive)s$/i' => '\\1',
		'/(hive)s$/i' => '\\1',
		'/([^f])ves$/i' => '\\1fe',
		'/(^analy)ses$/i' => '\\1sis',
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\\1\\2sis',
		'/([ti])a$/i' => '\\1um',
		'/(n)ews$/i' => '\\1ews',
		'/s$/i' => '',
		);

	$uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep');

	$irregular = array(
		'person' => 'people',
		'man' => 'men',
		'child' => 'children',
		'sex' => 'sexes',
		'move' => 'moves');

	$lowercased_word = strtolower($word);
	foreach ($uncountable as $_uncountable){
		if(substr($lowercased_word,(-1*strlen($_uncountable))) == $_uncountable){
			return $word;
		}
	}

	foreach ($irregular as $_plural=> $_singular){
		if (preg_match('/('.$_singular.')$/i', $word, $arr)) {
			return preg_replace('/('.$_singular.')$/i', substr($arr[0],0,1).substr($_plural,1), $word);
		}
	}

	foreach ($singular as $rule => $replacement) {
		if (preg_match($rule, $word)) {
			return preg_replace($rule, $replacement, $word);
		}
	}

	return $word;
}