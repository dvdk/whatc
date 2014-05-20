rowCallback = function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	// Convert risk profile to lowercase string with space removed, add class to row
	risk = "";
	// Set custom risk string and based on risk value
	switch (aData["Risk"]) {
		case 0:        			
			break;
		case 1:
			risk = "Winning too often";
			break;
		case 2:
			risk = "High Payout Chance";
			break;
		case 3:
			risk = "Unusual";
			break;
		case 4:
			risk = "HIGHLY UNUSUAL";
			break;
	}
	$('td:eq(0)', nRow).html(risk);

	// Set a custom class on risky rows
	classname = risk.replace(/\s+/g, '').toLowerCase();
	$(nRow).addClass(classname);
};

$(document).ready(function() {
    $('#settled').dataTable( {
        "ajax": "data.php?betstatus=0",
        "columns": [
            { "data": "Risk" },
            { "data": "Customer" },
            { "data": "Event" },
            { "data": "Participant" },
            { "data": "Stake" },
            { "data": "Win" }
        ],
        "iDisplayLength": 100,
        "order": [[0, "desc"], [1, "asc"]],
        "fnRowCallback": rowCallback
        
    } );
} );

$(document).ready(function() {
    $('#unsettled').dataTable( {
        "ajax": "data.php?betstatus=1",
        "columns": [
            { "data": "Risk" },
            { "data": "Customer" },
            { "data": "Event" },
            { "data": "Participant" },
            { "data": "Stake" },
            { "data": "Win" }
        ],
        "iDisplayLength": 100,
        "order": [[0, "desc"], [1, "asc"]],
        "fnRowCallback": rowCallback
    } );
} );