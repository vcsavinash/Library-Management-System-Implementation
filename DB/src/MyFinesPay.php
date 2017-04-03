<?php header("Content-Type: text/html; charset=ISO-8859-1");

// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$date = date("Y-m-d H:i:s");
$currDate = date("Y-m-d", strtotime ($date ."-6 hours"));
$allValarrays = $_POST["allValarrays"];
if(isset($_POST["allValarrays"]) && !empty($_POST["allValarrays"])) {
	for($i=0;$i<sizeof($allValarrays);$i++){
		//echo $allValarrays[$i];
		if(mysqli_query($conn,"UPDATE fines SET paid='1' WHERE loan_id = '$allValarrays[$i]'")){
			echo "Successfully paid fines for the book";
		}
	} 
}
?>