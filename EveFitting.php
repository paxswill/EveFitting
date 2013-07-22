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

// Point to the other files

$wgEveFittingIncludes = dirname(__FILE__) . '/includes';
$wgAutoloadClasses['EveFittingEFTParser'] =
	$wgEveFittingIncludes . '/EveFittingEFTParser.php';
$wgAutoloadClasses['EveFittingMapIDArray'] =
	$wgEveFittingIncludes . '/EveFittingMapIDArray.php';

/*
 * Configuration variables
 *
 * $wgEveFittingTypeIDMapper
 *         - choose which cmethod to use to map names to typeIDs. Currently
 *           only 'array' is supported.
 *           Default: 'array'
 */
$wgEveFittingTypeIDMapper = 'array';

// Set which typeID mapper to use
if ( $wgEveFittingTypeIDMapper == 'array' ) {
	$wgHooks['ParserFirstCallInit'][] =
		'EveFittingMapIDArray::EveFittingRegisterParser';
} else {
	// TODO Alert to the invalid config value
}

// Internationalization and magic words
$wgExtensionMessagesFiles['EveFitting'] =
	dirname(__FILE__) . '/EveFitting.i18n.php';
