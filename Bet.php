<?php
class Bet implements JsonSerializable {
	// No enums in PHP! here are some bet statuses
	const SETTLED = 0;
	const UNSETTLED = 1;
	
	// Risk profiles. Most serious risk has highest value
	const NONE = 0;
	const RISKY = 1;
	const UNUSUAL_WIN = 2;	
	const UNUSUAL = 3;
	const HIGHLY_UNUSUAL = 4;
	
	// Customer
	private $customer;
	
    // Event
    private $event;

    // Participant
    private $participant;

    // Stake
    private $stake;

    // Potential Win / Amount Won depending on status
    private $win;

    // Status SETTLED or UNSETTLED
    private $status;
    
    // Risk profile for this bet
    private $risk;
	
	/*
	 *  Create a new bet object
	 */    
    public function __construct($status, $customer, $event, $participant, $stake, $win)
    {
    	assert($status == Bet::SETTLED || $status == Bet::UNSETTLED);
    	$this->status = $status;
    	
    	$this->customer = $customer;
    	$this->event = $event;
    	$this->participant = $participant;
    	$this->stake = $stake;
    	$this->win = $win;
    	$this->risk = Bet::NONE;
    }

	/*
	 *  A bet was a winning bet if it is settled and win != 0
	 */
    public function didWin()
    {
        return (($this->status == Bet::SETTLED) && ($this->win != 0));
    }
    
    /*
     *  Getters
     */
    public function getStatus()
    {
    	return $this->status;
    }
     
    public function getStake()
    {
    	return $this->stake;
    }
    
    public function getWin()
    {
    	return $this->win;
    }
    
    
    public function setRisk($risk)
    {
    	assert($risk == Bet::NONE || 
    		  $risk == Bet::RISKY || 
    		  $risk == Bet::UNUSUAL || 
    		  $risk == Bet::HIGHLY_UNUSUAL || 
    		  $risk == Bet::UNUSUAL_WIN);
    		  
    	$this->risk = $risk;    	
    }
    
    /*
     * Return json representation
     */
    public function jsonSerialize()
    {
    	return ['Risk' => $this->risk,
				'Customer' => $this->customer,
    			'Event' => $this->event,
    			'Participant' => $this->participant,
    			'Stake' => $this->stake,
    			'Win' => $this->win
    	]; 
    }
}

