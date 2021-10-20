<!doctype html>
<html lang="en">
    <head>
        <title>Page Not Found!</title>
        <link rel="tasker icon" type="image/x-icon" href="images/t_favicon.png" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="style.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <meta charset="utf-8"/>
    </head>

    <body>
        <section>
            <!-- navbar -->
            <nav class="navbar shadow-lg sticky-top navbar-expand-lg navbar-dark" style="background-color: #003d69">
                <a class="navbar-brand logo" href="home.php">TASKER</a>
            </nav>

            <!-- 404 error message -->
            <div class="404content m-5">
                <div class="jumbotron shadow-lg">
                    <h1 class="big404">Oops!</h1>
                    <p class="lead mediumjumbocol">It seems like the page you're looking for has ran away.</p>
                    <hr class="my-4">
                    <p>Help us find our page by using the search bar below.</p>
                    <form class="form-inline my-2 my-lg-0 mr-3">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
                    </form>              
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
    </body>
</html>