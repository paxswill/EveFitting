<?php

// Metadata about the extension
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Eve Fitting Parsing Function',
	'description' => 'Adds a parser function under #EFT for formatting Eve Fitting Tool style blocks',
	'version' => '0.0.2',
	'author' => 'Will Ross',
	'url' => 'https://github.com/paxswill/EveFitting',
);

$wgHooks['ParserFirstCallInit'][] = 'wfEveFitting';

// Point to the other files
// Internationalization and magic words
$wgExtensionMessagesFiles['EveFitting'] =
	dirname(__FILE__) . '/EveFitting.i18n.php';
$wgEveFittingIncludes = dirname(__FILE__) . '/includes';
// Autoload classes
$wgAutoloadClasses['EveFittingEFTParser'] =
	$wgEveFittingIncludes . '/EveFittingEFTParser.php';
$wgAutoloadClasses['EveFittingMapIDArray'] =
	$wgEveFittingIncludes . '/EveFittingMapIDArray.php';
$wgAutoloadClasses['EveFittingMapSQL'] =
	$wgEveFittingIncludes . '/EveFittingMapSQL.php';

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
 *         - Connection options to use if using the 'sql' mapper. Enabling
 *           persistent connections is highly recommended.
 *           Default: 'array( 'PDO::ATTR_PERSISTENT' => true )
 */
$wgEveFittingTypeIDMapper = 'array';
$wgEveFittingDatabaseDSN = '';
$wgEveFittingDatabaseUsername = '';
$wgEveFittingDatabasePassword = '';
$wgEveFittingDatabaseOptions = array ( 'PDO::ATTR_PERSISTENT' => true );

function wfEveFitting( &$parser ) {
	global $wgEveFittingTypeIDMapper;

	// Set which typeID mapper to use
	if ( $wgEveFittingTypeIDMapper == 'array' ) {
		$parser->setFunctionHook( 'EFT',
			'EveFittingMapIDArray::EveFittingRender' );
	} elseif ( $wgEveFittingTypeIDMapper == 'sql' ) {
		wfDebug( "EFT: Using SQL mapper" );
		$parser->setFunctionHook( 'EFT',
			'EveFittingMapSQL::EveFittingRender' );
	} else {
		// TODO Alert to the invalid config value
	}
	return true;
}
