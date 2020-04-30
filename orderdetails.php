<!-- orderdetails.php -->

<html>
  <?php
    try
    {
      $dsn1 = "mysql:host=courses;dbname=z1853066";
      include("pswrds.php");
      $pdo1 = new PDO($dsn1, $username1, $password1);
    }

    catch(PDOexception $exception1)
    {
      echo "Database connection failed: " . $exception1->getMessage();
    }

    try
    {
      $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
      include("pswrds.php");
      $pdo2 = new PDO($connection2, $username2, $password2);
    }

    catch(PDOexception $exception2)
    {
      echo "Database connection failed: " . $exception2->getMessage();
    }

    $sql1 = "SELECT ordersID, custid, finalprice, date, status FROM orders";
    if (array_key_exists('sql', $_REQUEST))
    {
      $sql1 = "";
      $asarray = unserialize(base64_decode($_REQUEST['sql']));

      foreach($asarray as $word)
      {
        $sql1 = ($sql1 . $word . " ");
      }

      $sql1 = substr($sql1, 0, -1);
    }

    $toarray = explode(" ", $sql1);

    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";
      $a2s= base64_encode(serialize($toarray));
      echo "<input type=hidden name='sql'
                   value=$a2s1/>";
      echo "<input type=hidden name='viewall'
                   value='View All Orders'/>";
      echo "<input type=hidden name='search'
                   value='R'/>";
      echo "<input type=submit name='orders'
                   value='Return to Search'/>";
    echo "</form>";

    $orderid = 0;
    if (array_key_exists('oid', $_REQUEST))
    {
      $orderid = intval($_REQUEST['oid']);
    }

    $sqlord = "SELECT * FROM orders WHERE ordersID = $orderid";
    $queryord = $pdo1->query($sqlord);
    $arrayord = $queryord->fetchAll(PDO::FETCH_ASSOC);

    $order = $arrayord[0];
    $customer = $arrayord[0]['custid'];

    echo "<h3>Order Details</h3>";
    echo "<table border=3>";
      echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>Customer ID</th>";
        echo "<th>Status</th>";
        echo "<th>Total Weight</th>";
        echo "<th>Additional Charges</th>";
        echo "<th>Price</th>";
        echo "<th>Total Price</th>";
        echo "<th>Date of Order</th>";
      echo "</tr>";

      echo "<tr>";
        foreach($order as $data)
        {
          echo "<td>";
            echo "$data";
          echo "</td>";
        }
      echo "</tr>";
    echo "</table>";

   echo "<h3>Item Details</h3>";
   $sqlprod = "SELECT productID, quantity FROM ordereditems
              WHERE orderID = $orderid";
   $queryprod = $pdo1->query($sqlprod);
   $arrayprod = $queryprod->fetchAll(PDO::FETCH_ASSOC);

   echo "<table border=3>";
     echo "<tr>";
       echo "<th>Product Number</th>";
       echo "<th>Product Description</th>";
       echo "<th>Quantity Ordered</th>";
       echo "<th>Product Price</th>";
       echo "<th>Added Price</th>";
       echo "<th>Product Weight</th>";
       echo "<th>Added Weight</th>";
     echo "</tr>";

    foreach($arrayprod as $ordereditem)
    {
      $databdesc= "SELECT description, price, weight FROM parts WHERE number = $ordereditem[productID]";
      $querydesc= $pdo2->query($databdesc);
      $arraydesc = $querydesc ->fetchAll(PDO::FETCH_ASSOC);
      $description = $arraydesc[0]['description'];
      $weight = $arraydesc[0]['weight'];
      $price = $arraydesc[0]['price'];

      echo "<tr>";
        echo "<td>";
          echo "$ordereditem[productID]";
        echo "</td>";

        echo "<td>";
          echo "$description";
        echo "</td>";

        echo "<td>";
          echo "$ordereditem[quantity]";
        echo "</td>";

        echo "<td>";
          echo "$price";
        echo "</td>";

        $trueprice = ($ordereditem['quantity'] * $price);
        echo "<td>";
          echo "$trueprice";
        echo "</td>";

        echo "<td>";
          echo "$weight";
        echo "</td>";

        $trueweight = ($ordereditem['quantity'] * $weight);
        echo "<td>";
          echo "$trueweight";
        echo "</td>";
      echo "</tr>";
    }

   echo "</table>";


   echo "<h3>Customer Details</h3>";

   $internetcustomer = "SELECT name, email, address, ccnum FROM customer WHERE customerID = $customer";
   $querycust = $pdo1->query($internetcustomer);
   $arraycust = $querycust->fetchAll(PDO::FETCH_ASSOC);

   echo "<table border=3>";
     echo "<tr>";
       echo "<th>Customer Name</th>";
       echo "<th>Customer Email</th>";
       echo "<th>Customer Address</th>";
       echo "<th>Credit Card</th>";
     echo "</tr>";

     echo "<tr>";
       foreach($arraycust as $cust)
       {
         echo "<td>";
           echo "$cust[name]";
         echo "</td>";

         echo "<td>";
           echo "$cust[email]";
         echo "</td>";

         echo "<td>";
           echo "$cust[address]";
         echo "</td>";

         $star = "************";
         $censor = substr_replace($cust['ccnum'], $star, 0, -4);
         echo "<td>";
           echo "$censor";
         echo "</td>";
       }
     echo "</tr>";
   echo "</table>";
  ?>
</html>

