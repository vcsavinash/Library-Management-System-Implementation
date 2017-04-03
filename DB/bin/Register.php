<?php header("Content-Type: text/html; charset=ISO-8859-1");
// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$name = $_POST["name"];
$ssn = $_POST["ssn"];
$email = $_POST["email"];
$address = $_POST["address"];
$phone = $_POST["phone"];
$r = mysqli_fetch_array(mysqli_query($conn,"SELECT count(*) FROM borrower WHERE ssn='$ssn'"));
if($r[0] == 0){
	if((!empty($_POST["name"])) && (!empty($_POST["ssn"])) && (!empty($_POST["email"])) && (!empty($_POST["address"])) && (!empty($_POST["phone"]))) {
				$query = "INSERT INTO borrower (ssn,bname,email,address,phone) VALUES ('$ssn','$name','$email','$address','$phone')";
				$result = mysqli_query($conn,$query);
				if (!$result) {
		    		echo "Error in the registration!!";//die('Invalid query: ' . mysql_error());
				}else{
					$result1 = mysqli_query($conn,"SELECT card_id FROM borrower WHERE ssn='$ssn'");
					$value = mysqli_fetch_array($result1,MYSQLI_NUM);
						echo "You have registered. Your card number is $value[0]";
				}
	}else{
		echo "Please enter all values!";
	}
}else{
	echo "You are already a member";
}
?>