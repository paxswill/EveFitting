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

// Set up translation
$wgExtensionMessagesFiles['EveFitting'] = dirname(__FILE__) . 'EveFitting.i18n.php';

// Setup the parser extension
function pxEveFittingSetup( &$parser) {
	// Add the parsing hook in
	$parser->setFunctionHook( 'EFT', 'pxEveFittingRender' );
	// Assume that worked
	return true;
}

function pxEveFittingRender( $parser, $eftFit ) {
	/*
	 *
	 * $eftFit is the raw input output of the copy to pasteboard function from 
	 * EFT. The format is:
	[{ship_type}, {fit_name}]
	
	{low_slots}
	...
	[Empty Low slot]
	
	{med_slots}
	...
	[Empty Med slot]
	
	{high_slots}
	...
	[Empty High slot]
	
	{rigs}
	...
	[Empty Rig slot]
	
	{subsystems}
	...
	[Empty Subsystem slot]
	
	
	{drones}

	* Text enclosed in braces is variable text. Unused slots/rigs are specified 
	* by special tokens. There are two lines between the last high slot (or 
	* subsystem on T3 cruisers) and the start of the drones section.
	*/

	return ''
}

function pxEveFittingMapTypeID( $name ) {
	// Maps an exact string to an Eve typeID
	return -1;
}
