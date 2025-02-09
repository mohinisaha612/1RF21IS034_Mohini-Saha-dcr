<?php
// Connect to your MySQL database
include('constants.php');
// Fetch data from the database
$sql = "SELECT title, price FROM tbl_food";
$result = mysqli_query($conn, $sql);

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        // Change currency symbol from dollar to rupee
        $price = '₹' . $row["price"];

        // Output the data
        echo "Food Title: " . $row["title"]. "<br>";
        echo "Price: " . $price . "<br>";
        echo "<br>";
    }
} else {
    echo "0 results";
}

// Close the connection
mysqli_close($conn);
?>