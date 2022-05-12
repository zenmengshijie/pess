<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Police Emergency Service System </title>
<link href="header_style.css" rel="stylesheet" type="text/css">
<link href="content_style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
// Connect to a database
require_once 'db.php';

// Create connection
$conn = new mysqli (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);

// Check Connection
if ($conn -> connect_error) 
{
	die ("Connection Failed: " . $conn -> connect_error);
}

// Retrieve from patrolcar table those patrol cars that are 2: Patrol or 3: Free
$sql = "SELECT patrolcar_id, patrolcar_status_desc FROM patrolcar JOIN patrolcar_status
ON patrolcar.patrolcar_status_id=patrolcar_status.patrolcar_status_id
WHERE patrolcar.patrolcar_status_id='2' or patrolcar.patrolcar_status_id='3'";

$result = $conn->query($sql);

if ($result->num_rows > 0)
{
	while ($row = $result-> fetch_assoc())
	{
		$patrolcarArray [$row['patrolcar_id']] = $row['patrolcar_status_desc'];
	}
}

$conn->close();

?>

<form name="form1" method="post" action="<?php echo htmlentities ($_SERVER['PHP_SELF']); ?> ">
<table class="ContentStyle">
	<tr>
		<td colspan="2"> Incident Detail </td>
	</tr>
	<tr>
		<td> Caller's Name: </td>
		<td><?php echo $_POST ['callerName'] ?>
			<input type="hidden" name="callerName" id="callerName"
			value="<?php echo $_POST ['callerName'] ?>">
		</td>
	</tr>
	<tr>
		<td> Contact No: </td>
		<td><?php echo $_POST ['contactNo']?>
			<input type="hidden" name="contactNo" id="contactNo"
			value="<?php echo $_POST ['contactNo'] ?>">
		</td>
	</tr>
	<tr>
		<td> Location: </td>
		<td><?php echo $_POST ['location'] ?>
			<input type="hidden" name="location" id="location"
			value="<?php echo $_POST ['location'] ?>">
		</td>
	</tr>
	<tr>
		<td> Incident Type: </td>
		<td><?php echo $_POST ['incidentType'] ?>
			<input type="hidden" name="incidentType" id="incidentType"
			value="<?php echo $_POST ['incidentType'] ?>">
		</td>
	</tr>
	<tr>
		<td> Description: </td>
		<td>
			<textarea name="incidentDesc" cols="45" rows="5" readonly id="incidentDesc"><?php echo $_POST ['incidentDesc'] ?>
			</textarea>
			<input name="incidentDesc" type="hidden" id="incidentDesc" value="<?php echo $_POST ['incidentDesc'] ?>"> 
		</td>
	</tr>
</table>

<table class="ContentStyle">
	<tr>
		<td colspan = "3"> Dispatch Patrol Car Panel </td>
	</tr>
	<?php
		foreach ($patrolcarArray as $key=>$value)
		{
	?>
	<tr>
		<td>
			<input type = "checkbox" name = "chkPatrolcar[]"
			value="<?php echo $key?>">
		</td>
		<td><?php echo $key ?></td>
		<td><?php echo $value ?></td>
	</tr>
		<?php } ?>
	<tr>
		<td>
			<input type="reset" name="btnCancel" id="btnCancel" value="reset">
		</td>

		<td colspan = "2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="btnDispatch" id="btnDispatch" value="Dispatch">
		</td>
	</tr>
</table>

<?php
// if postback via clicking dispatch Button
if (isset($_POST["btnDispatch"]))
{
	require_once 'db.php';
	
	// Create Connection
	$conn = new mysqli (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	
	// Check Connection
	if ($conn->connect_error) 
	{
		die ("Connection Failed: " . $conn->connect_error);
	}
	
	$patrolcarDispatched = $_POST ["chkPatrolcar"]; 	//Array of patrol car being dispatched from past back
	$numofPatrolCarDispatched = count ($patrolcarDispatched);
	
	if ($numofPatrolCarDispatched > 0)
	{
		$incidentStatus='2'; 	// Incident Status to be set as dispatched
	}
	
	else 
	{
		$incidentStatus='1'; 	// Incident Status to be set aas Pending
	}
	
$sql = "INSERT INTO incident (caller_name, phone_number, incident_type_id, incident_location, incident_desc, incident_status_id) VALUES ('".$_POST['callerName']."','".$_POST['contactNo']."','".$_POST ['incidentType']."','".$_POST['location']."','".$_POST ['incidentDesc']."', $incidentStatus)";
	if ($conn->query ($sql) === FALSE)
	{
		echo "Error: " .$sql . "</br>" . $conn-> error;
	}

// Retrieve incident_id for the newly inserted table
	$incidentId=mysqli_insert_id($conn);;
	
// Update patrolcar status table and add into dispatch table
	for ($i=0; $i < $numofPatrolCarDispatched; $i++ )
	{
		// Update patrol car status ////////////////////
		$sql = "UPDATE patrolcar SET patrolcar_status_id='1' WHERE patrolcar_id = '".$patrolcarDispatched [$i]."'";
		
		if ($conn -> query ($sql) === FALSE)
		{
			echo "Error: " . $sql . "</br>" . $conn->error;
		}
		
		// Insert Dispatch data ////////////////////
		$sql = "INSERT INTO dispatch (incident_id, patrolcar_id, time_dispatched) VALUES ($incidentId, '".$patrolcarDispatched [$i]."', NOW ())";
		
		if ($conn-> query ($sql) === FALSE) 
		{
			echo "Error: " .$sql. "</br>" . $conn -> error;
		}
	}
	
	$conn->close();

?>

	<?php
            if(empty($patrolcarArray))
                {echo "<p>" . "No cars are available" . "</p>";}
            else
            {
			foreach($patrolcarArray as $key=>$value){
		?>
		    
			<td><input type="checkbox" name="chkPatrolcar[]" 
				value="<?php echo $key?>"></td>
			<td><?php echo $key ?></td>
			<td><?php echo $value ?></td>
            
		</tr>
		<?php	}}	?>

<script type="text/javascript">window.location="./logcall.php";</script>
<?php } ?>

<?php 		// Validate if request comes from logcall.php or post back

if (!isset ($_POST ["btnProcessCall"]) && !isset ($_POST["btnDispatch"]))
	header ("Location: Logcall.php");
?>


</form>
</body>
</html>