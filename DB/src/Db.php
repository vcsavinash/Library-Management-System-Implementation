<?php header("Content-Type: text/html; charset=ISO-8859-1");

// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$search=$_POST["search"];
$arr = explode(" ", $search);
$flag = false;
$show = true;
if((isset($_POST["search"]))) {
	foreach ($arr as $value){
		$query = "Select isbn10,title,group_concat(a.author_name) as authors,availability from booktable as b 
		           join book_authors as ba on b.isbn10=ba.isbn join authors as a on ba.author_id=a.author_id
		           where isbn10 like '%$value%' or title like '%$value%' or a.author_name like '%$value%' group by ba.isbn";
		$result = mysqli_query($conn,$query);
		if($result != NULL){
		 	while($row = $result->fetch_assoc()) {
			 	if($show == true)
					echo "<table><tr><th>ISBN</th><th>Title</th><th>Author</th><th>Available</th></tr>";
				$show = false;
			 	$flag = true;
			 	echo "<tr><td>".$row["isbn10"]."</td>";
			 	echo "<td>".$row["title"]."</td>";
			 	echo "<td>".$row["authors"]."</td>";
			 	if($row["availability"]==0)
			 		echo "<td>Not Available</td></tr>";
			 	else
			 		echo "<td>Available</td></tr>";
			 }
			 if($flag == false && $row == NULL)
		 		echo "Sorry !There are no books for this search!";
		}else
			echo "Sorry !There are no books for this search!";
	}  
	echo "</table>";
}else
 	echo "Sorry !There are no books for this search!";
?> 