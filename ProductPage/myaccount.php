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
        <title>My Account</title>
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
            
            <!-- depending on what is passed to the url, the user will see the below messages -->
            <div class="container mt-5 col-12">
                <?php
                    if(isset($_GET['error'])){
                        if($_GET['error'] == 'sqlerror'){
                            echo '<p class="user-messages">Error encountered, please let us know through our social media.</p>';
                        }
                        else if($_GET['error'] == 'emptyfields'){
                            echo '<p class="user-messages">You have left a required field empty</p>';
                        }
                        else if($_GET['error'] == 'filetoolarge'){
                            echo '<p class="user-messages">Please reenter your service\'s information with images no greater than 1000KB/1MB.</p>';
                        }
                        else if($_GET['error'] == 'imagetype'){
                            echo '<p class="user-messages">Please reenter your service\'s information with images only of type .jpg, .jpeg or .png</p>';
                        }
                        else if($_GET['error'] == 'invalidphoneformat'){
                            echo '<p class="user-messages">Please reenter your service\'s information with valid mobile and telephone numbers.</p>';
                        }
                        else if($_GET['error'] == 'invalidprice'){
                            echo '<p class="user-messages">Please reenter your service\'s information with a valid price.</p>';
                        }
                        else if($_GET['error'] == 'invalidmail'){
                            echo '<p class="user-messages">Please reenter your service\'s information with a valid email address.</p>';
                        }
                        else if($_GET['error'] == 'invalidaddress'){
                            echo '<p class="user-messages">Please reenter your service\'s information with a valid address or otherwise, tick the displayed checkbox.</p>';
                        }     
                    }
                    if(isset($_GET['actionsuccess'])){
                        if($_GET['actionsuccess'] == 'create'){
                            echo '<p class="user-messages">You have added a new service!</p>';
                        }
                    }
                ?>
                <div class="row mr-3">
                    <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-my-tab" data-toggle="pill" href="#v-pills-my" role="tab" aria-controls="v-pills-my" aria-selected="true">My Listings</a>
                        <a class="nav-link" id="v-pills-add-tab" data-toggle="pill" href="#v-pills-add" role="tab" aria-controls="v-pills-add" aria-selected="false">Add Listing</a>
                        <a class="nav-link" id="v-pills-edit-tab" data-toggle="pill" href="#v-pills-edit" role="tab" aria-controls="v-pills-edit" aria-selected="false">Edit Listing</a>
                        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
                    </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- my listings panel -->
                            <div class="tab-pane fade show active" id="v-pills-my" role="tabpanel" aria-labelledby="v-pills-my-tab">
                                <div class="jumbotron shadow-lg pt-4">
                                    <p class="lead"><b>Your Active Listings</b></p>
                                    <?php
                                        $userId = $_SESSION['userId'];
                                        $servicesNum = 0;
                                        $getUserListings = "SELECT * FROM servicetable WHERE userId='$userId'";
                                        $statement = mysqli_stmt_init($db);

                                        //sql fails if user does not have any services in the service table, so they are informed they have 0 listings
                                        if(!mysqli_stmt_prepare($statement, $getUserListings)){
                                            echo '<p style="font-size: 15px;">You currently have <b>0</b> listings</p>';
                                        }
                                        else{
                                            mysqli_stmt_execute($statement);
                                            $result = mysqli_stmt_get_result($statement);
                                                
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ++$servicesNum;
                                            }
                                            //the category being shown is printed along with the number of listed services
                                            echo '<p style="font-size: 15px;">You currently have <b>'.$servicesNum.'</b> listings</p>';
                                        }
                                    ?>
                                    <hr class="mt-4">
                                    <div class="container mt-5">
                                        <?php
                                            $userId = $_SESSION['userId'];
                                            $getUserListings = "SELECT * FROM servicetable WHERE userId='$userId'";
                                            $statement = mysqli_stmt_init($db);

                                            if(mysqli_stmt_prepare($statement, $getUserListings)){
                                                mysqli_stmt_execute($statement);
                                                $result = mysqli_stmt_get_result($statement);
                    
                                                $servicePrint = '<div class="row">';
                    
                                                //all services belong to the selected category are added to $servicePrint
                                                while ($row = mysqli_fetch_assoc($result)) {
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
                                                    
                                                    //if rating is 0 (default value), "No ratings" is output instead of stars
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
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- add listing panel -->
                            <div class="tab-pane fade" id="v-pills-add" role="tabpanel" aria-labelledby="v-pills-add-tab">
                                <div class="jumbotron shadow-lg pt-4">
                                    <p class="lead"><b>Add a Listing</b></p>
                                    <hr class="my-4">
                                    <form class="needs-validation" method="post" enctype="multipart/form-data" novalidate>
                                        <div class="form-row">
                                            <div class="col-md-5 mb-3">
                                                <label for="service-categ">Category*</label>
                                                <select class="custom-select" name="service-category" id="service-categ">
                                                    <?php 
                                                        $sqlGetCategories = "SELECT * FROM categorytable;";
                                                        $statement = mysqli_stmt_init($db);
                                                
                                                        //checking if sql is correct
                                                        if(!mysqli_stmt_prepare($statement, $sqlGetCategories)){
                                                            header("Location: myaccount.php?error=sqlerror");
                                                            exit();
                                                        }
                                                        //looping through each row in category table and printing the name in the desired format
                                                        else{
                                                            mysqli_stmt_execute($statement);
                                                            $result = mysqli_stmt_get_result($statement);
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                $categ = '<option value="'.$row['name'].'">'.$row['name'].'</option>';
                                                                echo $categ;
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="service-title">Title*</label>
                                            <input name="service-title" type="text" class="form-control" id="service-title" placeholder="Item Title" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="service-desc">Description*</label>
                                            <textarea name="service-desc" class="form-control" id="service-desc" style="resize: none; height: 100px;" placeholder="Item Description" required></textarea>
                                        </div>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="service-country">Service's Operating Country*</label>
                                            <div class="input-group mb-3">
                                                <select id="service-country" class="custom-select" name="service-country" required>
                                                    <option selected="selected" value="N.A">Not Applicable</option>
                                                    <option value="Afganistan">Afghanistan</option>
                                                    <option value="Albania">Albania</option>
                                                    <option value="Algeria">Algeria</option>
                                                    <option value="American Samoa">American Samoa</option>
                                                    <option value="Andorra">Andorra</option>
                                                    <option value="Angola">Angola</option>
                                                    <option value="Anguilla">Anguilla</option>
                                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                                    <option value="Argentina">Argentina</option>
                                                    <option value="Armenia">Armenia</option>
                                                    <option value="Aruba">Aruba</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="Austria">Austria</option>
                                                    <option value="Azerbaijan">Azerbaijan</option>
                                                    <option value="Bahamas">Bahamas</option>
                                                    <option value="Bahrain">Bahrain</option>
                                                    <option value="Bangladesh">Bangladesh</option>
                                                    <option value="Barbados">Barbados</option>
                                                    <option value="Belarus">Belarus</option>
                                                    <option value="Belgium">Belgium</option>
                                                    <option value="Belize">Belize</option>
                                                    <option value="Benin">Benin</option>
                                                    <option value="Bermuda">Bermuda</option>
                                                    <option value="Bhutan">Bhutan</option>
                                                    <option value="Bolivia">Bolivia</option>
                                                    <option value="Bonaire">Bonaire</option>
                                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Brazil">Brazil</option>
                                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                                    <option value="Brunei">Brunei</option>
                                                    <option value="Bulgaria">Bulgaria</option>
                                                    <option value="Burkina Faso">Burkina Faso</option>
                                                    <option value="Burundi">Burundi</option>
                                                    <option value="Cambodia">Cambodia</option>
                                                    <option value="Cameroon">Cameroon</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="Canary Islands">Canary Islands</option>
                                                    <option value="Cape Verde">Cape Verde</option>
                                                    <option value="Cayman Islands">Cayman Islands</option>
                                                    <option value="Central African Republic">Central African Republic</option>
                                                    <option value="Chad">Chad</option>
                                                    <option value="Channel Islands">Channel Islands</option>
                                                    <option value="Chile">Chile</option>
                                                    <option value="China">China</option>
                                                    <option value="Christmas Island">Christmas Island</option>
                                                    <option value="Cocos Island">Cocos Island</option>
                                                    <option value="Colombia">Colombia</option>
                                                    <option value="Comoros">Comoros</option>
                                                    <option value="Congo">Congo</option>
                                                    <option value="Cook Islands">Cook Islands</option>
                                                    <option value="Costa Rica">Costa Rica</option>
                                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                                    <option value="Croatia">Croatia</option>
                                                    <option value="Cuba">Cuba</option>
                                                    <option value="Curaco">Curacao</option>
                                                    <option value="Cyprus">Cyprus</option>
                                                    <option value="Czech Republic">Czech Republic</option>
                                                    <option value="Denmark">Denmark</option>
                                                    <option value="Djibouti">Djibouti</option>
                                                    <option value="Dominica">Dominica</option>
                                                    <option value="Dominican Republic">Dominican Republic</option>
                                                    <option value="East Timor">East Timor</option>
                                                    <option value="Ecuador">Ecuador</option>
                                                    <option value="Egypt">Egypt</option>
                                                    <option value="El Salvador">El Salvador</option>
                                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                    <option value="Eritrea">Eritrea</option>
                                                    <option value="Estonia">Estonia</option>
                                                    <option value="Ethiopia">Ethiopia</option>
                                                    <option value="Falkland Islands">Falkland Islands</option>
                                                    <option value="Faroe Islands">Faroe Islands</option>
                                                    <option value="Fiji">Fiji</option>
                                                    <option value="Finland">Finland</option>
                                                    <option value="France">France</option>
                                                    <option value="French Guiana">French Guiana</option>
                                                    <option value="French Polynesia">French Polynesia</option>
                                                    <option value="French Southern Ter">French Southern Ter</option>
                                                    <option value="Gabon">Gabon</option>
                                                    <option value="Gambia">Gambia</option>
                                                    <option value="Georgia">Georgia</option>
                                                    <option value="Germany">Germany</option>
                                                    <option value="Ghana">Ghana</option>
                                                    <option value="Gibraltar">Gibraltar</option>
                                                    <option value="Great Britain">Great Britain</option>
                                                    <option value="Greece">Greece</option>
                                                    <option value="Greenland">Greenland</option>
                                                    <option value="Grenada">Grenada</option>
                                                    <option value="Guadeloupe">Guadeloupe</option>
                                                    <option value="Guam">Guam</option>
                                                    <option value="Guatemala">Guatemala</option>
                                                    <option value="Guinea">Guinea</option>
                                                    <option value="Guyana">Guyana</option>
                                                    <option value="Haiti">Haiti</option>
                                                    <option value="Hawaii">Hawaii</option>
                                                    <option value="Honduras">Honduras</option>
                                                    <option value="Hong Kong">Hong Kong</option>
                                                    <option value="Hungary">Hungary</option>
                                                    <option value="Iceland">Iceland</option>
                                                    <option value="Indonesia">Indonesia</option>
                                                    <option value="India">India</option>
                                                    <option value="Iran">Iran</option>
                                                    <option value="Iraq">Iraq</option>
                                                    <option value="Ireland">Ireland</option>
                                                    <option value="Isle of Man">Isle of Man</option>
                                                    <option value="Israel">Israel</option>
                                                    <option value="Italy">Italy</option>
                                                    <option value="Jamaica">Jamaica</option>
                                                    <option value="Japan">Japan</option>
                                                    <option value="Jordan">Jordan</option>
                                                    <option value="Kazakhstan">Kazakhstan</option>
                                                    <option value="Kenya">Kenya</option>
                                                    <option value="Kiribati">Kiribati</option>
                                                    <option value="Korea North">Korea North</option>
                                                    <option value="Korea Sout">Korea South</option>
                                                    <option value="Kuwait">Kuwait</option>
                                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                    <option value="Laos">Laos</option>
                                                    <option value="Latvia">Latvia</option>
                                                    <option value="Lebanon">Lebanon</option>
                                                    <option value="Lesotho">Lesotho</option>
                                                    <option value="Liberia">Liberia</option>
                                                    <option value="Libya">Libya</option>
                                                    <option value="Liechtenstein">Liechtenstein</option>
                                                    <option value="Lithuania">Lithuania</option>
                                                    <option value="Luxembourg">Luxembourg</option>
                                                    <option value="Macau">Macau</option>
                                                    <option value="Macedonia">Macedonia</option>
                                                    <option value="Madagascar">Madagascar</option>
                                                    <option value="Malaysia">Malaysia</option>
                                                    <option value="Malawi">Malawi</option>
                                                    <option value="Maldives">Maldives</option>
                                                    <option value="Mali">Mali</option>
                                                    <option value="Malta">Malta</option>
                                                    <option value="Marshall Islands">Marshall Islands</option>
                                                    <option value="Martinique">Martinique</option>
                                                    <option value="Mauritania">Mauritania</option>
                                                    <option value="Mauritius">Mauritius</option>
                                                    <option value="Mayotte">Mayotte</option>
                                                    <option value="Mexico">Mexico</option>
                                                    <option value="Midway Islands">Midway Islands</option>
                                                    <option value="Moldova">Moldova</option>
                                                    <option value="Monaco">Monaco</option>
                                                    <option value="Mongolia">Mongolia</option>
                                                    <option value="Montserrat">Montserrat</option>
                                                    <option value="Morocco">Morocco</option>
                                                    <option value="Mozambique">Mozambique</option>
                                                    <option value="Myanmar">Myanmar</option>
                                                    <option value="Nambia">Nambia</option>
                                                    <option value="Nauru">Nauru</option>
                                                    <option value="Nepal">Nepal</option>
                                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                    <option value="Nevis">Nevis</option>
                                                    <option value="New Caledonia">New Caledonia</option>
                                                    <option value="New Zealand">New Zealand</option>
                                                    <option value="Nicaragua">Nicaragua</option>
                                                    <option value="Niger">Niger</option>
                                                    <option value="Nigeria">Nigeria</option>
                                                    <option value="Niue">Niue</option>
                                                    <option value="Norfolk Island">Norfolk Island</option>
                                                    <option value="Norway">Norway</option>
                                                    <option value="Oman">Oman</option>
                                                    <option value="Pakistan">Pakistan</option>
                                                    <option value="Palau Island">Palau Island</option>
                                                    <option value="Palestine">Palestine</option>
                                                    <option value="Panama">Panama</option>
                                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                                    <option value="Paraguay">Paraguay</option>
                                                    <option value="Peru">Peru</option>
                                                    <option value="Phillipines">Philippines</option>
                                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                                    <option value="Poland">Poland</option>
                                                    <option value="Portugal">Portugal</option>
                                                    <option value="Puerto Rico">Puerto Rico</option>
                                                    <option value="Qatar">Qatar</option>
                                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                                    <option value="Reunion">Reunion</option>
                                                    <option value="Romania">Romania</option>
                                                    <option value="Russia">Russia</option>
                                                    <option value="Rwanda">Rwanda</option>
                                                    <option value="St Barthelemy">St Barthelemy</option>
                                                    <option value="St Eustatius">St Eustatius</option>
                                                    <option value="St Helena">St Helena</option>
                                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                    <option value="St Lucia">St Lucia</option>
                                                    <option value="St Maarten">St Maarten</option>
                                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                                    <option value="Saipan">Saipan</option>
                                                    <option value="Samoa">Samoa</option>
                                                    <option value="Samoa American">Samoa American</option>
                                                    <option value="San Marino">San Marino</option>
                                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                                    <option value="Senegal">Senegal</option>
                                                    <option value="Seychelles">Seychelles</option>
                                                    <option value="Sierra Leone">Sierra Leone</option>
                                                    <option value="Singapore">Singapore</option>
                                                    <option value="Slovakia">Slovakia</option>
                                                    <option value="Slovenia">Slovenia</option>
                                                    <option value="Solomon Islands">Solomon Islands</option>
                                                    <option value="Somalia">Somalia</option>
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="Spain">Spain</option>
                                                    <option value="Sri Lanka">Sri Lanka</option>
                                                    <option value="Sudan">Sudan</option>
                                                    <option value="Suriname">Suriname</option>
                                                    <option value="Swaziland">Swaziland</option>
                                                    <option value="Sweden">Sweden</option>
                                                    <option value="Switzerland">Switzerland</option>
                                                    <option value="Syria">Syria</option>
                                                    <option value="Tahiti">Tahiti</option>
                                                    <option value="Taiwan">Taiwan</option>
                                                    <option value="Tajikistan">Tajikistan</option>
                                                    <option value="Tanzania">Tanzania</option>
                                                    <option value="Thailand">Thailand</option>
                                                    <option value="Togo">Togo</option>
                                                    <option value="Tokelau">Tokelau</option>
                                                    <option value="Tonga">Tonga</option>
                                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                                    <option value="Tunisia">Tunisia</option>
                                                    <option value="Turkey">Turkey</option>
                                                    <option value="Turkmenistan">Turkmenistan</option>
                                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                                    <option value="Tuvalu">Tuvalu</option>
                                                    <option value="Uganda">Uganda</option>
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="Ukraine">Ukraine</option>
                                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                                    <option value="United States of America">United States of America</option>
                                                    <option value="Uraguay">Uruguay</option>
                                                    <option value="Uzbekistan">Uzbekistan</option>
                                                    <option value="Vanuatu">Vanuatu</option>
                                                    <option value="Vatican City State">Vatican City State</option>
                                                    <option value="Venezuela">Venezuela</option>
                                                    <option value="Vietnam">Vietnam</option>
                                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                    <option value="Wake Island">Wake Island</option>
                                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                                    <option value="Yemen">Yemen</option>
                                                    <option value="Zaire">Zaire</option>
                                                    <option value="Zambia">Zambia</option>
                                                    <option value="Zimbabwe">Zimbabwe</option>
                                                </select>
                                            </div>                                  
                                        </div>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="service-address">Service Address*</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Address</span>
                                                </div>
                                                <input type="text" name="service-address" id="service-address" placeholder="eg. 24, Mizzi Street" class="form-control">
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="address-na" value=""> Tick if address is not applicable</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="service-price">Price*</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">€</span>
                                                </div>
                                                <input type="text" name="service-price" id="service-price" placeholder="eg. 2.50" class="form-control" aria-label="Amount" required>
                                            </div>                                        
                                        </div>
                                        <div class="form-group">
                                            <label for="service-images">Upload Image*</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" name="service-images" class="custom-file-input" id="file1" aria-describedby="inputGroupFileAddon01" required/>
                                                    <label class="custom-file-label" for="file1">Choose file</label>
                                                </div>
                                            </div>
                                            <p style="font-style: italic;" id="uploaded-file-name"></p>
                                        </div>
                                        <br>
                                        <p class="lead"><b>Contact Details</b></p>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="service-email">Email*</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Email</span>
                                                </div>
                                                <input type="email" name="service-email" id="service-email" placeholder="eg. email@example.com" class="form-control" required>
                                            </div>                                        
                                        </div>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="service-mobile">Mobile Number*</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Mobile Number</span>
                                                </div>
                                                <input type="tel" name="service-mobile" id="service-mobile" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" placeholder="eg. 356-9999-9999" class="form-control" required>
                                            </div>                                        
                                        </div>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="service-tel">Telephone Number*</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Telephone Number</span>
                                                </div>
                                                <input type="tel" name="service-tel" id="service-tel" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" placeholder="eg. 356-2999-9999" class="form-control" required>
                                            </div>                                        
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <div class="custom-control custom-switch ml-n3">
                                                    <input name="is-service-premium" type="checkbox" class="custom-control-input" id="customSwitch1" value="1">
                                                    <label class="custom-control-label" for="customSwitch1"><b>Promote My Listing</b></label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" name="add-listing" class="btn btn-primary shadow-lg pull-right">Add Listing</button>
                                    </form>
                                </div>
                            </div>

                            <!-- edit listings panel -->
                            <div class="tab-pane fade" id="v-pills-edit" role="tabpanel" aria-labelledby="v-pills-edit-tab">
                                <div class="jumbotron shadow-lg pt-4">
                                    <p class="lead"><b>Edit/Delete Listing</b></p>
                                    <?php
                                        $userId = $_SESSION['userId'];
                                        $servicesNum = 0;
                                        $getUserListings = "SELECT * FROM servicetable WHERE userId='$userId'";
                                        $statement = mysqli_stmt_init($db);

                                        //sql fails if user does not have any services in the service table, so they are informed they have 0 listings
                                        if(!mysqli_stmt_prepare($statement, $getUserListings)){
                                            echo '<p style="font-size: 15px;">You currently have <b>0</b> listings</p>';
                                        }
                                        else{
                                            mysqli_stmt_execute($statement);
                                            $result = mysqli_stmt_get_result($statement);
                                                
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ++$servicesNum;
                                            }
                                            echo '<p style="font-size: 15px;">You currently have <b>'.$servicesNum.'</b> listings</p>';
                                        }
                                    ?>
                                    <p style='font-style: italic;'>Editing a listing is not yet implemented</p>
                                    <hr class="my-4">
                                    <div class="container" style="margin-top:0;">
                                        <?php 
                                            $servicePrint = "";
                                            $userId = $_SESSION['userId'];
                                            $getUserListings = "SELECT * FROM servicetable WHERE userId='$userId'";
                                            $statement = mysqli_stmt_init($db);

                                            if(mysqli_stmt_prepare($statement, $getUserListings)){
                                                mysqli_stmt_execute($statement);
                                                $result = mysqli_stmt_get_result($statement);
                    
                                                //all services belong to the selected category are added to $servicePrint
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $servicePrint = $servicePrint.'<div class="row align-items-center user-listing" style="margin-top:50px;">
                                                        <div class="col-md-4 edit-listing-image">
                                                            <div class="product-top shadow-lg">
                                                                <img src="'.$row['images'].'" onerror="this.src=\'images/nia.png\';">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                        <h3 style="margin-top: 10px;">'.$row['title'].'</h3>
                                                        <p>'.$row['description'].'</p>
                                                        <p><b>Category: </b>'.$row['category'].'</p>
                                                        <p><b>Price: </b>€'.$row['price'].'</p>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class = "row align-items-center">
                                                        <div class="col-md-12">
                                                            <form method="post">
                                                                <button type="submit" name="edit-listing" class="btn btn-primary shadow-lg pull-right edit-buttons" value="'.$row['id'].'" style="margin-top: 10px;">Edit Listing</button>
                                                                <button type="submit" name="delete-listing-btn-'.$row['id'].'" class="btn btn-primary shadow-lg pull-right edit-buttons" value="'.$row['id'].'" style="margin-top: 10px;">Delete Listing</button>
                                                            </form>
                                                        </div>
                                                    </div>';
                                                }
                                                echo $servicePrint;
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- settings panel -->
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <div class="jumbotron shadow-lg pt-4">
                                    <p class="lead"><b>Your Profile</b></p>
                                    <p style='font-style: italic;'>Not Yet Implemented</p>
                                    <hr class="my-4">

                                    <form class="needs-validation" novalidate>
                                        <div class="form-row">
                                            <div class="col-md-5 mb-3">
                                                <label for="validationTooltip01">Name*</label>
                                                <input type="text" class="form-control" id="validationTooltip01" placeholder="John" required>
                                            </div>
                                            <div class="col-md-5 mb-3">
                                                <label for="validationTooltip02">Surname*</label>
                                                <input type="text" class="form-control" id="validationTooltip02" placeholder="Smith" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationTooltip03">Email</label>
                                                <input type="email" class="form-control" id="validationTooltip03" placeholder="john.smith@example.com" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="birthDate">Date of Birth</label>
                                                <input type="text" id="birthDate" class="form-control" placeholder="27/02/2020" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                        <div class="col-md-5 mb-3">
                                            <label for="user-bio">Bio</label>
                                            <textarea name="user-bio" class="form-control" id="user-bio" style="resize: none; height: 100px;" placeholder="Bio"></textarea>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label for="user-desc">Description</label>
                                            <textarea name="user-desc" class="form-control" id="user-desc" style="resize: none; height: 100px;" placeholder="Description"></textarea>
                                        </div></div>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="user-country">Country*</label>
                                            <div class="input-group mb-3">
                                                <select id="user-country" class="custom-select" name="user-country" required>
                                                    <option value="Afganistan">Afghanistan</option>
                                                    <option value="Albania">Albania</option>
                                                    <option value="Algeria">Algeria</option>
                                                    <option value="American Samoa">American Samoa</option>
                                                    <option value="Andorra">Andorra</option>
                                                    <option value="Angola">Angola</option>
                                                    <option value="Anguilla">Anguilla</option>
                                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                                    <option value="Argentina">Argentina</option>
                                                    <option value="Armenia">Armenia</option>
                                                    <option value="Aruba">Aruba</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="Austria">Austria</option>
                                                    <option value="Azerbaijan">Azerbaijan</option>
                                                    <option value="Bahamas">Bahamas</option>
                                                    <option value="Bahrain">Bahrain</option>
                                                    <option value="Bangladesh">Bangladesh</option>
                                                    <option value="Barbados">Barbados</option>
                                                    <option value="Belarus">Belarus</option>
                                                    <option value="Belgium">Belgium</option>
                                                    <option value="Belize">Belize</option>
                                                    <option value="Benin">Benin</option>
                                                    <option value="Bermuda">Bermuda</option>
                                                    <option value="Bhutan">Bhutan</option>
                                                    <option value="Bolivia">Bolivia</option>
                                                    <option value="Bonaire">Bonaire</option>
                                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Brazil">Brazil</option>
                                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                                    <option value="Brunei">Brunei</option>
                                                    <option value="Bulgaria">Bulgaria</option>
                                                    <option value="Burkina Faso">Burkina Faso</option>
                                                    <option value="Burundi">Burundi</option>
                                                    <option value="Cambodia">Cambodia</option>
                                                    <option value="Cameroon">Cameroon</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="Canary Islands">Canary Islands</option>
                                                    <option value="Cape Verde">Cape Verde</option>
                                                    <option value="Cayman Islands">Cayman Islands</option>
                                                    <option value="Central African Republic">Central African Republic</option>
                                                    <option value="Chad">Chad</option>
                                                    <option value="Channel Islands">Channel Islands</option>
                                                    <option value="Chile">Chile</option>
                                                    <option value="China">China</option>
                                                    <option value="Christmas Island">Christmas Island</option>
                                                    <option value="Cocos Island">Cocos Island</option>
                                                    <option value="Colombia">Colombia</option>
                                                    <option value="Comoros">Comoros</option>
                                                    <option value="Congo">Congo</option>
                                                    <option value="Cook Islands">Cook Islands</option>
                                                    <option value="Costa Rica">Costa Rica</option>
                                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                                    <option value="Croatia">Croatia</option>
                                                    <option value="Cuba">Cuba</option>
                                                    <option value="Curaco">Curacao</option>
                                                    <option value="Cyprus">Cyprus</option>
                                                    <option value="Czech Republic">Czech Republic</option>
                                                    <option value="Denmark">Denmark</option>
                                                    <option value="Djibouti">Djibouti</option>
                                                    <option value="Dominica">Dominica</option>
                                                    <option value="Dominican Republic">Dominican Republic</option>
                                                    <option value="East Timor">East Timor</option>
                                                    <option value="Ecuador">Ecuador</option>
                                                    <option value="Egypt">Egypt</option>
                                                    <option value="El Salvador">El Salvador</option>
                                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                    <option value="Eritrea">Eritrea</option>
                                                    <option value="Estonia">Estonia</option>
                                                    <option value="Ethiopia">Ethiopia</option>
                                                    <option value="Falkland Islands">Falkland Islands</option>
                                                    <option value="Faroe Islands">Faroe Islands</option>
                                                    <option value="Fiji">Fiji</option>
                                                    <option value="Finland">Finland</option>
                                                    <option value="France">France</option>
                                                    <option value="French Guiana">French Guiana</option>
                                                    <option value="French Polynesia">French Polynesia</option>
                                                    <option value="French Southern Ter">French Southern Ter</option>
                                                    <option value="Gabon">Gabon</option>
                                                    <option value="Gambia">Gambia</option>
                                                    <option value="Georgia">Georgia</option>
                                                    <option value="Germany">Germany</option>
                                                    <option value="Ghana">Ghana</option>
                                                    <option value="Gibraltar">Gibraltar</option>
                                                    <option value="Great Britain">Great Britain</option>
                                                    <option value="Greece">Greece</option>
                                                    <option value="Greenland">Greenland</option>
                                                    <option value="Grenada">Grenada</option>
                                                    <option value="Guadeloupe">Guadeloupe</option>
                                                    <option value="Guam">Guam</option>
                                                    <option value="Guatemala">Guatemala</option>
                                                    <option value="Guinea">Guinea</option>
                                                    <option value="Guyana">Guyana</option>
                                                    <option value="Haiti">Haiti</option>
                                                    <option value="Hawaii">Hawaii</option>
                                                    <option value="Honduras">Honduras</option>
                                                    <option value="Hong Kong">Hong Kong</option>
                                                    <option value="Hungary">Hungary</option>
                                                    <option value="Iceland">Iceland</option>
                                                    <option value="Indonesia">Indonesia</option>
                                                    <option value="India">India</option>
                                                    <option value="Iran">Iran</option>
                                                    <option value="Iraq">Iraq</option>
                                                    <option value="Ireland">Ireland</option>
                                                    <option value="Isle of Man">Isle of Man</option>
                                                    <option value="Israel">Israel</option>
                                                    <option value="Italy">Italy</option>
                                                    <option value="Jamaica">Jamaica</option>
                                                    <option value="Japan">Japan</option>
                                                    <option value="Jordan">Jordan</option>
                                                    <option value="Kazakhstan">Kazakhstan</option>
                                                    <option value="Kenya">Kenya</option>
                                                    <option value="Kiribati">Kiribati</option>
                                                    <option value="Korea North">Korea North</option>
                                                    <option value="Korea Sout">Korea South</option>
                                                    <option value="Kuwait">Kuwait</option>
                                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                    <option value="Laos">Laos</option>
                                                    <option value="Latvia">Latvia</option>
                                                    <option value="Lebanon">Lebanon</option>
                                                    <option value="Lesotho">Lesotho</option>
                                                    <option value="Liberia">Liberia</option>
                                                    <option value="Libya">Libya</option>
                                                    <option value="Liechtenstein">Liechtenstein</option>
                                                    <option value="Lithuania">Lithuania</option>
                                                    <option value="Luxembourg">Luxembourg</option>
                                                    <option value="Macau">Macau</option>
                                                    <option value="Macedonia">Macedonia</option>
                                                    <option value="Madagascar">Madagascar</option>
                                                    <option value="Malaysia">Malaysia</option>
                                                    <option value="Malawi">Malawi</option>
                                                    <option value="Maldives">Maldives</option>
                                                    <option value="Mali">Mali</option>
                                                    <option selected="selected" value="Malta">Malta</option>
                                                    <option value="Marshall Islands">Marshall Islands</option>
                                                    <option value="Martinique">Martinique</option>
                                                    <option value="Mauritania">Mauritania</option>
                                                    <option value="Mauritius">Mauritius</option>
                                                    <option value="Mayotte">Mayotte</option>
                                                    <option value="Mexico">Mexico</option>
                                                    <option value="Midway Islands">Midway Islands</option>
                                                    <option value="Moldova">Moldova</option>
                                                    <option value="Monaco">Monaco</option>
                                                    <option value="Mongolia">Mongolia</option>
                                                    <option value="Montserrat">Montserrat</option>
                                                    <option value="Morocco">Morocco</option>
                                                    <option value="Mozambique">Mozambique</option>
                                                    <option value="Myanmar">Myanmar</option>
                                                    <option value="Nambia">Nambia</option>
                                                    <option value="Nauru">Nauru</option>
                                                    <option value="Nepal">Nepal</option>
                                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                    <option value="Nevis">Nevis</option>
                                                    <option value="New Caledonia">New Caledonia</option>
                                                    <option value="New Zealand">New Zealand</option>
                                                    <option value="Nicaragua">Nicaragua</option>
                                                    <option value="Niger">Niger</option>
                                                    <option value="Nigeria">Nigeria</option>
                                                    <option value="Niue">Niue</option>
                                                    <option value="Norfolk Island">Norfolk Island</option>
                                                    <option value="Norway">Norway</option>
                                                    <option value="Oman">Oman</option>
                                                    <option value="Pakistan">Pakistan</option>
                                                    <option value="Palau Island">Palau Island</option>
                                                    <option value="Palestine">Palestine</option>
                                                    <option value="Panama">Panama</option>
                                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                                    <option value="Paraguay">Paraguay</option>
                                                    <option value="Peru">Peru</option>
                                                    <option value="Phillipines">Philippines</option>
                                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                                    <option value="Poland">Poland</option>
                                                    <option value="Portugal">Portugal</option>
                                                    <option value="Puerto Rico">Puerto Rico</option>
                                                    <option value="Qatar">Qatar</option>
                                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                                    <option value="Reunion">Reunion</option>
                                                    <option value="Romania">Romania</option>
                                                    <option value="Russia">Russia</option>
                                                    <option value="Rwanda">Rwanda</option>
                                                    <option value="St Barthelemy">St Barthelemy</option>
                                                    <option value="St Eustatius">St Eustatius</option>
                                                    <option value="St Helena">St Helena</option>
                                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                    <option value="St Lucia">St Lucia</option>
                                                    <option value="St Maarten">St Maarten</option>
                                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                                    <option value="Saipan">Saipan</option>
                                                    <option value="Samoa">Samoa</option>
                                                    <option value="Samoa American">Samoa American</option>
                                                    <option value="San Marino">San Marino</option>
                                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                                    <option value="Senegal">Senegal</option>
                                                    <option value="Seychelles">Seychelles</option>
                                                    <option value="Sierra Leone">Sierra Leone</option>
                                                    <option value="Singapore">Singapore</option>
                                                    <option value="Slovakia">Slovakia</option>
                                                    <option value="Slovenia">Slovenia</option>
                                                    <option value="Solomon Islands">Solomon Islands</option>
                                                    <option value="Somalia">Somalia</option>
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="Spain">Spain</option>
                                                    <option value="Sri Lanka">Sri Lanka</option>
                                                    <option value="Sudan">Sudan</option>
                                                    <option value="Suriname">Suriname</option>
                                                    <option value="Swaziland">Swaziland</option>
                                                    <option value="Sweden">Sweden</option>
                                                    <option value="Switzerland">Switzerland</option>
                                                    <option value="Syria">Syria</option>
                                                    <option value="Tahiti">Tahiti</option>
                                                    <option value="Taiwan">Taiwan</option>
                                                    <option value="Tajikistan">Tajikistan</option>
                                                    <option value="Tanzania">Tanzania</option>
                                                    <option value="Thailand">Thailand</option>
                                                    <option value="Togo">Togo</option>
                                                    <option value="Tokelau">Tokelau</option>
                                                    <option value="Tonga">Tonga</option>
                                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                                    <option value="Tunisia">Tunisia</option>
                                                    <option value="Turkey">Turkey</option>
                                                    <option value="Turkmenistan">Turkmenistan</option>
                                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                                    <option value="Tuvalu">Tuvalu</option>
                                                    <option value="Uganda">Uganda</option>
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="Ukraine">Ukraine</option>
                                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                                    <option value="United States of America">United States of America</option>
                                                    <option value="Uraguay">Uruguay</option>
                                                    <option value="Uzbekistan">Uzbekistan</option>
                                                    <option value="Vanuatu">Vanuatu</option>
                                                    <option value="Vatican City State">Vatican City State</option>
                                                    <option value="Venezuela">Venezuela</option>
                                                    <option value="Vietnam">Vietnam</option>
                                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                    <option value="Wake Island">Wake Island</option>
                                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                                    <option value="Yemen">Yemen</option>
                                                    <option value="Zaire">Zaire</option>
                                                    <option value="Zambia">Zambia</option>
                                                    <option value="Zimbabwe">Zimbabwe</option>
                                                </select>
                                            </div>                                  
                                        </div>
                                        <div class="form-group col-md-5 pl-0">
                                            <label for="user-address">Address</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="user-address" id="user-address" placeholder="eg. 24, Mizzi Street" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-5 mb-3">
                                                <label for="user-mob">Mobile</label>
                                                <input type="text" class="form-control" id="user-mob" placeholder="eg. 356-9999-9999">
                                            </div>
                                            <div class="col-md-5 mb-3">
                                                <label for="user-tel">Telephone</label>
                                                <input type="text" class="form-control" id="user-tel" placeholder="eg. 356-2999-9999">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="exampleDropdownFormPassword1">New Password</label>
                                                <input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                                                <div class="invalid-tooltip">
                                                    Please enter a password.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="exampleDropdownFormPassword2">Confirm New Password</label>
                                                <input type="password" class="form-control" id="exampleDropdownFormPassword2" placeholder="Confirm Password">
                                                <div class="invalid-tooltip">
                                                    Passwords dont match.
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary shadow-lg pull-right">Apply Changes</button>
                                        <button type="submit" name="delete-account-btn" class="btn btn-primary shadow-lg pull-right" style="margin-right: 20px;" onclick="delAccount()">Delete Account</button>
                                    </form>
                                </div>
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


        <!-- If a "delete listing" button is pressed, a javascript prompt will be shown to the user. 
             Then, a statement in server.php will delete the appropriate listing. -->
        <?php
            $delServiceID = 0;
            while($delServiceID < 200){
                $deleteButton = "delete-listing-btn-".$delServiceID;
                if(isset($_POST[$deleteButton])){
                    echo '<script type="text/javascript">
                        var choice = confirm("Are you sure you want to delete this service?");
                        if(choice == true){
                            window.location.href="myaccount.php?deleteservice='.$delServiceID.'";
                        }
                    </script>';
                }

                $delServiceID++;
            }
        ?>

        <!-- Placed here since JavaScript should ideally not be loaded before page content -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script>
            var uploaded_files = document.getElementById('file1');

            //used to display file name when image is uploaded
            uploaded_files.addEventListener('change', function(){
                document.getElementById('uploaded-file-name').textContent = "File name: " + uploaded_files.files[0].name;
            } );
        </script>
    </body>
</html>