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

class EveFittingMapSQL extends EveFittingEFTParser {

	public static function EveFittingMapTypeID( $name ) {
		global $wgEveFittingDatabaseDSN;
		global $wgEveFittingDatabaseUsername;
		global $wgEveFittingDatabasePassword;
		global $wgEveFittingDatabaseOptions;

		// Connect to the DB
		try {
			$dbConnection = new PDO( $wgEveFittingDatabaseDSN,
			                         $wgEveFittingDatabaseUsername,
			                         $wgEveFittingDatabasePassword,
			                         $wgEveFittingDatabaseOptions );
		} catch ( PDOException $exc ) {
			wfDebug( "DB connection failed: " . $exc->getMessage() );
		}

		// Create the prepared statment (no SQLi here)
		$stmt = $dbConnection->prepare(
			"SELECT typeID FROM invTypes WHERE typeName = :name LIMIT 1" );
		// Bind parameters and execute
		$stmt->bindParam( ':name', $name );
		$stmt->execute();
		// Get results
		$typeID = $stmt->fetch( PDO::FETCH_NUM );
		if ( $typeID !== FALSE ) {
			$typeID = intval( $typeID[0] );
		} else {
			$typeID = -1;
		}
		// Close out the cursor to let it be used again
		$stmt->closeCursor();
		return $typeID;
	}

}
