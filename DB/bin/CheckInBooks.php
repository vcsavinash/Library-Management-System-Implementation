<?php header("Content-Type: text/html; charset=ISO-8859-1");

// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$date = date("Y-m-d H:i:s");
$currDate = date("Y-m-d", strtotime ($date ."-6 hours"));
$allVals = $_POST["allVals"];
$borrowercardnoin1 = $_POST["borrowercardnoin1"];
for($i=0;$i<sizeof($allVals);$i++){
	//if((isset($_POST["allVals"]) && !empty($_POST["allVals"])) && (isset($_POST["borrowercardnoin1"]) && !empty($_POST["borrowercardnoin1"]))) {
	//echo "$allVals[$i]";
	//echo "$borrowercardnoin1";
		//if(mysqli_query($conn,"UPDATE booktable SET availability=1 WHERE isbn10='$allVals[$i]'")){
			//echo "Successfully updated availability";
			//if(mysqli_query($conn,"UPDATE book_loans SET date_in='$currDate', deleted = 1 WHERE isbn = '$allVals[$i]'")){
				//echo "Successfully updated deleted";
				$query_for_loan_id = mysqli_query($conn,"SELECT loan_id FROM book_loans WHERE isbn = '$allVals[$i]' AND date_in IS NULL");
				$loanid = mysqli_fetch_array($query_for_loan_id,MYSQLI_NUM);
				$query_for_not_null = mysqli_query($conn,"SELECT loan_id,fine_amount,paid FROM fines WHERE loan_id='$loanid[0]'");
				$query_for_date_diff = mysqli_query($conn,"SELECT DATEDIFF('$currDate',due_date) FROM book_loans WHERE isbn = '$allVals[$i]' AND loan_id='$loanid[0]'");
				$diff = mysqli_fetch_array($query_for_date_diff,MYSQLI_NUM);
				//echo "$diff[0]";
				if($diff[0] > 0){
					$not_null = mysqli_fetch_array($query_for_not_null,MYSQLI_NUM);
					$value = $diff[0]*0.25;
					if($not_null[0]!=null){
						if(mysqli_query($conn,"UPDATE fines SET fine_amount='$value' WHERE loan_id = '$loanid[0]'")){//NO update do select if exists
							echo "Successfully updated fines for the book";
							if(mysqli_query($conn,"UPDATE booktable SET availability=1 WHERE isbn10='$allVals[$i]'")){
								echo "Successfully updated availability";
								if(mysqli_query($conn,"UPDATE book_loans SET date_in='$currDate', deleted = 1 WHERE isbn = '$allVals[$i]' AND loan_id='$loanid[0]'")){
									echo "Successfully updated deleted and checked in";
								}else{
									echo "Error in updating deletion";
								}
							}else 
							echo "Error in updating availability";
						}
					}else{
						if(mysqli_query($conn,"INSERT INTO fines (loan_id,fine_amount,paid) VALUES ('$loanid[0]','$value',0)")){
							echo "Successfully inserted fines for the book";
							if(mysqli_query($conn,"UPDATE booktable SET availability=1 WHERE isbn10='$allVals[$i]'")){
								echo "Successfully updated availability";
								if(mysqli_query($conn,"UPDATE book_loans SET date_in='$currDate', deleted = 1 WHERE isbn = '$allVals[$i]' AND loan_id='$loanid[0]'")){
									echo "Successfully updated deleted and checked in";
								}else{
									echo "Error in updating deletion";
								}
							}else 
								echo "Error in updating availability";
						}
					}
				}else{
					echo "Successfully checkedin the book";
					if(mysqli_query($conn,"UPDATE booktable SET availability=1 WHERE isbn10='$allVals[$i]'")){
								echo "Successfully updated availability";
								if(mysqli_query($conn,"UPDATE book_loans SET date_in='$currDate', deleted = 1 WHERE isbn = '$allVals[$i]' AND loan_id='$loanid[0]'")){
									echo "Successfully updated deleted and checked in";
								}else{
									echo "Error in updating deletion";
								}
					}else 
								echo "Error in updating availability";
				}
			/*}else{
				echo "Error in updating deletion";
			}
		}else 
			echo "Error in updating availability";*/
	//} 
}
?>