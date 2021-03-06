<?php
	session_start();

	$_SESSION['error'] = 0;

	////////////////////////////////////////////////////////////////////////////////////////////
	// ERROR CHECK

	if(isset($_POST['bill_subtotal']))
	{
		$_SESSION['bill_subtotal'] = htmlspecialchars($_POST['bill_subtotal']);

		if((!is_numeric($_SESSION['bill_subtotal'])) || ($_SESSION['bill_subtotal'] <= 0))
		{	
			$_SESSION['error'] .= 1;				// bill subtotal input is invalid, concatenate 1 to error code
			// $_SESSION['bill_subtotal'] = 0;		// default value to 0
		}
	}

	if(isset($_POST['percentage']))
	{
		if($_POST['percentage'] == "custom")
		{
			$_SESSION['custom'] = htmlspecialchars($_POST['custom']);
			$_SESSION['percentage'] = $_SESSION['custom'];

			if((!is_numeric($_SESSION['custom'])) || ($_SESSION['custom'] <= 0))
				$_SESSION['error'] .= 2;		// custom input is invalid, concatenate 2 to error code
		}
		else
		{
			$_SESSION['percentage'] = $_POST['percentage'];
		}
	}
	
	if(isset($_POST['num_persons']))
	{
		$_SESSION['num_persons'] = htmlspecialchars($_POST['num_persons']);

		if((!is_numeric($_SESSION['num_persons'])) || ($_SESSION['num_persons'] <= 0) || ($_SESSION['num_persons'] != round($_SESSION['num_persons'])))
			$_SESSION['error'] .= 3;		//split input is invalid, concatenate 3 to error code
	}

	/////////////////////////////////////////////////////////////////////////////////////////////
	// PROCESS

	if($_SESSION['error'] == 0)
	{
		$tip = $_SESSION['bill_subtotal'] * $_SESSION['percentage'] / 100.00;		// calculate tip
		$total = $_SESSION['bill_subtotal'] + $tip;						// calculate total

		$_SESSION['output'] = "";

		// generate output, round results to two decimal places
		$_SESSION['output'] .= "Tip: $" . number_format(round($tip, 2), 2) . "<br>" . "Total: $" . number_format(round($total, 2), 2);

		// if number of persons is greater than one, output tip and total for each person
		if($_SESSION['num_persons'] > 1)
		{
			$tip_each = $tip / $_SESSION['num_persons'];
			$total_each = $total / $_SESSION['num_persons'];

			$_SESSION['output'] .= "<br>Tip each: $" . number_format(round($tip_each, 2), 2) . "<br>Total each: $" . number_format(round($total_each, 2), 2);
		}
	}

	// return to previous page
	if(isset($_SERVER["HTTP_REFERER"]))
		header("Location: {$_SERVER["HTTP_REFERER"]}");
?>
