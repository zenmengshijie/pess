<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Police Emergencry Service System</title>
</head>
<body>
<form name="frmLogCall" method="post"
	onSubmit="return validateForm()" action="dispatch.php">
<table>
	<tr>
		<td colspan="2">Loc call Panel</td>
	</tr>
	
	<tr>
		<td>Caller's Name :</td>
		<td><input type="text" name="callerName" id="callerName"></td>
	</tr>
	<tr>
		<td>Contact No :</td>
		<td><td><input type="text" name="contactNo" id="contactNo"></td>
	</tr>
	<tr>
		<td>Location :</td>
		<td><td><input type="text" name="location" id="location"></td>
	</tr>
	<tr>
		<td>Incident Type :</td>
		<td>
		<select type="text" name="incidentType"></select>
		</td>
	</tr>
	<tr>
		<td>Description :</td>
		<td><textarea name="incidentDesc" id="incidentDesc" cols="45"
				rows="5"></textarea>
		</td>
	</tr>
	<tr>
		<td><input type="reset" name="btnCancel" id="btnCancek"
			values="Reset">
		</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"
			name="btnProcessCall" id="btnProcessCall" value="Process Call...">
		</td>
	</tr>
</table>
</form>
</body>
</html>
	