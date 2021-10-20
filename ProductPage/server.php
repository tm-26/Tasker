<?php
    //Connecting to taskerdb
    $db = mysqli_connect("localhost", "taskeradmin", "letmeinplease", "taskerdb") or die("Connection to database failed". mysqli_connect_error());

    //if the button in registration is pressed
    if (isset($_POST['register-button'])){
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $dob = date('Y-m-d', strtotime($_POST['dateOfBirth']));
        $country = $_POST['user-country'];
        $pswd = $_POST['password'];
        $pswdRepeat = $_POST['passwordRepeat'];

        $orgJoinDate = date('Y-m-d');
        $joinDate = date('Y-m-d', strtotime($orgJoinDate));

        $address = "N.A";
        $likes = json_encode(array("Carpentry" => 0));

        //validate password strength
        $uppercase = preg_match('@[A-Z]@', $pswd);
        $lowercase = preg_match('@[a-z]@', $pswd);
        $number    = preg_match('@[0-9]@', $pswd);

        //checking if any fields are empty
        if(empty($firstName) || empty($lastName) || empty($email) || empty($dob) || empty($pswd) || empty($pswdRepeat)){
            header("Location: register.php?error=emptyfields");
            exit();
        }
        //checking if email, name and surname are valid
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z]*$/", $firstName) && !preg_match("/^[a-zA-Z]*$/", $lastName)){
            header("Location: register.php?error=invalidmailnamesurname");
        }
        //checking if name and surname are valid
        else if(!preg_match("/^[a-zA-Z]*$/", $firstName) || !preg_match("/^[a-zA-Z]*$/", $lastName)){
            header("Location: register.php?error=invalidnamesurname");
        }
        //checking if email is valid
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("Location: register.php?error=invalidmail");
            exit();
        }
        //checking if password has all the set requirements
        else if(!$uppercase || !$lowercase || !$number || strlen($pswd) < 8) {
            header("Location: register.php?error=invalidpassword");
            exit();
        }
        //checking if passwords match
        else if($pswd !== $pswdRepeat){
            header("Location: register.php?error=passwordsnotmatch");
            exit();
        }
        //otherwise, information is added to db
        else{
            $duplicateEmailCheck = "SELECT * FROM `usertable` WHERE `email`=? LIMIT 1";
            $checkStatement = mysqli_stmt_init($db);
            if(!mysqli_stmt_prepare($checkStatement, $duplicateEmailCheck)){
                header("Location: register.php?error=sqlerror");
                exit();
            }
            else{
                mysqli_stmt_bind_param($checkStatement, "s", $email);
                mysqli_stmt_execute($checkStatement);
                mysqli_stmt_store_result($checkStatement);
                
                //if email is already found in database
                if(mysqli_stmt_num_rows($checkStatement) > 0){
                    header("Location: register.php?error=mailinuse");
                    exit();
                }
                //if not, user is added to database
                else{
                    $password = password_hash($pswd, PASSWORD_DEFAULT);
                    $query = "INSERT INTO `usertable` (`address`, `country`, `dateOfBirth`, `email`, `firstName`, `joinDate`, `lastName`, `likes`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
                    $statement = mysqli_stmt_init($db);
                    if(!mysqli_stmt_prepare($statement, $query)){
                        header("Location: register.php?error=sqlerror");
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($statement, "sssssssss", $address, $country, $dob, $email, $firstName, $joinDate, $lastName, $likes, $password);
                        mysqli_stmt_execute($statement);
                        header("Location: signin.php?account=created");
                        exit();
                    }
                }
            }
        }
        mysqli_stmt_close($checkStatement);
        mysqli_stmt_close($statement);
        mysqli_close($db);
    }

    //if the button in signin.php is pressed, the below will execute
    if (isset($_POST['signin-submit'])){
        $mail = $_POST['signin-email'];
        $pswd = $_POST['signin-password'];

        //checking if either field is empty
        if (empty($mail) || empty($pswd)){
            header("Location: signin.php?error=emptyfields");
            exit();
        }
        else{
            $sqlMailCheck = "SELECT * FROM `userTable` where `email`=? LIMIT 1;";
            $statement = mysqli_stmt_init($db);

            //checking if sql is correct
            if(!mysqli_stmt_prepare($statement, $sqlMailCheck)){
                header("Location: signin.php?error=sqlerror");
                exit();
            }
            else{
                mysqli_stmt_bind_param($statement, 's', $mail);
                mysqli_stmt_execute($statement);
                $result = mysqli_stmt_get_result($statement);

                //if a result is returned, check if  passwords match
                if ($row = mysqli_fetch_assoc($result)) {
                    $pswdCheck = password_verify($pswd, $row['password']);
                    if ($pswdCheck == false){
                        header("Location: signin.php?error=wrongpswd");
                        exit();
                    }
                    else if($pswdCheck == true){
                        session_start();
                        $_SESSION['userId'] = $row['id'];

                        header("Location: home.php?signin=success");
                        exit();
                    }
                    //in case a boolean is not returned
                    else{
                        if ($pswdCheck == false){
                            header("Location: signin.php?error=wrongpswd");
                            exit();
                        }
                    }
                }
                //if no result is returned, user does not exist
                else{
                    header("Location: signin.php?error=usernotexist");
                    exit();
                }
            }
        }
        mysqli_stmt_close($statement);
        mysqli_close($db);
    }

    //if signout button is pressed on any page
    if(isset($_POST['signout-button'])){
        session_start();
        session_unset();
        session_destroy();
        header("Location: home.php?signout=success");
    }

    //if user adds a service
    if (isset($_POST['add-listing'])){
        //used in order to upload image to a location on the server
        $file_name = $_FILES['service-images']['name'];
        $file_size =$_FILES['service-images']['size'];
        $file_tmp =$_FILES['service-images']['tmp_name'];
        $file_type=$_FILES['service-images']['type'];
        $file_ext=strtolower(end(explode('.',$_FILES['service-images']['name'])));
        $extensions= array("jpeg","jpg","png");
        $imagePath = "images/".$file_name;

        $userId = $_SESSION['userId'];

        $categ = $_POST['service-category'];
        $title = $_POST['service-title'];
        $desc = $_POST['service-desc'];
        $price = $_POST['service-price'];
        $country = $_POST['service-country'];
        
        $email = $_POST['service-email'];
        $mobile = $_POST['service-mobile'];
        $tel = $_POST['service-tel'];
        $premium = $_POST['is-service-premium'];

        //if isset, premium is checked and its value is 1
        //otherwise, it is not set so its value should be changed to 0 to reflect that it is not a premium service
        if(!isset($premium)){
            $premium = 0;
        }

        //depending on whether the user ticked that an address is not applicable to their service, $location is set
        if(!isset($_POST['address-na']) && !empty($_POST['service-address'])){
            $location = $_POST['service-address'];
        }
        else if((!isset($_POST['address-na']) && empty($_POST['service-address'])) || (isset($_POST['address-na']) && !empty($_POST['service-address']))){
            header("Location: myaccount.php?error=invalidaddress");
            exit();
        }
        else if(isset($_POST['address-na']) && empty($_POST['service-address'])){
            $location = "N.A";
        }

        //checking if entered fields are empty
        if (empty($categ) || empty($title) || empty($desc) || empty($price) || empty($country) || empty($email) || empty($mobile) || empty($tel)){
            header("Location: myaccount.php?error=emptyfields");
            exit();
        }
        //checking if file size is greater than 10000kb/10mb
        else if($file_size > 10000000){
            header("Location: myaccount.php?error=filetoolarge");
            exit();
        }
        //checking if file extension is valid
        else if(in_array($file_ext,$extensions) === false) {
            header("Location: myaccount.php?error=imagetype");
            exit();
        }
        //checking if entered email is valid
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("Location: myaccount.php?error=invalidmail");
            exit();
        }
        //checking if phone numbers are of the correct format
        else if(!preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $mobile) || !preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $tel)) {
            header("Location: myaccount.php?error=invalidphoneformat");
        }
        //checking if price is of the correct format
        else if(!preg_match("/^\d+(\.\d{2})?$/", $price)){
            header("Location: myaccount.php?error=invalidprice");
            exit();
        }
        //otherwise, service is added to db
        else{
            $insertQuery = "INSERT INTO `servicetable` (`category`, `country`, `description`, `email`, `images`, `isPremium`, `location`, `mobile`, `price`, `telephone`, `title`, `userId`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $insertStatement = mysqli_stmt_init($db);
            if(!mysqli_stmt_prepare($insertStatement, $insertQuery)){
                header("Location: myaccount.php?error=sqlerror");
                exit();
            }
            // if sql statement can be executed, service is added and the image is stored on the server
            else{
                $updateQuery = "UPDATE `categorytable` SET `numberOfServices` = `numberOfServices`+1 WHERE `name` = ?;";
                $updateStatement = mysqli_stmt_init($db);
                if(!mysqli_stmt_prepare($updateStatement, $updateQuery)){
                    header("Location: myaccount.php?error=sqlerror");
                    exit();
                }
                else{
                    //service is added
                    mysqli_stmt_bind_param($insertStatement, "sssssissdssi", $categ, $country, $desc, $email, $imagePath, $premium, $location, $mobile, $price, $tel, $title, $userId);
                    mysqli_stmt_execute($insertStatement);
                    //image is uploaded
                    move_uploaded_file($file_tmp, $imagePath);    

                    //number of services in category table is updated
                    mysqli_stmt_bind_param($updateStatement, "s", $categ);
                    mysqli_stmt_execute($updateStatement);

                    header("Location: myaccount.php?actionsuccess=create");
                    exit();
                }    
                
            }
        }
        mysqli_stmt_close($insertStatement);
        mysqli_stmt_close($updateStatement);
        mysqli_close($db);
    }

    //if user deletes a service
    if(isset($_GET['deleteservice'])){
        $delServiceID = $_GET['deleteservice'];
        $delQuery = "DELETE FROM `servicetable` WHERE `servicetable`.`id` = $delServiceID";
        $deleteStatement = mysqli_stmt_init($db);

        if(mysqli_stmt_prepare($deleteStatement, $delQuery)){
            $getServiceQuery = "SELECT * FROM servicetable WHERE id=$delServiceID";
            $serviceStatement = mysqli_stmt_init($db);

            if(mysqli_stmt_prepare($serviceStatement, $getServiceQuery)){
                mysqli_stmt_execute($serviceStatement);
                $result = mysqli_stmt_get_result($serviceStatement);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    $serviceCateg = $row['category'];
                }

                $updateQuery = "UPDATE `categorytable` SET `numberOfServices` = `numberOfServices`-1 WHERE `name` = ?;";
                $updateStatement = mysqli_stmt_init($db);
                if(mysqli_stmt_prepare($updateStatement, $updateQuery)){
                    //number of services in category table is updated
                    mysqli_stmt_bind_param($updateStatement, "s", $serviceCateg);
                    mysqli_stmt_execute($updateStatement);
                }

                //service is deleted from service table
                mysqli_stmt_execute($deleteStatement);
            }
        }
    }

    function getUser(){
        $statement = mysqli_stmt_init($GLOBALS["db"]);
        $userId = $_SESSION['userId'];                               
        mysqli_stmt_prepare($statement, "SELECT * FROM usertable WHERE id = '$userId' LIMIT 1");
        mysqli_stmt_execute($statement);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
    }

    function getServices($category){
        $statement = mysqli_stmt_init($GLOBALS["db"]);
        $allServices = array();

        if($category == null){
            mysqli_stmt_prepare($statement, "SELECT * FROM servicetable");
            mysqli_stmt_execute($statement);
            $services = mysqli_stmt_get_result($statement);
            while ($row = mysqli_fetch_array($services)){
                $allServices[] = "[" . implode(",", array($row['category'], $row['country'], $row['rating'], '['. implode(",", array($row['ageGroup0'], $row['ageGroup1'], $row['ageGroup2'], $row['ageGroup3'], $row['ageGroup4'])) . ']' , $row['isPremium'], $row['popularity'], $row['longitude'], $row['latitude'], $row['id'])) . "]";
            }
        } else {
            mysqli_stmt_prepare($statement, "SELECT * FROM `servicetable` WHERE `category`='$category'");
            mysqli_stmt_execute($statement);
            $services = mysqli_stmt_get_result($statement);
            while ($row = mysqli_fetch_array($services)){
                $allServices[] = "[" . implode(",", array($row['category'], $row['country'], $row['rating'], '['. implode(",", array($row['ageGroup0'], $row['ageGroup1'], $row['ageGroup2'], $row['ageGroup3'], $row['ageGroup4'])) . ']' , $row['isPremium'], $row['popularity'], $row['longitude'], $row['latitude'], $row['id'])) . "]";
            }
        }

        return $allServices;
    }
