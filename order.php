<?php
ob_start(); // Start output buffering
?>
<?php include('partials-front/menu.php'); ?>

<?php 
    //Check whether food id is set or not
    if(isset($_GET['food_id']))
    {
        //Get the Food id and details of the selected food
        $food_id = $_GET['food_id'];

        //Get the Details of the Selected Food
        $sql = "SELECT * FROM tbl_food WHERE id=$food_id";
        //Execute the Query
        $res = mysqli_query($conn, $sql);
        //Count the rows
        $count = mysqli_num_rows($res);
        //Check whether the data is available or not
        if($count==1)
        {
            //Data Available
            //Get the Data from Database
            $row = mysqli_fetch_assoc($res);

            $title = $row['title'];
            $price = $row['price'];
            $image_name = $row['image_name'];

            // Get the category of the selected food
            $category_id = $row['category_id'];

            // Fetch other items from the same category excluding the currently selected item
            $sql_similar = "SELECT * FROM tbl_food WHERE category_id=$category_id AND id!=$food_id";
            $result_similar = mysqli_query($conn, $sql_similar);
        }
        else
        {
            //Food not Available
            //Redirect to Home Page
            header('location:'.SITEURL);
        }
    }
    else
    {
        //Redirect to homepage
        header('location:'.SITEURL);
    }
?>

<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search">
    <div class="container">
        
        <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

        <form action="" method="POST" class="order">
            <fieldset>
                <legend>Selected Food</legend>

                <div class="food-menu-img">
                    <?php 
                        //Check whether the image is available or not
                        if($image_name=="")
                        {
                            //Image not Available
                            echo "<div class='error'>Image not Available.</div>";
                        }
                        else
                        {
                            //Image is Available
                            ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                            <?php
                        }
                    ?>
                    
                </div>

                <div class="food-menu-desc">
                    <h3><?php echo $title; ?></h3>
                    <input type="hidden" name="food" value="<?php echo $title; ?>">

                    <p class="food-price">₹<?php echo $price; ?></p>
                    <input type="hidden" name="price" value="<?php echo $price; ?>">

                    <div class="order-label">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" value="1" required>
                    
                </div>

            </fieldset>
            
            <fieldset>
                <legend>Delivery Details</legend>
                <div class="order-label">Full Name</div>
                <input type="text" name="full-name" placeholder="E.g. Mohini Saha" class="input-responsive" required>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" placeholder="E.g. 9843xxxxxx" class="input-responsive" required>

                <div class="order-label">Email</div>
                <input type="email" name="email" placeholder="E.g. mohini@gmail.com" class="input-responsive" required>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive" required></textarea>

                <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
            </fieldset>

        </form>

        <?php 
            //Check whether submit button is clicked or not
            if(isset($_POST['submit']))
            {
                // Get all the details from the form
                $food = $_POST['food'];
                $price = $_POST['price'];
                $qty = $_POST['qty'];

                $total = $price * $qty; // total = price x qty 
                $order_date = date("Y-m-d h:i:sa"); //Order Date
                $status = "Ordered";  // Ordered, On Delivery, Delivered, Cancelled
                $customer_name = $_POST['full-name'];
                $customer_contact = $_POST['contact'];
                $customer_email = $_POST['email'];
                $customer_address = $_POST['address'];

                //Save the Order in Database
                //Create SQL to save the data
                $sql2 = "INSERT INTO tbl_order SET 
                    food = '$food',
                    price = $price,
                    qty = $qty,
                    total = $total,
                    order_date = '$order_date',
                    status = '$status',
                    customer_name = '$customer_name',
                    customer_contact = '$customer_contact',
                    customer_email = '$customer_email',
                    customer_address = '$customer_address'
                ";

                //Execute the Query
                $res2 = mysqli_query($conn, $sql2);

                //Check whether query executed successfully or not
                if($res2==true)
                {
                    //Query Executed and Order Saved
                    $_SESSION['order'] = "<div class='success text-center'>Food Ordered Successfully.</div>";
                    header('location:'.SITEURL);
                }
                else
                {
                    //Failed to Save Order
                    $_SESSION['order'] = "<div class='error text-center'>Failed to Order Food.</div>";
                    header('location:'.SITEURL);
                }
            }
        ?>

    </div>
</section>
<!-- fOOD sEARCH Section Ends Here -->


<<!-- Similar Items Section Starts Here -->
<section class="similar-items">
    <div class="container">
        <h2 class="text-center">Similar Items</h2>
        <div class="row">
            <?php 
                // Check if there are at least two similar items
                if(mysqli_num_rows($result_similar) >= 2) {
                    // Fetch and display only the first two similar items
                    $counter = 0; // Counter to limit to two items
                    while($row_similar = mysqli_fetch_assoc($result_similar)) {
                        if($counter < 2) {
                            ?>
                            <div class="col-md-4">
                                <div class="food-menu-box">
                                    <div class="food-menu-img">
                                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $row_similar['image_name']; ?>" alt="<?php echo $row_similar['title']; ?>" class="img-responsive img-curve">
                                    </div>
                                    <div class="food-detail text-center">
                                        <h4><?php echo $row_similar['title']; ?></h4>
                                        <p class="food-price">₹<?php echo $row_similar['price']; ?></p>
                                        <!-- Display other details of the similar food items here -->
                                    </div>
                                </div>
                            </div>
                            <?php
                            $counter++; // Increment the counter
                        } else {
                            break; // Break the loop after displaying two items
                        }
                    }
                } else {
                    echo "<p class='text-center'>No similar items found.</p>";
                }
            ?>
        </div>
    </div>
</section>
<!-- Similar Items Section Ends Here -->

<!-- Similar Items Section Ends Here -->



<?php include('partials-front/footer.php'); ?>
<?php
ob_end_flush(); // Send the buffered output to the browser
?>