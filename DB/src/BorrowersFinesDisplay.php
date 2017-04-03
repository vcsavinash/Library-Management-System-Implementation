<?php header("Content-Type: text/html; charset=ISO-8859-1");
// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$resultloan = mysqli_query($conn,"SELECT card_id, SUM(fine_amount) FROM book_loans AS b, fines AS f WHERE b.loan_id=f.loan_id GROUP BY card_id");
while($cardid = mysqli_fetch_array($resultloan)){
    $id = $cardid["card_id"];
    $totalfineamount = $cardid["SUM(fine_amount)"];
    //echo $id;
	$querypaid = "SELECT SUM(fine_amount) FROM fines WHERE loan_id IN (SELECT loan_id FROM book_loans WHERE card_id='$id') AND paid='0'";
	$result = mysqli_query($conn,$querypaid);
	$row = mysqli_fetch_array($result,MYSQLI_NUM);//$result->fetch_assoc();
	$fineremain = $totalfineamount-$row[0];
	if($id!=null){
	    $return_arr[] = array("total_fine_amount" => $totalfineamount,
	    					  "cardid" => $id,
	    					  "finepaid" => $fineremain,
	    					  "fineremain" => $row[0]);
	}
}
echo json_encode($return_arr);
?>