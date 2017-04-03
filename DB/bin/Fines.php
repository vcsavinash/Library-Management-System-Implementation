<?php header("Content-Type: text/html; charset=ISO-8859-1");
// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
	$date = date("Y-m-d H:i:s");
	$currDate = date("Y-m-d", strtotime ($date ."-6 hours"));
	$query = "SELECT loan_id,DATEDIFF(curdate(),due_date) FROM book_loans WHERE DATEDIFF(curdate(),due_date)>0 AND date_in IS NULL";
	$result = mysqli_query($conn,$query);
	if (!$result) {
		echo "Error in the query!!";//die('Invalid query: ' . mysql_error());
	}else{
		//echo $loanid["loan_id"];
		//echo $loanid[1];
		while($loanid = mysqli_fetch_array($result,MYSQLI_NUM)){
	  		$query_for_not_null = mysqli_query($conn,"SELECT loan_id,fine_amount,paid FROM fines WHERE loan_id='$loanid[0]'");
			//if($loanid[1] > 0){
				$not_null = mysqli_fetch_array($query_for_not_null,MYSQLI_NUM);
				$value = $loanid[1]*0.25;
				if($not_null[0]!=null){
					if(mysqli_query($conn,"UPDATE fines SET fine_amount='$value' WHERE loan_id = '$loanid[0]'")){
						echo "Successfully updated fines for the book";
					}
				}else{
					if(mysqli_query($conn,"INSERT INTO fines (loan_id,fine_amount,paid) VALUES ('$loanid[0]','$value',0)")){
						echo "Successfully inserted fines for the book";
					}
				}
			//}else{
				//echo "Successfully checkedin the book";
			//}
		}
	}
?>