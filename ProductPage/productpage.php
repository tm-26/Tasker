<?php
    session_start();
    include 'server.php';
    if(!isset($_GET['serviceid'])){
        header("Location: 404page.php");
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title> Product Page</title>
        <link rel="tasker icon" type="image/x-icon" href="images/t_favicon.png" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="style.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta charset="utf-8"/>
    </head>

    <body>
        <!-- navbar -->
        <nav class="navbar shadow-lg sticky-top navbar-expand-lg navbar-dark" style="background-color: #003d69">
            <a class="navbar-brand logo" href="home.php">TASKER</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <?php
                        //if signed in, the user will get to see the below links in the navbar
                        if(isset($_SESSION['userId'])){
                            echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"saved.php\">Saved Listings</a> </li>
                            <li class=\"nav-item\"><a class=\"nav-link\" href=\"myaccount.php\">My Account</a> </li>";
                        }
                    ?>
                </ul>

                <!-- search bar -->
                <form class="form-inline my-2 my-lg-0 mr-3">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-light my-2 my-sm-0" type="submit">Search</button>
                </form>
                
                <?php
                    //if signed in, the user will see the signout button
                    if(isset($_SESSION['userId'])){
                        echo "<form method = \"POST\"> 
                        <button name=\"signout-button\" type=\"submit\" class=\"btn btn-outline-light my-2 my-sm-0 mr-2\">Sign Out</button>
                        </form>";
                    }
                    //otherwise, they will see the signin/register buttons
                    else{
                        echo "<a type=\"button\" class=\"btn btn-outline-light my-2 my-sm-0 mr-2\" href=\"signin.php\">Sign In</a>
                        <a type=\"button\" class=\"btn btn-outline-light my-2 my-sm-0\" href=\"register.php\">Register</a>";
                    }
                ?>
            </div>
        </nav>

        <!-- main content -->
        <div class="container mt-4">
            <div class="row">
                <?php
                    $serviceID = $_GET['serviceid'];
                    $getService = "SELECT * FROM servicetable WHERE id='$serviceID'";
                    $statement = mysqli_stmt_init($db);

                    //increment popularity visit value
                    $getPopularity = mysqli_query($db, "SELECT popularity FROM servicetable WHERE id='$serviceID'");
                    $tmp = mysqli_fetch_assoc($getPopularity)['popularity'];
                    $newVal = $tmp + 0.01;
                    mysqli_query($db, "UPDATE servicetable SET popularity=$newVal WHERE id=$serviceID");

                    //get age of user and calculate difference
                    if(isset($_SESSION['userId'])){
                        $userID = $_SESSION['userId'];
                        $getDob = mysqli_query($db, "SELECT dateOfBirth FROM usertable WHERE id='$userID'");
                        $from = mysqli_fetch_assoc($getDob)['dateOfBirth'];
                        $from = new DateTime($from);
                        $to   = new DateTime('today');
                        $curr = $from->diff($to)->y;
                        $getVal = mysqli_query($db, "SELECT * FROM servicetable WHERE id='$serviceID'");
                        if($curr >= 16 && $curr < 20){
                            $val = mysqli_fetch_assoc($getVal)['ageGroup0'];
                            $newVal = $val + 1;
                            mysqli_query($db, "UPDATE servicetable SET ageGroup0=$newVal WHERE id=$serviceID");
                        }
                        else if($curr >= 20 && $curr < 30){
                            $val = mysqli_fetch_assoc($getVal)['ageGroup1'];
                            $newVal = $val + 1;
                            mysqli_query($db, "UPDATE servicetable SET ageGroup1=$newVal WHERE id=$serviceID");
                        }
                        else if($curr >= 30 && $curr < 40){
                            $val = mysqli_fetch_assoc($getVal)['ageGroup2'];
                            $newVal = $val + 1;
                            mysqli_query($db, "UPDATE servicetable SET ageGroup2=$newVal WHERE id=$serviceID");
                        }
                        else if($curr >= 40 && $curr < 50){
                            $val = mysqli_fetch_assoc($getVal)['ageGroup3'];
                            $newVal = $val + 1;
                            mysqli_query($db, "UPDATE servicetable SET ageGroup3=$newVal WHERE id=$serviceID");
                        }
                        else if($curr >= 50 && $curr < 60){
                            $val = mysqli_fetch_assoc($getVal)['ageGroup4'];
                            $newVal = $val + 1;
                            mysqli_query($db, "UPDATE servicetable SET ageGroup4=$newVal WHERE id=$serviceID");
                        }
                        else{
                            $val = mysqli_fetch_assoc($getVal)['ageGroup5'];
                            $newVal = $val + 1;
                            mysqli_query($db, "UPDATE servicetable SET ageGroup5=$newVal WHERE id=$serviceID");
                        }
                    }

                    if(mysqli_stmt_prepare($statement, $getService)){
                        //execute the query which gets the current service
                        mysqli_stmt_execute($statement);
                        $result = mysqli_stmt_get_result($statement);

                        //if a result is returned, get image of service
                        if ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="col-md-5">
                                <img style="width: inherit; height: inherit;" src="'.$row['images'].'" onerror="this.src=\'images/nia.png\';">
                            </div>';
                        }
                        //if no result is returned, service does not exist
                        else{
                            header("Location: 404page.php");
                            exit();
                        }
                    }
                ?>

                <!-- details of current item -->
                <div class="col-md-7">
                    <p class="location text-center"><?php echo $row['country'] ?></p>
                    <h2><?php echo $row['title'] ?></h2>
                    <?php
                        $serviceOwnerID = $row['userId'];
                        $getUser = "SELECT * FROM usertable WHERE id='$serviceOwnerID'";
                        $statement = mysqli_stmt_init($db);

                        //if sql can be executed, which should be the case since a service must be associated with an existing user
                        if(mysqli_stmt_prepare($statement, $getUser)){
                            mysqli_stmt_execute($statement);
                            $result = mysqli_stmt_get_result($statement);

                            //if a result is returned, get image of service
                            if ($userRow = mysqli_fetch_assoc($result)) {
                                echo '<p>'.$userRow['firstName'].' '.$userRow['lastName'].'</p>';
                            }
                            //if no result is returned, service does not exist
                            else{
                                header("Location: 404page.php?");
                                exit();
                            }
                        }
                    ?>
                    <div class="stars">
                        <?php
                            $rating = (float)$row['rating'];
                            $fullStars = floor($rating);

                            if($rating == 0){
                                echo '<p style=\'margin-bottom: 0; font-style: italic;\'>No Ratings</p>';
                            }
                            else{
                                for ($i=0; $i < $fullStars; $i++) { 
                                    echo '<i class="fa fa-star"></i>';
                                }
                                if($rating!=$fullStars){
                                    echo '<i class="fa fa-star-half-o"></i>';
                                }
                            }
                        ?>
                    </div>
                    <p class="price">EUR <?php echo $row['price'] ?></p>
                    <p><b>Contact on: </b><?php echo $row['mobile'] ?></p>
                    <p><b>Description: </b><?php echo $row['description'] ?></p>
                    <?php
                        if(isset($_SESSION['userId'])){
                            
                            //when message button clicked
                            if(isset($_POST['message'])) { 

                                //increase global listing popularity
                                $serviceID = $_GET['serviceid'];
                                $getPopularity = mysqli_query($db, "SELECT popularity FROM servicetable WHERE id='$serviceID'");
                                $tmp = mysqli_fetch_assoc($getPopularity)['popularity'];
                                $newVal = $tmp + 0.05;
                                mysqli_query($db, "UPDATE servicetable SET popularity=$newVal WHERE id=$serviceID");

                                //increase user's listing popularity
                                $userID = $_SESSION['userId'];
                                $getLikes = mysqli_query($db, "SELECT likes FROM usertable WHERE id='$userID'");
                                $tmpLikes = mysqli_fetch_assoc($getLikes)['likes'];
                                $serviceID = $_GET['serviceid'];
                                $getCategory = mysqli_query($db, "SELECT category FROM servicetable WHERE id='$serviceID'");
                                $tmpCategory = mysqli_fetch_assoc($getCategory)['category'];
                                
                                $jLikes = @json_decode($tmpLikes, true);
                                //if json doesnt exist
                                if(json_last_error() != JSON_ERROR_NONE){
                                    $tmpJString = '{"'.$tmpCategory.'":10}';
                                    mysqli_query($db, "UPDATE usertable SET likes='$tmpJString' WHERE id='$userID'");
                                }
                                //if key is in json
                                elseif(array_key_exists($tmpCategory, $jLikes)){
                                    $jLikes[$tmpCategory] = $jLikes[$tmpCategory] + 10;
                                    $tmpJString = json_encode($jLikes);
                                    mysqli_query($db, "UPDATE usertable SET likes='$tmpJString' WHERE id='$userID'");
                                }
                                //if key is not in json
                                else{
                                    $jLikes[$tmpCategory] = 10;
                                    $tmpJString = json_encode($jLikes);
                                    mysqli_query($db, "UPDATE usertable SET likes='$tmpJString' WHERE id='$userID'");
                                }
                            } 
                            echo '<form method="post"> 
                                <button type="button" class="btn btn-outline-primary shadow-lg mr-2 saveLater">
                                    Save for Later
                                </button>
                        
                                <button type="submit" class="btn btn-primary shadow-lg saveLater" name="message">
                                    Send a Message
                                </button>
                            </form>';
                        }
                    ?>
                    <p>&nbsp</p>
                </div>
            </div>

            <!-- cards for suggestions related to search -->
            <div style="margin-top: 50px;" class="related">
            <h4>Related to your search:</h4>
            <div class="card-deck" style="margin-top: 25px;">
                <?php
                    $shownServiceIDs = array();
                    array_push($shownServiceIDs, $serviceID);
                    //current service id is placed in array

                    $serviceCateg = $row['category'];
                    $getRelServices = "SELECT * FROM servicetable WHERE category='$serviceCateg'";
                    $statement = mysqli_stmt_init($db);

                    //if sql can be executed
                    if(mysqli_stmt_prepare($statement, $getRelServices)){
                        mysqli_stmt_execute($statement);
                        $result = mysqli_stmt_get_result($statement);

                        $serviceNum = 0;
        
                        while ($relRow = mysqli_fetch_assoc($result) and $serviceNum < 4) {
                            // if the id of the related service is not the same as the service being displayed
                            if($relRow['id'] != $serviceID){
                                echo '<div class="card shadow-lg">
                                    <img class="card-img-top" src="'.$relRow['images'].'" onerror="this.src=\'images/nia.png\';" alt="Card image cap">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$relRow['title'].'</h5>
                                        <p class="card-text">'.$relRow['description'].'</p>
                                    </div>
                                </div>';

                                array_push($shownServiceIDs, $relRow['id']);
                                // the id of each shown service is added to the array

                                $serviceNum++;
                            }
                        }

                        // if not enough related searches have been displayed, all services are retrieved from the database
                        if($serviceNum < 4){
                            $getOtherServices = "SELECT * FROM servicetable";
                            $statement = mysqli_stmt_init($db);

                            if(mysqli_stmt_prepare($statement, $getOtherServices)){
                                mysqli_stmt_execute($statement);
                                $result = mysqli_stmt_get_result($statement);
                
                                while ($relRow = mysqli_fetch_assoc($result) and $serviceNum < 4) {
                                    // if id of current service is not found in the array, it can be displayed as it is not already displayed on the page
                                    if(!in_array($relRow['id'], $shownServiceIDs)){
                                        echo '<div class="card shadow-lg">
                                            <img class="card-img-top" src="'.$relRow['images'].'" onerror="this.src=\'images/nia.png\';" alt="Card image cap">
                                            <div class="card-body">
                                                <h5 class="card-title">'.$relRow['title'].'</h5>
                                                <p class="card-text">'.$relRow['description'].'</p>
                                            </div>
                                        </div>';

                                        $serviceNum++;
                                    }
                                }
                            }
                        }
                    }
                ?>
            </div>
        </div>
            
        </div>

        <!-- Footer -->
        <footer class="page-footer font-small teal pt-4">

        <!-- Footer Text -->
        <div class="container-fluid text-center text-md-left">

            <!-- Grid row -->
            <div class="row">

                <!-- Grid column -->
                <div class="col-md-6 mt-md-0 mt-3">

                    <!-- Content -->
                    <h5 class="text-uppercase font-weight-bold">About Tasker</h5>
                    <p>Through Tasker, service providers can easily reach their audience by posting and promoting their services. On the other hand,
                        people who are looking for a service can easily search through Tasker to find what they need, while also being recommended services
                        based on their previous activity.
                    </p>
                </div>
                <!-- Grid column -->

                <hr class="clearfix w-100 d-md-none pb-3">

                <!-- Grid column -->
                <div class="col-md-6 mb-md-0 mb-3">

                    <!-- Content -->
                    <h5 class="text-uppercase font-weight-bold">About Us</h5>
                    <p>Tasker is created by 6 University of Malta students belonging to the Faculty of Economics, 
                        Accounting and Management, and the Faculty of Information and Communication Technology. Tasker is our way 
                        of showcasing what we have learnt so far through our respective degrees.</p>

                </div>
                <!-- Grid column -->

            </div>
            <!-- Grid row -->

        </div>
        <!-- Footer Text -->

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">
            <a href="https://www.facebook.com/" class="fa fa-facebook"></a>
            <a href="https://twitter.com/" class="fa fa-twitter"></a>
            <a href="https://www.instagram.com/" class="fa fa-instagram"></a>
            © <?php echo date("Y")?> Copyright:
            <a href="https://placeholder.com/"> placeholder.com</a>
        </div>
        <!-- Copyright -->

        </footer>
        <!-- Footer -->

    </body>
</html>