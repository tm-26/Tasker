<?php
    session_start();
    include 'server.php';
    if(!isset($_SESSION['userId'])){
        header("Location: signin.php");
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Listings</title>
        <link rel="tasker icon" type="image/x-icon" href="images/t_favicon.png"/>
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
                        <li class="nav-item">
                            <a class="nav-link" href="saved.php">Saved Listings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="myaccount.php">My Account</a>
                        </li>
                    </ul>

                    <!-- search bar -->
                    <form class="form-inline my-2 my-lg-0 mr-3">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-light my-2 my-sm-0" type="submit">Search</button>
                    </form>
                    
                    <!-- sign out -->
                    <form method = "POST"> 
                        <button name="signout-button" type="submit" class="btn btn-outline-light my-2 my-sm-0 mr-2">Sign Out</button>
                    </form>
                </div>
            </nav>

            <!-- main content -->
            <div class="jumbotron pt-5">
                <h1 class="display-4">Saved Listings</h1>
                <p>You currently have <b>2</b> saved listings</p>
                <p style='font-style: italic;'>Not Yet Implemented</p>
                <hr class="my-4">

                <!-- product list -->
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="product-top shadow-lg">
                                <a href="productpage.php">
                                    <img src="images/astronaut.png" onerror="this.src='images/nia.png';">
                                </a>
                                <div class="overlay-right">
                                    <button type="button" class="btn btn-secondary" title="Save for Later">
                                        <i class="fa fa-heart rounded"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-bottom text-center">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <a href="productpage.php">
                                    <h3>Rocket Engineer</h3>
                                </a>
                                <h5>€82.00</h5>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="product-top shadow-lg">
                                <a href="productpage.php">
                                    <img src="images/pizza.png" onerror="this.src='images/nia.png';">
                                </a>
                                <div class="overlay-right">
                                    <button type="button" class="btn btn-secondary" title="Save for Later">
                                        <i class="fa fa-heart rounded"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-bottom text-center">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                                <i class="fa fa-star-o"></i>
                                <a href="productpage.php">
                                    <h3>Pizza Delivery</h3>
                                </a>
                                <h5>€4.00</h5>
                            </div>
                        </div>

                    </div>
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