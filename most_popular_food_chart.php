<?php
include('config\constants.php');

$food_names = array();
$quantities = array();

$sql = "SELECT food, SUM(qty) AS total_qty FROM tbl_order GROUP BY food ORDER BY total_qty DESC";
$result = mysqli_query($conn, $sql);

// Fetch the best-selling item
$row = mysqli_fetch_assoc($result);
$best_selling_food = $row['food'];
$best_selling_quantity = $row['total_qty'];

// Reset the result pointer to fetch all data for the chart
mysqli_data_seek($result, 0);

while($row = mysqli_fetch_assoc($result)) {
    $food_names[] = $row['food'];
    $quantities[] = $row['total_qty'];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Orders Chart</title>
    <!-- Add any additional meta tags, stylesheets, or scripts here -->
    <style>
        /* Add your custom CSS styles here */
        /* Adjust styles to fit your website's design */
    </style>
</head>
<body>
    <h1>Best-Selling Food: <?php echo $best_selling_food; ?> (<?php echo $best_selling_quantity; ?> orders)</h1>
    <h2>Food Orders Chart</h2>
      <!-- Hyperlink to Home Page -->
      <a href="index.php">Back to Home</a>
    <div id="chart-container">
        <canvas id="bar-chart"></canvas>
    </div>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data from PHP
        var foodNames = <?php echo json_encode($food_names); ?>;
        var quantities = <?php echo json_encode($quantities); ?>;

        // Chart.js code to create bar chart
        var ctx = document.getElementById('bar-chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: foodNames,
                datasets: [{
                    label: 'Quantity Ordered',
                    data: quantities,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Adjust color as needed
                    borderColor: 'rgba(54, 162, 235, 1)', // Adjust color as needed
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Ordered'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Food Name'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>