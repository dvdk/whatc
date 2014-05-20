<?php
include_once "FileReader.php";
include_once "Bet.php";

function getBetData($status)
{
	$fr = new FileReader();
	$customers = $fr->getCustomers();
	$unsettledBets = array();
	$settledBets = array();

	foreach ($customers as $customer) {
		$unsettledBets = array_merge($unsettledBets, $customer->getUnsettledBetsWithRisk());
		$settledBets =  array_merge($settledBets, $customer->getRiskySettledBets());
	}
	
	if ($status == Bet::UNSETTLED) {
		$ret = array("data" => $unsettledBets);
	} else if ($status == Bet::SETTLED) {
		$ret = array("data" => $settledBets);
	}
	
	return $ret;
}

if (isset($_GET["betstatus"])) {
	$status = $_GET["betstatus"];
	assert($status == Bet::SETTLED || $status == Bet::UNSETTLED);
	header('Content-Type: application/json');
	echo json_encode(getBetData($status));
}

?>
