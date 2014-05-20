<?php
include_once "FileReader.php";
include_once "Bet.php";

$foo = new FileReader();
$customers = $foo->getCustomers();

foreach ($customers as $customer) {
	echo "<pre><h1>Customer ID: ". $customer->getCustomerId() ."</h1>";
	echo "<h2>Settled Bets:</h2>";
	print_r($customer->getUnsettledBetsWithRisk());
	echo "<h2>Unsettled Bets:</h2>";
	print_r($customer->getSettledBetsWithRisk());
	echo "</pre>";
}

?>
