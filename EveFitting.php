<?php

// Metadata about the extension
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Eve Fitting Block',
	'description' =>
		'Adds a parser function for Eve Fitting Tool (EFT) blocks',
	'version' => '0.0.2',
	'author' => 'Will Ross',
	'url' => 'https://github.com/paxswill/EveFitting',
);

$wgHooks['ParserFirstCallInit'][] = 'wfEveFitting';

// Point to the other files
// Internationalization and magic words
$wgEveFittingDir = dirname(__FILE__);
$wgExtensionMessagesFiles['EveFitting'] =
	$wgEveFittingDir . '/EveFitting.i18n.php';
// Autoload classes
$wgAutoloadClasses['EveFittingEFTParser'] =
	$wgEveFittingDir . '/EveFittingEFTParser.php';
$wgAutoloadClasses['EveFittingMapIDArray'] =
	$wgEveFittingDir . '/EveFittingMapIDArray.php';
$wgAutoloadClasses['EveFittingMapSQL'] =
	$wgEveFittingDir . '/EveFittingMapSQL.php';

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
