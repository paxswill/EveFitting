<?php

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
