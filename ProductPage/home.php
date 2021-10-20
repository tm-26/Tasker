<?php
    session_start();
    include 'server.php';
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Tasker</title>
        <link rel="tasker icon" type="image/x-icon" href="images/t_favicon.png"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="style.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta charset="utf-8"/>
    </head>

    <body id="home-body">
        <section class="items">
            <!-- navbar -->
            <nav class="navbar shadow-lg sticky-top navbar-expand-lg navbar-dark" style="background-color: #ffffff">
                <a id="home-logo" class="navbar-brand" href="home.php">TASKER</a>
                <button id = "home-nav-icon" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul id = "home-nav" class="navbar-nav mr-auto">
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
                    <form id="home-search-top" class="form-inline my-2 my-lg-0 mr-3">
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

            <!-- slideshow -->
            <div class="slider-container">
                <div class="slider">
                    <div class="homeSlides">
                        <div id="tasker-slide"></div>
                    </div>
                    <div class="homeSlides">
                        <div id="connect-slide">Connect with clients and service providers</div>
                    </div>
                    <div class="homeSlides">
                        <div id="laptop-slide">Find the services you need through our website!</div>
                    </div>
                </div>
            </div>

            <!-- main content -->
            <div class="jumbotron pt-5">
                <div id = "featured-listings" class="container mt-5">
                    <h3>Featured Listings</h3><hr>
                    <?php
                        $sqlGetServices = "SELECT * FROM servicetable;";
                        $sqlGetUsers = "SELECT * FROM usertable;";
                        $statement = mysqli_stmt_init($db);
                        
                        if(!mysqli_stmt_prepare($statement, $sqlGetServices)){
                            header("Location: home.php?error=sqlerror");
                            exit();
                        }
                        else{
                            mysqli_stmt_execute($statement);
                            $services = mysqli_stmt_get_result($statement);

                            if (!is_null($services)) {
                                $rowCreate = '<div class = "row">';
                                $numOfItems = 0;

                                // If logged in
                                if(isset($_SESSION['userId'])){
                                    // Handle current user                               
                                    $userInfo = getUser();
                                    foreach(explode(',', substr(shell_exec("python3 rSystem.py ".(date('Y', time()) - date('Y', strtotime($userInfo['dateOfBirth'])))." \"".$userInfo['country']."\" \"".$userInfo['address']."\" \"".substr($userInfo['likes'], 1, -1)."\" \"" .implode(",",getServices(null))." \" 4 True"), 1, -2)) as $serviceId){
                                        if($serviceId == "rro"){
                                            goto noUser;
                                        }
                                        
                                        mysqli_stmt_prepare($statement, "SELECT * FROM servicetable WHERE id = '$serviceId' LIMIT 1");
                                        mysqli_stmt_execute($statement);
                                        $service = mysqli_fetch_assoc(mysqli_stmt_get_result($statement));

                                        $rowCreate = $rowCreate.'<div class="col-md-3">
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
                                        
                                        $rating = $service['rating'];
                                        $fullStars = floor($rating);

                                        if($rating == 0){
                                            $rowCreate = $rowCreate.'<p style=\'text-align:center; margin-bottom: 0; font-style: italic;\'>No Ratings</p>';
                                        }
                                        else{
                                            //depending on star rating of service, the appropriate number of stars are added to $rowCreate
                                            for ($i=0; $i < $fullStars; $i++) { 
                                                $rowCreate = $rowCreate.'<i class="fa fa-star"></i>';
                                            }
                                            if($rating!=$fullStars){
                                                $rowCreate = $rowCreate.'<i class="fa fa-star-half-o"></i>';
                                            }
                                        }

                                        $rowCreate = $rowCreate.'<a href="productpage.php?serviceid='.$service['id'].'">
                                                    <h3>'.$service['title'].'</h3>
                                                </a>
                                                <h5>€'.$service['price'].'</h5>
                                            </div>
                                        </div>';
                                    }
                                    echo($rowCreate);
                                } 
                                else {
                                    noUser:
                                    //the first four premium services in the service table are added to $rowCreate
                                    while ($row = mysqli_fetch_array($services) and ($numOfItems < 4)){
                                        if($row['isPremium'] == 1){
                                            $rowCreate = $rowCreate.'<div class="col-md-3">
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
                                                $rowCreate = $rowCreate.'<p style=\'text-align:center; margin-bottom: 0; font-style: italic;\'>No Ratings</p>';
                                            }
                                            else{
                                                //depending on star rating of service, the appropriate number of stars are added to $rowCreate
                                                for ($i=0; $i < $fullStars; $i++) { 
                                                    $rowCreate = $rowCreate.'<i class="fa fa-star"></i>';
                                                }
                                                if($rating!=$fullStars){
                                                    $rowCreate = $rowCreate.'<i class="fa fa-star-half-o"></i>';
                                                }
                                            }

                                            $rowCreate = $rowCreate.'<a href="productpage.php?serviceid='.$row['id'].'">
                                                        <h3>'.$row['title'].'</h3>
                                                    </a>
                                                    <h5>€'.$row['price'].'</h5>
                                                </div>
                                            </div>';

                                            ++$numOfItems;
                                        }
                                    }
                                    $rowCreate = $rowCreate.'</div>';
                                    echo($rowCreate);
                                }
                            } 
                        }
                    ?>
                </div>

                <div id = "featured-categories" class="container mt-5">
                    <h3>Categories</h3>
                    <h5 ><a id="show-all-categ" href="categories.php">View All Categories</a></h5>
                    <hr class="my-4">
                    <?php
                        $sqlGetCategs = "SELECT * FROM categorytable;";
                        $statement = mysqli_stmt_init($db);
                        
                        //checking if the sql statements are correct
                        if(!mysqli_stmt_prepare($statement, $sqlGetCategs)){
                            header("Location: home.php?error=sqlerror");
                            exit();
                        }
                        else{
                            mysqli_stmt_execute($statement);
                            $categories = mysqli_stmt_get_result($statement);
    
                            if (!is_null($categories)) {
                                $categs = '<div class = "row">';
                                $numOfItems = 0;
                                
                                // If logged in
                                if(isset($_SESSION['userId'])){
                                    $userInfo = getUser();
                                    
                                    foreach(explode(',', substr(shell_exec("python3 rSystem.py \"".substr($userInfo['likes'], 1, -1)."\" 4"), 1, -2)) as $category){
                                        mysqli_stmt_prepare($statement, "SELECT * FROM categorytable WHERE name = $category LIMIT 1");
                                        mysqli_stmt_execute($statement);
                                        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
                                    
                                        $categs = $categs.'<div class = "col-md-3">
                                            <div class="product-top shadow-lg cat">
                                                <a href="category.php?category='.$row['name'].'">
                                                    <img src="'.$row['image'].'" onerror="this.src=\'images/nia.png\';">
                                                    <div class="overlay-right category-title">
                                                        <i>'.$row['name'].'</i>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>';
                                    }
                                    $categs = $categs.'</div>';
                                    echo($categs);
                                } 
                                else {
                                    //the first four categories in the category table are added to $categs
                                    while ($row = mysqli_fetch_array($categories) and $numOfItems < 4 ){
                                        $categs = $categs.'<div class = "col-md-3">
                                            <div class="product-top shadow-lg cat">
                                                <a href="category.php?category='.$row['name'].'">
                                                    <img src="'.$row['image'].'" onerror="this.src=\'images/nia.png\';">
                                                    <div class="overlay-right category-title">
                                                        <i>'.$row['name'].'</i>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>';

                                        ++$numOfItems;
                                    }
                                    $categs = $categs.'</div>';
                                    echo($categs);
                                }
                            }
                            else{
                                echo '<p>No categories available.</p>';
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