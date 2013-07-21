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
$wgHooks['ParserFirstCallInit'][] = 'pxEveFittingSetup';

// Point to the other files
$wgExtensionMessagesFiles['EveFitting'] = dirname(__FILE__) . 'EveFitting.i18n.php';

$wgEveFittingIncludes = dirname(__FILE__) . '/includes';
$wgAutoloadClasses['EveFittingEFTParser'] = $wgEveFittingIncludes . 'EveFittingEFTParser.php';
$wgAutoloadClasses['EveFittingMapIDArray'] = $wgEveFittingIncludes . 'EveFittingMapIDArray.php';

// Setup the parser extension
function pxEveFittingSetup( &$parser) {
	// Add the parsing hook in
	$parser->setFunctionHook( 'EFT', 'EveFittingEFTParser::EveFittingRender' );
	// Assume that worked
	return true;
}
