<?php

/**
 * Name: Jerome Acosta
 * Course Code: SYST10199
 * Date: April 6, 2022
 */


    // Import Database Credentials
    require("connect.php");

    $errors="";
    $output="";

    define("PHONE_LENGTH", 13);
    define("MAX_FIRST_NAME_LENGTH", 20);
    define("MAX_LAST_NAME_LENGTH", 30);
    define("MAXIMUM_EMAIL_LENGTH", 255);

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['input_email'];
        $fname = $_POST['input_fname'];
        $lname = $_POST['input_lname'];
        $phone = $_POST['input_phone'];
        $phone_ext = $_POST['input_phone_ext'];

        //Validation
        $valid = true;
        if(!isset($email) || trim($email) == ""){
          $errors.="Email must not be empty.<br>";
          $valid = false;
        }
        elseif (!preg_match("/@/i",$email)){
          $errors.='Email is missing an "@" symbol.<br>';
          $valid = false;
        }
        elseif (strlen($email) > MAXIMUM_EMAIL_LENGTH){
          $errors.='Email must be less than ' + MAXIMUM_EMAIL_LENGTH + ".<br>";
          $valid = false;
        }

        if(!isset($fname) || trim($fname) == ""){
          $errors.="First Name must not be empty.<br>";
          $valid = false;
        }
        elseif (strlen($fname) > MAX_FIRST_NAME_LENGTH){
          $errors.='First Name must be less than ' + MAX_FIRST_NAME_LENGTH + ".<br>";
           $valid = false;
        }
        
        if(!isset($lname) || trim($lname) == ""){
          $errors.="Last Name must not be empty.<br>";
          $valid = false;
        }
        elseif (strlen($lname) > MAX_LAST_NAME_LENGTH){
          $errors.='Last Name must be less than ' + MAX_LAST_NAME_LENGTH + ".<br>";
          $valid = false;
        }

        if(!isset($phone) || trim($phone) == ""){
          $errors.="Phone Number must not be empty.<br>";
          $valid = false;
        }
        elseif (strlen($phone) != PHONE_LENGTH){
          $errors.="Phone Number must be written as follows (123)456-7890.<br>";
          $valid = false;
        }
        else {
          if (isset($phone_ext) && trim($phone_ext) != ""){
            $phone.=" ext. $phone_ext";
          }
        }

        if ($valid){
          try{
            // 1. Open connection with db
            $dbConn = new PDO ("mysql:host=$hostname;dbname=$dbname", "$user", "$password");
            
            // //Check if connected to db
            // echo "Connected to db";

            // 2. Write a command
            $command = "INSERT INTO contacts(email_address, first_name, last_name, phone_number) VALUES (?, ?, ?, ?);";

            // 3. Prepare Statement
            $stmt = $dbConn->prepare($command);

            // 4. Execute Prepared Statement
            $execOK = $stmt->execute(array($email, $fname, $lname, $phone));

            if ($execOK){
                header('Location: ass4output.php');
            }
            else {
                $errors .= "Error executing query.";
            }
          }
          catch(PDOException $e){
              $errors.= 'Connection Error '.$e->getMessage();
          }

        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Menu</title>
  <link rel="stylesheet" href="./css/vertical-menue.css">
</head>
<body>
  <header>
    <h1>Sys10199: Assignment 4-Contacts</h1>
  </header>
  <div class="container">
    <nav>
      <ul>
        <li><a href="./ass4output.php">View Contacts</a></li>
        <li><a href="./ass4input.php">Add Contact</a></li>
      </ul>
    </nav>
    <section class="main">
        <h2 id="errors"><?=$errors?></h2>
      <article>
        <h1>Add Contact - Enter the Following Data:</h1>
        <div class=form-container>
          <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
              <div>
                  <label for="">Email Address:<span class="asterisk">*</span> </label>
                  <input required type="text" name="input_email" id="input_email" value="<?php  if($_SERVER['REQUEST_METHOD'] == "POST"){echo $email;}?>">
              </div>
              <div>
                  <label for="">First Name:<span class="asterisk">*</span> </label>
                  <input required type="text" name="input_fname" id="input_fname" value="<?php  if($_SERVER['REQUEST_METHOD'] == "POST"){echo $fname;}?>">
              </div>
              <div>
                  <label for="">Last Name:<span class="asterisk">*</span> </label>
                  <input required type="text" name="input_lname" id="input_lname" value="<?php  if($_SERVER['REQUEST_METHOD'] == "POST"){echo $lname;}?>">
              </div>
              <div>
                  <label for="">Phone Number:<span class="asterisk">*</span> </label>
                  <input required type="text" name="input_phone" id="input_phone" value="<?php  if($_SERVER['REQUEST_METHOD'] == "POST"){echo $phone;}?>">
              </div>
              <div>
                  <label for="">Phone Extension: </label>
                  <input type="text" name="input_phone_ext" id="input_phone_ext" value="<?php  if($_SERVER['REQUEST_METHOD'] == "POST"){echo $phone_ext;}?>">
              </div>
  
              <div>
                  <input type="submit" value="Add">
                  <input type="reset" value="Clear">
              </div>
  
          </form>
        </div>
      </article>
    </section>
  </div>
</body>

</html>