<?php

// Metadata about the extension
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Eve Fitting Parsing Function',
	'description' => 'Adds a parser function under #EFT for formatting Eve Fitting Tool style blocks',
	'version' => '0.0.1a',
	'author' => 'paxswill',
	'url' => 'http://paxswill.com',
);

// Set the function to call for initial setup
$wgHooks['ParserFirstCallInit'][] = 'EveFittingSetup';

// Point to the other files
$wgExtensionMessagesFiles['EveFitting'] =
	dirname(__FILE__) . '/EveFitting.i18n.php';

$wgEveFittingIncludes = dirname(__FILE__) . '/includes';
$wgAutoloadClasses['EveFittingEFTParser'] =
	$wgEveFittingIncludes . 'EveFittingEFTParser.php';
$wgAutoloadClasses['EveFittingMapIDArray'] =
	$wgEveFittingIncludes . 'EveFittingMapIDArray.php';

/*
 * Configuration variables
 *
 * $wgEveFittingTypeIDMapper
 *         - choose which cmethod to use to map names to typeIDs. Currently
 *           only 'array' is supported.
 *           Default: 'array'
 */
$wgEveFittingTypeIDMapper = 'array';

// Setup the parser extension
function EveFittingSetup( &$parser) {
	// Add the parsing hook in
	if ( $wgEveFittingTypeIDMapper == 'array' ) {
		$parser->setFunctionHook( 'EFT',
			'EveFittingMapIDArray::EveFittingRender' );
	} else {
		// TODO Alert to the invalid config value
	}
	return true;
}
