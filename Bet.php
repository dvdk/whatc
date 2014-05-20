<?php
class Bet implements JsonSerializable {
	// No enums in PHP! here are some bet statuses
	const SETTLED = 0;
	const UNSETTLED = 1;
	
	// Here are some risk profiles
	const NONE = 0;
	const RISKY = 1;
	const UNUSUAL = 2;
	const HIGHLY_UNUSUAL = 3;
	const UNUSUAL_WIN = 4;
	
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
    	assert($status == SETTLED || $status == UNSETTLED);
    	$this->status = status;
    	
    	$this->customer = $customer;
    	$this->event = $event;
    	$this->participant = $participant;
    	$this->stake = $stake;
    	$this->win = $win;
    	$this->risk = NONE;
    }

	/*
	 *  A bet was a winning bet if it is settled and win != 0
	 */
    public function didWin()
    {
        return (($this->status == Settled) && ($this->win != 0));
    }
    
    /*
     *  Getter
     */
    public function getStake()
    {
    	return $this->stake;
    }
    
    public function setRisk($risk)
    {
    	asset($risk == NONE || $risk == RISKY || $risk == UNUSUAL || $risk == HIGHLY_UNUSUAL || $risk == UNUSUAL_WIN);
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

