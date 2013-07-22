<?php
/**
 * @section LICENSE
 * Copyright (c) 2013, Will Ross
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *
 *     Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @file
 */

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
$wgAutoloadClasses['EveFittingMapArray'] =
	$wgEveFittingDir . '/EveFittingMapArray.php';
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
			'EveFittingMapArray::EveFittingRender' );
	} elseif ( $wgEveFittingTypeIDMapper == 'sql' ) {
		$parser->setFunctionHook( 'EFT',
			'EveFittingMapSQL::EveFittingRender' );
	} else {
		// TODO Alert to the invalid config value
	}
	return true;
}
