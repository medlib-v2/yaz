<?php

/**
 * BaseRecord.
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author 		Patrick LUZOLO <luzolo_p@medlib.fr>
 * @package 	Yaz\Config
 *
 */

namespace Yaz\Config;

class YazConfig {

	private static $ALLOW_RECORD_MODE = [
		'string',
		'xml',
		'raw',
		'syntax',
		'array'
		];
	    
	const
		TYPE_STRING   = 'string',
		TYPE_XML      = 'xml',
		TYPE_RAW      = 'raw',
		TYPE_SYNTAX   = 'syntax',
		TYPE_ARRAY    = 'array';
		
	public static function getByPosition($connexion, $position, $record_mode)
	{
		return yaz_record($connexion, $position, $record_mode);
	}
		
	public static function getAllowRecordMode()
	{
		return self::$ALLOW_RECORD_MODE;
	}
}
