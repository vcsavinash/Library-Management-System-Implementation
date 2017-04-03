<?php header("Content-Type: text/html; charset=ISO-8859-1");

// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$checkoutisbn = $_POST["checkoutisbn"];
$borrowercardno=$_POST["borrowercardno"];
$date = date("Y-m-d H:i:s");
$flag=0;
$currDate = date("Y-m-d", strtotime ($date ."-6 hours"));
$fiveDays = date("Y-m-d", strtotime ($currDate ."+14 days"));
$available = "SELECT count(*) FROM booktable WHERE isbn10='$checkoutisbn' AND availability=0";
$resultav = mysqli_fetch_array(mysqli_query($conn,$available),MYSQLI_NUM);
//$resultsavai = (int)$resultav;
$duedate = "SELECT date_in FROM book_loans WHERE card_id=$borrowercardno AND deleted='0'";
$resultduedates = mysqli_query($conn,$duedate);
while($diff = mysqli_fetch_array($resultduedates)){
	if(!is_null($diff["date_in"]))
		$flag = 1;
}
if($flag == 0 ){
	if($resultav[0] == 0)
	{
		$noofloans = "SELECT count(*) FROM book_loans WHERE card_id=$borrowercardno AND date_in IS NULL";
		$resultloans = "SELECT count(*) FROM fines WHERE loan_id IN (SELECT loan_id FROM book_loans WHERE date_in IS NULL AND card_id =$borrowercardno) AND paid='0'";
		$num_rows = mysqli_fetch_array(mysqli_query($conn,$noofloans),MYSQLI_NUM);//mysql_num_rows($resultloans);
		$resultnum = mysqli_fetch_array(mysqli_query($conn,$resultloans),MYSQLI_NUM);
		if($num_rows[0] < 3){
			if($resultnum[0] == 0){
				if((isset($_POST["checkoutisbn"])) && (isset($_POST["borrowercardno"]))) {
					$query = "INSERT INTO book_loans (isbn, card_id, date_out, due_date) VALUES ('$checkoutisbn',$borrowercardno,'$currDate','$fiveDays')";
					$result = mysqli_query($conn,$query);
					if (!$result) {
			    		echo "Please verify. Some entered values may be incorrect!!";//die('Invalid query: ' . mysql_error());
					}else{
						if(mysqli_query($conn,"UPDATE booktable SET availability=0 WHERE isbn10='$checkoutisbn'"))
							echo "Checked Out!";
						else 
							echo "Error in query!";
					}
				} 
			}else{
				echo "!!Sorry!You already have to pay fines for books. You will not be permitted to checkout!!";
			}
		} else {
		echo "!!Sorry!You already have 3 book loans with this id. You will not be permitted to checkout!!";
		}
	} else {
		echo "Sorry!This book is already checked out";
	}
}else{
	echo "Sorry!You already have books for checking in";
}
?> 