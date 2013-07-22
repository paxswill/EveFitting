<?php

class EveFittingEFTParser {

	public static function EveFittingRender( $parser, $eftFit ) {
		/*
		 *
		 * $eftFit is the raw input output of the copy to pasteboard function
		 * from EFT. The format is:
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

		* Text enclosed in braces is variable text. Unused slots/rigs are
		* specified by special tokens. There are two lines between the last
		* high slot (or subsystem on T3 cruisers) and the start of the drones
		* section.
		*/

		// Normalize \r\n to \n
		$nixBreaks = str_replace( "\r\n", "\n", $eftFit );

		// Discard any manual line breaks already added
		$nobrs = str_replace( array( "<br />\n", "<br />" ), array( "\n" ),
			$nixBreaks );

		// split the fit into seperate lines
		$lines = explode( "\n", $nobrs );

		// The first line is the ship type and fitting name in a special format
		$firstLine = array_shift( $lines );
		// Trim the brackets off
		$trimmed = substr( $firstLine, 1, -1 );
		// Extract the ship name and the fit name
		list( $shipName, $fitName ) = explode( ", ", $trimmed );
		$shipID = static::EveFittingMapTypeID( $shipName );

		// Parse the items
		$sections = array();
		$charges = array();
		// Prime the section array
		$current = array();
		foreach ( $lines as $line ) {
			// If on a blank line, it's a divider between sections
			if ( $line == "" ) {
				// Add a blank new section
				$sections[] = $current;
				$current = array();
				continue;
			}
			// Split items from charges
			$exploded = explode( ", ", $line );
			// Drones count as a charge, but are signified by their quantity
			// being in the form 'Drone_Name x#'
			if ( preg_match( "/(.+) x([\d+])$/",
					$exploded[0], $matches ) == 1 ) {
				// Drone
				$droneID = static::EveFittingMapTypeID( $matches[1] );
				$quantity = intval( $matches[2] );
				// Add drones to the charges array
				for ( $i = 0; $i < $quantity; $i++ ) {
					$charges[] = $droneID;
				}
			} else {
				// Get the itemID for the item name and save it for later
				$itemID = static::EveFittingMapTypeID( $exploded[0] );
				if ( $itemID >= 0 ) {
					$current[] = $itemID;
				}
				// Save the charge for the end
				if ( count( $exploded ) > 1 ) {
					$chargeID = static::EveFittingMapTypeID( $exploded[1] );
					if ( $chargeID >= 0 ) {
						$charges[] = $chargeID;
					}
				}
			}
		}
		$sections[] = $current;

		// Recombine the lines, using <br /> as a line break instead of \n
		$fixedEFT = implode( '<br />', $lines );

		// If the ship type is invalid, making the DNA is unecessary
		if ( $shipID < 0 ) {
			return $firstLine . "<br />" . $fixedEFT;
		}

		$dna = $shipID;
		// T3 cruisers have subsystems, which will show up as an extra section
		// The numbers are the typeIDs for the four T3 cruisers.
		if ( $shipID == 29984 ||
			 $shipID == 29986 ||
			 $shipID == 29988 ||
			 $shipID == 29990 ) {
			$subsystems = array_pop( $sections );
			foreach ( $subsystems as $subsystem ) {
				// The documentation on the EveO wiki is wrong, you need a
				// quantity of 1 on subsystems
				$dna = $dna . ":" . $subsystem . ";1";
			}
			// There must always be 5 subsystems
			for ( $i = count( $subsystems ); $i < 5; $i++ ){
				$dna = $dna . ":;";
			}
		}

		// Rigs have to go last, save them for later
		// FIXME The rig stuff is bugged. It works for now, but could
		// (probably) break later.
		$rigs = array_pop($sections);

		// Consolidate and count modules
		$allSections = array();
		// TODO this is ineffcient as hell
		foreach ( $sections as $section ) {
			foreach ( $section as $item ) {
				$allSections[] = $item;
			}
		}
		$quantities = array_count_values( $allSections );
		foreach ( $quantities as $item => $quantity ) {
			$dna = $dna . ":" . $item . ";" . $quantity;
		}

		// Count and add rigs
		if ( count( $rigs ) == 0 ) {
			// Even if there are no rigs, it has to have a colon
			$dna = $dna . ":";
		} else {
			$rigQuantities = array_count_values( $rigs );
			foreach ( $rigQuantities as $rig => $quantity ) {
				$dna = $dna . ":" . $rig . ";" . $quantity;
			}
		}

		// Handle charges (ammo+drones)
		if ( count( $charges ) == 0 ) {
			// Even if there're no charges, add an empty entry
			$dna = $dna . ":";
		} else {
			$chargeQuantities = array_count_values( $charges );
			foreach ( $chargeQuantities as $charge => $quantity ) {
				$dna = $dna . ":" . $charge . ";" . $quantity;
			}
		}

		// Make the anchor (link) tag that'll open the fitting window
		$dnaAnchor =
			"<a href=\"javascript:CCPEVE.showFitting('" . $dna . "')\">";

		// Return the EFT block with the first line being a link to open the
		// fitting in game.
		$eftFitLink = $dnaAnchor . $firstLine . "</a><br />" . $fixedEFT;

		// MediaWiki magic. tells Mediawiki that I'm returning HTML, and not to
		// mess with it.
		return array( $eftFitLink, 'isHTML' => true );
	}

	public static function EveFittingMapTypeID( $name ) {
		// A dummy function, meant to be overloaded
		return -1;
	}
}
