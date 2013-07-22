<?php

class EveFittingMapSQL extends EveFittingEFTParser {

	public static function EveFittingRegisterParser( &$parser ) {
		$parser->setFunctionHook( 'EFT',
			'EveFittingMapSQL::EveFittingRender' );
		return true;
	}

	public static function EveFittingMapTypeID( $name ) {
		return -1;
	}

}
