<?php
include_once "Bet.php";
include_once "Config.php";

class Customer implements JsonSerializable {
    private $settledBets;
    private $unsettledBets;
    private $wonBets;
    private $totalBets;
    private $totalStake;
    private $customerId;
   
    /*
     * Construct a new customer object with a given id
     */
    public function __construct($customerId)
    {    	
        $this->customerId = $customerId;

        $this->settledBets = array();
        $this->unsettledBets = array();
        $this->betsWon = 0; 
        $this->totalStake = 0;
        $this->totalBets = 0;
    }

    public function addBet($bet)
    {
        if ($bet->getStatus() == Bet::SETTLED) {
            $this->settledBets[] = $bet;
            if ($bet->didWin()) {
                $this->betsWon++;
            }
        } else {
            $this->unsettledBets[] = $bet;
        }
        
        $this->totalBets++;
        $this->totalStake+= $bet->getStake();
    }
    
    public function getCustomerId()
    {
    	return $this->customerId;
    }

    public function getSettledBetsWithRisk()
    {
        $winRisk = $this->getPercentWon() > WIN_RISK_THRESHOLD;
        foreach ($this->settledBets as &$bet) {
            if ($winRisk) {
                $bet->setRisk(Bet::RISKY);
            }
        }
        
        return $this->settledBets;
    }
    
    // Only return this customers settled bets if they were winning over the treshhold
    public function getRiskySettledBets()
    {
    	$winRisk = $this->getPercentWon() > WIN_RISK_THRESHOLD;
    	if ($winRisk) {
    		return $this->getSettledBetsWithRisk();
    	}
    	return array();
    }

    public function getUnsettledBetsWithRisk()
    {
        $winRisk = $this->getPercentWon() > WIN_RISK_THRESHOLD;
        foreach ($this->unsettledBets as &$bet) {
            if ($winRisk) {
                $bet->setRisk(Bet::RISKY);
            }
            
            $betStake = $bet->getStake();
            $averageStake = $this->getAverageStake();
            echo "Customer ID: ".$this->customerId ."Average:".$this->getAverageStake();
            
            if ($betStake > ($averageStake * HIGHLY_UNUSUAL_STAKE_MULTIPLIER)) {
            	$bet->setRisk(Bet::HIGHLY_UNUSUAL);
            } else if ($betStake > ($averageStake * UNUSUAL_STAKE_MULTIPLIER)) {
            	$bet->setRisk(Bet::UNUSUAL);
            }
            
            if ($bet->getWin() >= UNUSUAL_WIN_LIMIT) {
            	$bet->setRisk(Bet::UNUSUAL_WIN);
            }
        }
        
        return $this->unsettledBets;
    }

    private function getPercentWon()
    {
        return $this->betsWon / count($this->settledBets);
    }
    
    private function getAverageStake()
    {
    	return $this->totalStake / $this->totalBets;	
    }
    
	/*
     * Return json representation
     */
    public function jsonSerialize()
    {
    	return ['CustomerId' => $this->customerId,
				'SettledBets' => $this->settledBets,
    			'UnsettledBets' => $this->unsettledBets
    	]; 
    }
}
