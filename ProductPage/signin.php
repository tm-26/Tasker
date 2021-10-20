<?php
    session_start();
    include 'server.php';
    if(isset($_SESSION['userId'])){
        header("Location: myaccount.php");
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Sign In</title>
        <link rel="tasker icon" type="image/x-icon" href="images/t_favicon.png"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="style.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta charset="utf-8"/>
    </head>

    <body>
        <section>
            <!-- navbar -->
            <nav class="navbar shadow-lg sticky-top navbar-expand-lg navbar-dark" style="background-color: #003d69">
                <a class="navbar-brand logo" href="home.php">TASKER</a>
            </nav>

            <!-- sign in form -->
            <div class="signincontent m-5">
                <div class="jumbotron jumbosize shadow-lg mx-auto">
                    <h3 class="signregistertitle mt-n3 mb-3">Sign in to your Tasker account</h3>
                    
                    <form class="px-4 py-3" method = "POST"> 
                        <a class="btn btn-primary btn-social btn-lg btn-block btn-google shadow-lg">
                            <span class="fa fa-google" style="color: white;"></span> <span style="color: white;">Sign in with Google</span>
                        </a>

                        <?php
                            // depending on what is passed to the url, the user will see the below messages
                            if(isset($_GET['error'])){
                                if($_GET['error'] == 'emptyfields'){
                                    echo '<p class="user-messages" style=\'margin: 1rem auto;\'>You have left a field empty.</p>';
                                }
                                else if($_GET['error'] == 'wrongpswd' || $_GET['error'] == 'usernotexist'){
                                    echo '<p class="user-messages" style=\'margin: 1rem auto;\'>Incorrect email or password.</p>';
                                }
                                else if($_GET['error'] == 'sqlerror'){
                                    echo '<p class="user-messages" style=\'margin: 1rem auto;\'>Error encountered, please let us know through our social media.</p>';
                                }
                            }
                            if(isset($_GET['account'])){
                                if($_GET['account'] == 'created'){
                                    echo '<p class="user-messages" style=\'margin: 1rem auto;\'>Please sign in to your newly created Tasker account.</p>';
                                }
                            }
                        ?>

                        <hr class="my-4">

                        <div class="form-group">
                            <label for="exampleDropdownFormEmail1">Email address</label>
                            <input required name = "signin-email" type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Password</label>
                            <input required name="signin-password" type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <div class="custom-control custom-switch ml-n3">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">Remember Me</label>
                                </div>
                            </div>
                        </div>
                        <p>By pressing Sign In you accept the following <a href="terms.php" target="_blank">Terms and Conditions</a></p>
                        <button name="signin-submit" type="submit" class="btn btn-primary shadow-lg">Sign in</button>
                    </form>

                    <hr class="my-4">

                    <a class="dropdown-item" href="register.php">New around here? <span style="color:rgb(8, 115, 255);">Sign up</span></a>
                    <a class="dropdown-item" href="404page.php">Forgot password?</a>
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