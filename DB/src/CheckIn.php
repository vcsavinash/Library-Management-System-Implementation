<?php header("Content-Type: text/html; charset=ISO-8859-1");
// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$borrowername = $_POST["borrowername"];
$checkinisbn = $_POST["checkinisbn"];
$borrowercardno=$_POST["borrowercardnoin"];
if((isset($_POST["borrowercardnoin"]) && !empty($_POST["borrowercardnoin"]))) {
		$query = "SELECT isbn,due_date FROM book_loans WHERE card_id=$borrowercardno AND date_in IS NULL";
		$result = mysqli_query($conn,$query);
		if (!$result) {
    		echo "Error in the query!!";//die('Invalid query: ' . mysql_error());
		}else{
			while($row = mysqli_fetch_array($result)){
			    $isbn = $row["isbn"];
			    $duedate = $row["due_date"];
			    $return_arr[] = array("isbn" => $isbn,
			                    "duedate" => $duedate);
			}
			// Encoding array in JSON format
			//echo json_encode($return_arr);
		}
} 
if((isset($_POST["borrowername"]) && !empty($_POST["borrowername"]))){
			$res = mysqli_query($conn,"SELECT b.isbn,b.due_date FROM book_loans as b where b.card_id IN (SELECT bo.card_id from borrower as bo where bo.bname LIKE '%$borrowername%') AND date_in IS NULL");
			if (!$res) {
	    		echo "Error in the query!!";//die('Invalid query: ' . mysql_error());
			}else{
				while($row1 = mysqli_fetch_array($res)){
				    $isbn1 = $row1["isbn"];
				    $duedate1 = $row1["due_date"];
				    if (multi_array_search($isbn1,$return_arr) == false)
  					{
  						 $return_arr[] = array("isbn" => $isbn1,
				                    "duedate" => $duedate1);
  					}
				}
				// Encoding array in JSON format
				//echo json_encode($return_arr);
			}
} 
if((isset($_POST["checkinisbn"]) && !empty($_POST["checkinisbn"]))){
		$queryi = "SELECT isbn,due_date FROM book_loans WHERE isbn='$checkinisbn' AND date_in IS NULL";
		$resulti = mysqli_query($conn,$queryi);
		if (!$resulti) {
    		echo "Error in the query!!";//die('Invalid query: ' . mysql_error());
		}else{
			while($rowi = mysqli_fetch_array($resulti)){
			    $isbni = $rowi["isbn"];
			    $duedatei = $rowi["due_date"];
			   if (multi_array_search($isbni,$return_arr) == false)
  					{
  						 $return_arr[] = array("isbn" => $isbni,
				                    "duedate" => $duedatei);
  					}
			}
			// Encoding array in JSON format
			//echo json_encode($return_arr);
		}
}
echo json_encode($return_arr);
function multi_array_search($search_for, $search_in) {
    foreach ($search_in as $element) {
        if ( ($element === $search_for) ){
            return true;
        }elseif(is_array($element)){
            $result = multi_array_search($search_for, $element);
            if($result == true)
                return true;
        }
    }
    return false;
}
?>