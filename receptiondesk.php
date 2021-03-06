<!-- reception.php -->

<html><head>
<title> 1A CSCI 467 Checkout - Z1853066</title></head> 
<center>
<body bgcolor='FFA07A'> 

  <?php
    try
    {
      $dsn1 = "mysql:host=courses;dbname=z1853066"; //connecting to the database
      include("pswrds.php"); //password
      $pdo1 = new PDO($dsn1, $username1, $password1); //stating the user name and password
    }

    catch(PDOexception $exception1)
    {
      echo "Database connection failed: " . $exception1->getMessage(); //if connection fails throw an error
    }

    try
    {
      $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467"; //connecting to the second database
      include("pswrds.php"); //second password
      $pdo2 = new PDO($connection2, $username2, $password2); //stating the second user name and password
    }

    catch (PDOexception $exception2)
    {
      echo "Database connection failed: " . $exception2->getMessage(); //if the connection fails, throw a second error message
    }

    if (array_key_exists('change', $_REQUEST)) //if statement for the array of values
    {
      $changenum = intval($_REQUEST['qntty']);
      $prdctnum = intval($_REQUEST['pnmbr']);
      $sqlchange = "UPDATE inventory SET quantity = $changenum WHERE productID = $prdctnum";
      $qchange = $pdo1->query($sqlchange);
    }



    echo "<h1>Current Inventory</h1>"; //header

    $sql3 = "SELECT number FROM parts"; //sql statement
    $q3 = $pdo2->query($sql3);
    $array3 = $q3->fetchAll(PDO::FETCH_ASSOC);

    $mainarray = array();
    foreach($array3 as $pnumb) //loop through the array and find the propper values
    {
      $sql1 = "SELECT productID, quantity FROM inventory WHERE productID = $pnumb[number]";
      $q1 = $pdo1->query($sql1);
      $array1 = $q1->fetchAll(PDO::FETCH_ASSOC);
      array_push($mainarray, $array1[0]);
    }

    echo "<table border=5, cellspacing=5, cellpadding=5>"; //formatting for the table
echo '<tbody style="background-color:#FF726F">'; //colorring
//table setting
      echo "<tr>";
        echo "<th>Product #</th>";
        echo "<th>Product Name</th>";
        echo "<th>Quantity on Hand</th>";
        echo "<th>Edit Quantity</th>";
      echo "</tr>";

      foreach($mainarray as $num)
      {
        echo "<tr>";
          $sql2 = "SELECT description FROM parts WHERE number = $num[productID]"; //sql query to call propper values
          $q2 = $pdo2->query($sql2);
          $array2 = $q2->fetchAll(PDO::FETCH_ASSOC);
          $description = $array2[0]['description'];

          echo "<td>";
            echo "$num[productID]"; //display product ID
          echo "</td>";

          echo "<td>";
            echo "$description"; //display description
          echo "</td>";

          echo "<td>";
            echo "$num[quantity]"; //display quantity
          echo "</td>";

          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/receptiondesk.php>";
              echo "<input type=number name='qntty' 
                           min=1 required/>";
              echo "<input type=hidden name='pnmbr' 
                           value=$num[productID]/>";
              echo "<input type=submit name='change' 
                           value='Modify'/>";
            echo "</form>";
          echo "</td>";

        echo "</tr>";
      }
  ?>

<footer> 

Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A
<br>
</footer>

</html>
