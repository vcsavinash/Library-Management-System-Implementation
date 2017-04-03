<?php header("Content-Type: text/html; charset=ISO-8859-1");
// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$allValarrays = $_POST["allValarrays"];
if(isset($_POST["allValarrays"]) && !empty($_POST["allValarrays"])) {
	for($i=0;$i<sizeof($allValarrays);$i++){
		$resultloan = mysqli_query($conn,"SELECT loan_id FROM book_loans WHERE card_id='$allValarrays[$i]' AND date_in IS NOT NULL");
		while($loanid = mysqli_fetch_array($resultloan)){
		    $id = $loanid["loan_id"];
		    //echo $id;
			$query = "SELECT fine_amount FROM fines WHERE loan_id =$id AND paid='0'";
			$result = mysqli_query($conn,$query);
			if (!$result) {
	    		echo "Error in the query!!";//die('Invalid query: ' . mysql_error());
			}else{
				$row = mysqli_fetch_array($result);//$result->fetch_assoc();
				if($row["fine_amount"]!=null){
				    $fineamount = $row["fine_amount"];
				    $return_arr[] = array("fine_amount" => $fineamount,
				    					  "loan_id" => $id,
				    					  "card_id" => $allValarrays[$i]);
				}
			}
		}
	}
	echo json_encode($return_arr);
}
?>