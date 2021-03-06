<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>The Sparks Foundation - Basic Banking System</title>
    <link rel="shortcut icon" href="https://www.thesparksfoundationsingapore.org/images/logo_small.png" type="image/png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/navbar.css"> 
	<link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/table.css">
    <link rel="stylesheet" href="CSS/form.css">
   
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>




				<?php
				  include 'navbar.php';
			     ?>
						
                        <div class="table-container">
								<h1 class="heading"> Transfer Money</h1>

								<?php
									include 'Database/dbconfig.php';
									if (isset($_REQUEST['id'])) 
									{
										$sid = $_GET['id'];
										$sql = "SELECT * FROM  users where id = $sid";
										$result = mysqli_query($conn, $sql);
										if (!$result) 
										{
											echo "Error : " . $sql . "<br>" . mysqli_error($conn);
										}
										$rows = mysqli_fetch_assoc($result);
									}
								?>
									  

								<form method="post">    
									<table class="table">
										<thead>
											<tr>
												<th>Account No</th>
												<th>Name</th>
												<th>Email</th>
												<th>Balance</th>
																				   
											</tr>
										</thead>   
										<tbody>
											<tr>
												<td data-label="Acc. No"><?php echo (isset($rows['Id']) ? $rows['Id'] : ' ' ); ?></td>                        
												<td data-label="Name"><?php echo (isset($rows['Name']) ? $rows['Name'] : ' ');?></td>
												<td data-label="Email"><?php echo (isset($rows['Email']) ? $rows['Email'] : ' ');?></td>                       
												<td data-label="Balance"><b>???</b><?php  echo (isset($rows['Balance']) ? $rows['Balance'] : ' ');?></td>     
											</tr>					
										</tbody>
									</table> 

									<div class="wrapper">
										<div class="title">
											Transfer Money 
										</div>
										<div class="form">
											<div class="inputfield">
												<label for="to">Receiver</label>
												<div class="custom_select">
													<select id="to" name="to" class="form-control" required>
														<option value="" disabled selected>Choose a Recipient</option>
														<?php
															include 'Database/dbconfig.php';
															$sid = $_REQUEST['id'];
															$sql = "SELECT * FROM Users where id!=$sid";
															$result = mysqli_query($conn, $sql);
															if (!$result) 
															{
																echo "Error " . $sql . "<br>" . mysqli_error($conn);
															}
															while ($rows = mysqli_fetch_assoc($result)) 
															{
																?>
																	<option class="table" value="<?php echo $rows['Id']; ?>">

																		<?php echo $rows['Name']; ?> &emsp;(Balance:
																		<?php echo $rows['Balance']; ?> )

																	</option>
																<?php
															}
														?>
													</select>
												</div>
											</div> 			  
											<div class="inputfield">
												<label for="amount" >Amount</label>
												<input type="number" class="input" name="amount" id="amount" required>
													 
										    </div> 

											<div class="inputfield">
												<input name="submit" type="submit" value="Transfer" class="btn">
											</div>
										</div>
									</div>
								</form>
                              
				</div>	
                <footer id="foot"> 
                                    <div class="footer-content">
                                        <h3>The Sparks Foundation</h3>
                                        <p>... inspiring, innovating, integrating</p>
                                        <ul class="socials">
                                            <li><a href="https://www.facebook.com/thesparksfoundation.info"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="https://twitter.com/tsfsingapore?lang=en"><i class="fa fa-twitter"></i></a></li>
                                            <li><a href="https://www.instagram.com/thesparksfoundation.info/"><i class="fa fa-instagram"></i></a></li>
                                            <li><a href="https://sg.linkedin.com/company/the-sparks-foundation"><i class="fa fa-linkedin-square"></i></a></li>
                                        </ul>
                                    </div>
                </footer>
</body>	
</html>



<?php
include 'Database/dbconfig.php';

if (isset($_POST['submit'])) 
{

		$from = $_GET['id'];
		$to = $_POST['to'];
		$amount = $_POST['amount'];

		$sql = "SELECT * from users where id=$from";
		$query = mysqli_query($conn, $sql);
		$sql1 = mysqli_fetch_array($query); // returns array from which the amount is to be transferred.

		// check input of negative value by user
		if (($amount) < 0) 
		{
			echo '<script>';
			echo ' alert("Please Enter  Valid amount.")';  // showing an alert box.
			echo '</script>';
		}

		// check insufficient balance.
		else if ($amount > $sql1['Balance']) 
		{
			echo '<script>';
			echo ' alert("Not Enough Balance. Try Again Later.")';  // showing an alert box.
			echo '</script>';
		}

		// constraint to check zero values
		else if ($amount == 0) 
		{

			echo "<script>";
			echo "alert('Please Enter a Valid Amount')";
			echo "</script>";
		} 
		else 
		{
			$sql = "SELECT * from users where id=$to";
			$query = mysqli_query($conn, $sql);
			$sql2 = mysqli_fetch_array($query);

			$sender = $sql1['Name'];
			$receiver = $sql2['Name'];

			// deducting amount from sender's account
			$newbalance = $sql1['Balance'] - $amount;
			$sql = "UPDATE users set Balance=$newbalance where id=$from";
			mysqli_query($conn, $sql);

			// adding amount to reciever's account
			$newbalance = $sql2['Balance'] + $amount;
			$sql = "UPDATE users set Balance=$newbalance where Id=$to";
			mysqli_query($conn, $sql);


			$sql = "INSERT INTO transaction(`Sender`, `Receiver`, `Balance`) VALUES ('$sender','$receiver','$amount')";
			$query = mysqli_query($conn, $sql);

			if ($query) {
				echo "<script> alert('Success. Money Transfer Completed');
										 window.location='Transaction History.php';
							   </script>";
			}

			$newbalance = 0;
			$amount = 0;
		}
}
?>