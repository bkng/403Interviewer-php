<?php
	/*
	* This script deletes a Question from the system. We first validate the
	* user as the author of the Question by means of checking their email 
	* address.
	*/
	require('../util/requestParams.php');
	require('../util/queryTools.php');
	require('../util/db_tables.php');
	require('../util/security.php');

	// fetch vars
	$questionId = filter_var($_GET[$PARAM_QUESTIONID], FILTER_SANITIZE_NUMBER_INT);
	$email = filter_var(file_get_contents('php://input'), FILTER_SANITIZE_EMAIL);
	
	// check for existing userId
	$userId = getUserId($email);
	if ($userId < 0) {
		header("HTTP/1.1 401 Unauthorized");
		exit("User not found");
	}
	
	// prepare query
	$query = $DELETE . $FROM . $TABLE_QUESTION . $WHERE . "\"" . 
		$PARAM_QUESTIONID . "\"=" . "'" . $questionId . "'" . $AND . "\"" .
		$PARAM_AUTHORID . "\"=" . "'" . $userId . "'" . $RETURNING .
		"\"" . $COLUMN_QUESTION_QUESTIONID . "\"";
	$rs = executeQuery($query);
	if ($rs) {
		echo pg_fetch_result($rs, 0, 0);
	}
	else {
		echo "0";
	}
?>