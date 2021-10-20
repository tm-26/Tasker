<?php
    session_start();
    include 'server.php';
    if(!isset($_GET['category'])){
        header("Location: 404page.php");
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Category</title>
        <link rel="tasker icon" type="image/x-icon" href="images/t_favicon.png" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="style.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta charset="utf-8"/>
    </head>

    <body>
        <section class="items">
            <!-- navbar -->
            <nav class="navbar shadow-lg sticky-top navbar-expand-lg navbar-dark" style="background-color: #003d69">
                <a class="navbar-brand logo" href="home.php">TASKER</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="home.php">Home<span class="sr-only">(current)</span></a>
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
                        //if signed in, the user will see the sign out button
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
            <div class="jumbotron pt-5">
                <?php
                    if(isset($_GET['category'])){
                        $category = $_GET['category'];
                        $getCateg = "SELECT * FROM `categorytable` WHERE `name`='$category'";
                        $statement = mysqli_stmt_init($db);
                        $rServices = array();
                        $n = 0;

                        if(isset($_SESSION['userId'])){
                            $userInfo = getUser();
                            $listOfServices = getServices($category);
                            $rServices = explode(',', substr(shell_exec("python3 rSystem.py ".(date('Y', time()) - date('Y', strtotime($userInfo['dateOfBirth'])))." \"".$userInfo['country']."\" \"".$userInfo['address']."\" \"".substr($userInfo['likes'], 1, -1)."\" \"" .implode(",", $listOfServices)." \" \"".count($listOfServices)."\" False"), 1, -2));
                            
                            $n = sizeof($rServices);
                        } 
                        else {
                            mysqli_stmt_prepare($statement, $getCateg);
                            mysqli_stmt_execute($statement);
                            $result = mysqli_stmt_get_result($statement);
                            if ($row = mysqli_fetch_assoc($result)) {
                                $n = $row['numberOfServices'];
                            }
                        }
                       
                        //the category being shown is printed along with the number of shown services
                        echo '<h1 class="display-4">Category: <b>'.$category.'</b></h1>
                             <p>There are <b>'.$n.'</b> listings in this category</p>
                             <hr class="my-4">';
                    }

                    if(isset($_SESSION['userId'])){
                        //increase user's category popularity
                        $userID = $_SESSION['userId'];
                        $getLikes = mysqli_query($db, "SELECT likes FROM usertable WHERE id='$userID'");
                        $tmpLikes = mysqli_fetch_assoc($getLikes)['likes'];
                        $getCategory = $_GET['category'];
                        
                        $jLikes = @json_decode($tmpLikes, true);
                        //if json doesnt exist
                        if(json_last_error() != JSON_ERROR_NONE){
                            $tmpJString = '{"'.$getCategory.'":1}';
                            mysqli_query($db, "UPDATE usertable SET likes='$tmpJString' WHERE id='$userID'");
                        }
                        //if key is in json
                        elseif(array_key_exists($getCategory, $jLikes)){
                            $jLikes[$getCategory] = $jLikes[$getCategory] + 1;
                            $tmpJString = json_encode($jLikes);
                            mysqli_query($db, "UPDATE usertable SET likes='$tmpJString' WHERE id='$userID'");
                        }
                        //if key is not in json
                        else{
                            $jLikes[$getCategory] = 1;
                            $tmpJString = json_encode($jLikes);
                            mysqli_query($db, "UPDATE usertable SET likes='$tmpJString' WHERE id='$userID'");
                        }
                    }
                ?>

                <!-- filters/options -->
                <div class="accordion shadow-lg" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <form class="form my-1">
                                <button class="btn btn-outline-primary pull-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Filters
                                </button>
                            </form>
                            <form class="form-inline pull-right">
                                <p class="lead my-1 mr-2" for="inlineFormCustomSelectPref">Sort By</p>
                                <select class="custom-select my-0 mr-sm-3" id="inlineFormCustomSelectPref">
                                <option selected>Featured</option>
                                </select>  
                            </form>
                        </div>
                
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <!-- filter form -->
                                <form>
                                    <p style='text-align: right; font-style: italic;'>Not Yet Implemented</p>
                                    <!-- price range filter -->
                                    <div class="row">
                                        <div class="input-group mb-3 col-md-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">From €</span>
                                            </div>
                                            <input type="text" placeholder="0" class="form-control text-right" aria-label="Amount (to the nearest euro)">
                                            <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
        
                                        <div class="input-group mb-3 col-md-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">To &nbsp;&nbsp;&nbsp;&nbsp; €</span>
                                            </div>
                                            <input type="text" placeholder="250" class="form-control text-right" aria-label="Amount (to the nearest euro)">
                                            <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- minimum rating filter -->
                                    <label for="ratingFilter">Minimum Rating</label>
                                    <input type="range" class="custom-range"  min="0" max="5" id="ratingFilter">

                                    <div class="pull-right my-3">
                                        <button type="button" class="btn btn-outline-primary">Clear</button>
                                        <button type="button" class="btn btn-primary">Apply</button>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- product list -->
                <div class="container mt-5">
                    <?php
                        if(isset($_GET['category'])){
                            $category = $_GET['category'];
                            $servicesNum = 0;
                            $getCategServices = "SELECT * FROM `servicetable` WHERE `category`='$category'";
                            $statement = mysqli_stmt_init($db);

                            if(mysqli_stmt_prepare($statement, $getCategServices)){
                                $servicePrint = '<div class="row">';
                                
                                if(isset($_SESSION['userId'])){
                                    // Handle current user
                                    foreach($rServices as $serviceId){
                                        if(!is_numeric($serviceId)){
                                            goto noUser;
                                        }
                                        mysqli_stmt_prepare($statement, "SELECT * FROM servicetable WHERE id = '$serviceId'");
                                        mysqli_stmt_execute($statement);
                                        $service = mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
                                        
                                        $servicePrint = $servicePrint.'<div class="col-md-3">
                                            <div class="product-top shadow-lg">
                                                <a href="productpage.php?serviceid='.$service['id'].'">
                                                    <img src="'.$service['images'].'" onerror="this.src=\'images/nia.png\';">
                                                </a>
                                                <div class="overlay-right">
                                                    <button type="button" class="btn btn-secondary" title="Save for Later">
                                                        <i class="fa fa-heart-o rounded"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product-bottom text-center">';
    
                                        $rating = (float)$service['rating'];
                                        $fullStars = floor($rating);
    
                                        if($rating == 0){
                                            $servicePrint = $servicePrint.'<p style=\'text-align:center; margin-bottom: 0; font-style: italic;\'>No Ratings</p>';
                                        }
                                        else{
                                            //depending on star rating of service, the appropriate number of stars are added to $servicePrint
                                            for ($i=0; $i < $fullStars; $i++) { 
                                                $servicePrint = $servicePrint.'<i class="fa fa-star"></i>';
                                            }
                                            if($rating!=$fullStars){
                                                $servicePrint = $servicePrint.'<i class="fa fa-star-half-o"></i>';
                                            }
                                        }
    
                                        $servicePrint = $servicePrint.'<a href="productpage.php?serviceid='.$service['id'].'">
                                                    <h3>'.$service['title'].'</h3>
                                                </a>
                                                <h5>€'.$service['price'].'</h5>
                                            </div>
                                        </div>';
                                    }
                                    $servicePrint = $servicePrint.'</div>';
    
                                    echo $servicePrint;

                                } 
                                else {
                                    noUser:
                                    mysqli_stmt_execute($statement);
                                    $result = mysqli_stmt_get_result($statement);
    
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ++$servicesNum;
    
                                        $servicePrint = $servicePrint.'<div class="col-md-3">
                                            <div class="product-top shadow-lg">
                                                <a href="productpage.php?serviceid='.$row['id'].'">
                                                    <img src="'.$row['images'].'" onerror="this.src=\'images/nia.png\';">
                                                </a>
                                                <div class="overlay-right">
                                                    <button type="button" class="btn btn-secondary" title="Save for Later">
                                                        <i class="fa fa-heart-o rounded"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product-bottom text-center">';
    
                                        $rating = (float)$row['rating'];
                                        $fullStars = floor($rating);
    
                                        if($rating == 0){
                                            $servicePrint = $servicePrint.'<p style=\'text-align:center; margin-bottom: 0; font-style: italic;\'>No Ratings</p>';
                                        }
                                        else{
                                            //depending on star rating of service, the appropriate number of stars are added to $servicePrint
                                            for ($i=0; $i < $fullStars; $i++) { 
                                                $servicePrint = $servicePrint.'<i class="fa fa-star"></i>';
                                            }
                                            if($rating!=$fullStars){
                                                $servicePrint = $servicePrint.'<i class="fa fa-star-half-o"></i>';
                                            }
                                        }
    
                                        $servicePrint = $servicePrint.'<a href="productpage.php?serviceid='.$row['id'].'">
                                                    <h3>'.$row['title'].'</h3>
                                                </a>
                                                <h5>€'.$row['price'].'</h5>
                                            </div>
                                        </div>';
                                    }
                                    $servicePrint = $servicePrint.'</div>';
    
                                    echo $servicePrint;
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </section>

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

        <!-- Placed here since JavaScript should ideally not be loaded before page content -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    </body>
</html>