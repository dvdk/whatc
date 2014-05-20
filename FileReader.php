<?php
/**
 * Reads in CSV files and creates Customers/Bets
 */
include_once "Config.php";
include_once "Customer.php";
include_once "Bet.php";

class FileReader
{
	private $customers;
	
	/*
	 * Read in data from two CSVs and return customer db
	 */
	public function getCustomers()
	{
		$this->readFile("Settled.csv", Bet::SETTLED);
		$this->readFile("Unsettled.csv", Bet::UNSETTLED);
		return $this->customers;		
	}
	
	/*
	 * Read in data from CSV file specified by filename
	 * add bets to customer database.
	 * 
	 * Bets status determined by bet status
	 */
	private function readFile($filename, $betStatus)
	{
		$row = 1;
		if (($handle = fopen($filename, "r")) !== FALSE) {
    		while (($data = fgetcsv($handle)) !== FALSE) {    			
    			// Skip header row
    			if ($row++ == 1) {
    				continue;
    			}
    			
    			$this->addBet($betStatus, $data);    			
    		}
		}		
	}
	
	/*
	 * Create a new bet object from CSV data row
	 * associate it with customer, creating new customer if customer doesn't already exist
	 */
	private function addBet($status, $data)
	{
		// I'm assuming the data in the CSV is valid
		list($customerId, $event, $participant, $stake, $win) = $data;
		
		// Create new bet
		$bet = new Bet($status, $customerId, $event, $participant, $stake, $win);
		
		if (!$customer = &$this->customers[$customerId]) {
			$customer = new Customer($customerId);
			$customer->addBet($bet);
			$this->customers[$customerId] = $customer;
		} else {
			$customer->addBet($bet);
		}
	}
}
?>
