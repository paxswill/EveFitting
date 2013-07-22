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
 *           supported options are 'array' and 'sql'.
 *           Default: 'array'
 *
 * $wgEveFittingDatabaseDSN
 *         - If using the 'sql' typeID mapper, specify the DSN of the database
 *           to connect to here.
 *           Default: ''
 *
 * $wgEveFittingDatabaseUsername
 *         - The username to use to connect to the database if using the 'sql'
 *           mapper.
 *           Default: ''
 *
 * $wgEveFittingDatabasePassword
 *         - The password to connect to the database if using the 'sql' mapper.
 *           Default: ''
 *
 * $wgEveFittingDatabaseOptions
 *         - Connection options to use if using the 'sql' mapper.
 *           Default: ''
 */
$wgEveFittingTypeIDMapper = 'array';
$wgEveFittingDatabaseDSN = '';
$wgEveFittingDatabaseUsername = '';
$wgEveFittingDatabasePassword = '';
$wgEveFittingDatabaseOptions = '';

// Set which typeID mapper to use
if ( $wgEveFittingTypeIDMapper == 'array' ) {
	$wgHooks['ParserFirstCallInit'][] =
		'EveFittingMapIDArray::EveFittingRegisterParser';
} elseif ( $wgEveFittingTypeIDMapper == 'sql' ) {
	$wgHooks['ParserFirstCallInit'][] =
		'EveFittingMapSQL::EveFittingRegisterParser';
} else {
	// TODO Alert to the invalid config value
}

// Internationalization and magic words
$wgExtensionMessagesFiles['EveFitting'] =
	dirname(__FILE__) . '/EveFitting.i18n.php';
