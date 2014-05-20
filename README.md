whatc
=====

To use:
Checkout all files onto a webserver with php and hit index.html 
or try at http://www.mafia.org.au/whatc/

Solution overview:
I solved the challenge using PHP to read in / parse the data and spit out json feeds
for unsettled and settled bets.

Display is handled by a simple page (I'm not a front end engineer) using jquery
and datatables (https://datatables.net).

On the front end two tables are displayed, one showing all the risky settled bets
(ie bets where customer won over 60% of the time). (I do have a function to show
all settled bets but didnt think this was necessary).

The other shows all unsettled bets sorted by risk, then customer id:
Highly unusual (betting >30x average)
Unusual (> 10x average)
High payout chance (> 1000 potential win)
Winning too often (customer with history of winning >60% of the time)
No risk

Sine the dataset is really small, I've not done anything tricky with datastructures.
Everything is stored in memory, fetched from disk for every request, and risks are
calculated on the fly when requested.

I've also calculated the average stake amount across all settled and unsettled bets.
This calculation means a user needs quite a bit of bet history before a bet can be 
flagged as "above average". I'm not sure if I should have only calculated based on
settled bets. (eg a customer who's previously bet $1 and puts on a $1,000,000 bet 
can't more than 2x average by definition when average = (bet1+bet2)/2).

The challenge was pretty simple so I didn't write unit tests, however I have
checked each type of risk is shown correctly by manually playing with the csv files
(see git commits).

Another thing not considered is that bets can have multiple different risk profiles.
Eg a bet could trigger a warning for having a potential win amount > $1000, AND
if the customer has a high win rate AND a third time if the bet in question was > 10x
the the customers average bet amount. Ideally the status property of the Bet object 
should be a bit mask, and Customer::getUnsettledBetsWithRisk() should set these values
independently, with the datatable having logic to show multiple different risk profiles
(possibly a separate image in the LH column for each risk type triggered).

Current logic will always flag a bet falling in multiple categories first as high win 
risk, next as unusuaul or highly unusual (depending on stake vs average bet), and finally
as a risky customer who wins to often.

PHP implementation:

FileReader - implements file i/o and sets up data structures
Bet:
Model class for bet, fields correlating to CSV as well as additional fields for 
status (settled/unsettled) and risk.

Customer
Model class for a customer. Every customer has an id and an array of settled and
unsettled bets.

Implements methods to add a bet, and get a list of all unsettled bets with risk.
For convenience, I keep a running tally of bets won, total stake and total bets to
help determine risk for each bet when getUnsettledBetsWithRisk() is called

Config:
Define magic numbers/constants for business rules

Data:
Uses FileReader class above to create an array of customers. Exposes settled and 
unsettled bet data including risk based on betstatus get param. Returns JSON
for datatable.
