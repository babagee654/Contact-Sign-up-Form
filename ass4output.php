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

    try{
        // 1. Open connection with db
        $dbConn = new PDO ("mysql:host=$hostname;dbname=$dbname", "$user", "$password");

        // If a delete button has been requested, delete the item from the table.
        if($_SERVER['REQUEST_METHOD'] == "POST"){
          if (isset($_POST['delete'])){
            $email = $_POST['delete'];
            $command = "DELETE FROM contacts WHERE email_address='$email'";
            $stmt = $dbConn->prepare($command);
            $execOK = $stmt->execute();
            $errors .= "Successfully deleted '$email'.";

            if (!$execOK){
              $errors .= "Error executing query.";
            }
          }
        }
                
        // //Check if connected to db
        // echo "Connected to db";

         // 2. Write a command
        $command = "SELECT * FROM contacts ORDER BY last_name ASC";

        // 3. Prepare Statement
        $stmt = $dbConn->prepare($command);

         // 4. Execute Prepared Statement
        $execOK = $stmt->execute();

        if ($execOK){
            while($row = $stmt->fetch()){
                $email = $row['email_address'];
                $fname = $row['first_name'];
                $lname = $row['last_name'];
                $phone = $row['phone_number'];
                $date = $row['created_on'];
                $output .= "<tr><td>$email</td> <td>$fname</td><td>$lname</td><td>$phone</td><td>$date</td><td><button type='submit' value=$email name='delete'>Delete</button></td></tr>";
            }
        }
        else {
            $errors .= "Error executing query.";
        }
    }
    catch(PDOException $e){
        $errors.= 'Connection Error '.$e->getMessage();
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
        <h2><?=$errors?></h2>
      <article>
        <h1>View Contacts Table:</h1>
        <table class="styled-table">
          <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
          <thead>
            <tr>
                <th>E-mail Address</th>
                <th>First Name</th>
                <th>Last name</th>
                <th>Phone Number</th>
                <th>Created On</th>
                <th>Delete Contact</th>
            </tr>
          </thead>
          <tbody>
            <?=$output?>
          </tbody>
          </form>
        </table>
      </article>
    </section>
  </div>
</body>

</html>