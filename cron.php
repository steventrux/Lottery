<?php
include '/include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//carica le api del wallet
$xml = "https://api.eveonline.com/corp/WalletJournal.xml.aspx?keyID=$keyID&vCode=$vCode&rowCount=2560"; // XML feed file/URL
$xmlCorp = simplexml_load_file($xml);
foreach ($xmlCorp->result->rowset->row as $row) { 

//scegli solo i versamenti che hanno una descrizione
    if ($row['reason'] != "") {
$refID = $row['refID'];
$ownerID = $row['ownerID1'];
$ownerName = $row['ownerName1'];
//controlla i versamenti già contabilizzati usando il refID

$sql = "SELECT *
FROM lottery.tickets
LEFT JOIN lottery.refunds ON lottery.tickets.reason = lottery.refunds.reason
WHERE lottery.tickets.refID = '$refID'
OR lottery.refunds.refID = '$refID'";
$result = $mysqli->query($sql);
$result1 = $result->fetch_array(MYSQLI_ASSOC);
$row_cnt = $result->num_rows;

//controlla che la descrizione del versamento corrisponda ad una lotteria esistente
$reason = substr($row['reason'], 6, -1);
$sql_lottery = "SELECT *
FROM lottery.lotteries
WHERE lottery.lotteries.reason = '$reason'";
$result_lottery = $mysqli->query($sql_lottery);
$result_lottery1 = $result_lottery->fetch_array(MYSQLI_ASSOC);
$row_lottery = $result_lottery->num_rows;
//se il versamento è valido continua...
if (($row_cnt == 0) and ($row_lottery > 0)){
//calcola i biglietti spettanti arrotondati per difetto
$allowedTicket = ($row['amount'] / $result_lottery1['ticketCost']);
$ticketBuyed = floor($allowedTicket);
//controlla che possa comprare almeno un biglietto
if ($ticketBuyed > 0){
//controlla ci siano abbastanza biglietti disponibili
if ($result_lottery1['ticketLeft'] > $ticketBuyed){
//registra tutti i biglietti
//trova l' ultimo biglietto venduto
$lotto_reason = substr($row['reason'], 0, -1);

for($i=1;$i<=$ticketBuyed;$i++){
//random 
$done = false;
$max = $result_lottery1['ticketNum'];
// Keep creating new numbers until we've found one that's unique.
do {
    // Generate a random number between 1 and x.
    $number = rand(1, $max);

    // Check if number exists in database.
	$sql_unique = "SELECT *
FROM lottery.tickets
WHERE lottery.tickets.reason = '$reason'
AND lottery.tickets.ticket = '$number'";
$result_unique = $mysqli->query($sql_unique);
$unique_cnt = $result_unique->num_rows;
	
    if($unique_cnt < 1) {
        $done = true;
    }
} while(!$done);
//end random

//inserisci i biglietti nel database
$register = "INSERT INTO lottery.tickets (reason, refID, ownerID, ownerName, ticket) VALUES (?,?,?,?,?)";
$stmt = $mysqli->prepare($register);
$stmt->bind_param("siisi", $reason, $refID, $ownerID, $ownerName, $number);
$stmt->execute();
}
//aggiorna i biglietti disponibili
$new_ticket_disp = $result_lottery1['ticketLeft'] - $ticketBuyed;
$query_ticket_disp = "UPDATE lottery.lotteries SET ticketLeft =? WHERE lottery.lotteries.reason = ?";
$stmt_ticket_disp = $mysqli->prepare($query_ticket_disp);
$stmt_ticket_disp->bind_param('is', $new_ticket_disp, $reason);
$stmt_ticket_disp->execute();

    
} else {
//registra i biglietti disponibili e il resto segnalo come rimborso
//trova l' ultimo biglietto venduto
$lotto_reason = substr($row['reason'], 0, -1);
//calcola quanti biglietti saranno da rimborsare
$ticket_refund = $ticketBuyed - $result_lottery1['ticketLeft'];

$maxAvailable = $result_lottery1['ticketLeft'];
for($i=1;$i<=$maxAvailable;$i++){
//random 
$done = false;
$max = $result_lottery1['ticketNum'];
// Keep creating new numbers until we've found one that's unique.
do {
    // Generate a random number between 1 and x.
    $number = rand(1, $max);

    // Check if number exists in database.
	$sql_unique = "SELECT *
FROM lottery.tickets
WHERE lottery.tickets.reason = '$reason'
AND lottery.tickets.ticket = '$number'";
$result_unique = $mysqli->query($sql_unique);
$unique_cnt = $result_unique->num_rows;
	
    if($unique_cnt < 1) {
        $done = true;
    }
} while(!$done);
//end random

//inserisci i biglietti nel database
$register = "INSERT INTO lottery.tickets (reason, refID, ownerID, ownerName, ticket) VALUES (?,?,?,?,?)";
$stmt = $mysqli->prepare($register);
$stmt->bind_param("siisi", $reason, $refID, $ownerID, $ownerName, $number);
$stmt->execute();
}

//azzera i biglietti disponibili
$ticket_end = 0;
$query_ticket_disp = "UPDATE lottery.lotteries SET ticketLeft =? WHERE lottery.lotteries.reason = ?";
$stmt_ticket_disp = $mysqli->prepare($query_ticket_disp);
$stmt_ticket_disp->bind_param('is', $ticket_end, $reason);
$stmt_ticket_disp->execute();
//registra i biglietti da rimborsare
$refunded = 0;
$register_refunds = "INSERT INTO lottery.refunds (reason, ownerID, ownerName, ticket, refID, refunded) VALUES (?,?,?,?,?,?)";
$stmt_refunds = $mysqli->prepare($register_refunds);
$stmt_refunds->bind_param('sisiii', $reason, $ownerID, $ownerName, $ticket_refund, $refID, $refunded);
$stmt_refunds->execute();
//chiudi la lotteria
$openLottery = 0;
$query_openLottery = "UPDATE lottery.lotteries SET open =? WHERE lottery.lotteries.reason = ?";
$stmt_openLottery = $mysqli->prepare($query_openLottery);
$stmt_openLottery->bind_param('is', $openLottery, $reason);
$stmt_openLottery->execute();
//avvisa via mail della chiusura di una lotteria
$email = 's.trussardi@gmail.com';
mail($email, 'Lottery', 'Lottery '.$result_lottery1['name'].' has just been closed. All tickets have been sold!', 'From: lottery@lottery.info');
}
}
}
}
}

?>