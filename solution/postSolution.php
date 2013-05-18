<?php
	/*
	* This script handles postSolution requests. Requires user authentication.
	* Returns the ID of the newly-created Solution.
	*/

	// load dependencies
	require('../util/requestParams.php');
	require('../util/db_tables.php');	
	require('../util/queryTools.php');
       require('../util/security.php');

       // require secure connection
       secureConnection();
	
	// parse JSON payload
	$questionId = filter_var($_POST[$COLUMN_SOLUTION_QUESTIONID], FILTER_SANITIZE_NUMBER_INT);
	$authorId = filter_var($_POST[$COLUMN_SOLUTION_AUTHORID], FILTER_SANITIZE_NUMBER_INT);
	$solutionText = filter_var($_POST[$COLUMN_SOLUTION_TEXT], FILTER_SANITIZE_STRING);
	$dateCreated = filter_var($_POST[$COLUMN_SOLUTION_DATE], FILTER_SANITIZE_NUMBER_INT);
		
	// build query
	$query = $INSERT . $TABLE_SOLUTION . $VALUES;
	$query .= ("(DEFAULT, " . $questionId . ", " . $authorId . ", ")
	$query .= ("'$solutionText'" . ", " . $dateCreated . ", " . "0, 0)");
	$query .= (" RETURNING \"" . $COLUMN_SOLUTION_SOLUTIONID . "\"");
	
	// execute query and return ID
	$rs = executeQuery($query);
	echo pg_fetch_result($rs, 0, 0);
?>