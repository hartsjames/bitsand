<?php
/*-----------------------------------------------------------------------------
 | Bitsand - an online booking system for Live Role Play events
 |
 | File ooc_form.php
 |     Author: Russell Phillips
 |  Copyright: (C) 2006 - 2015 The Bitsand Project
 |             (http://github.com/PeteAUK/bitsand)
 |
 | Bitsand is free software; you can redistribute it and/or modify it under the
 | terms of the GNU General Public License as published by the Free Software
 | Foundation, either version 3 of the License, or (at your option) any later
 | version.
 |
 | Bitsand is distributed in the hope that it will be useful, but WITHOUT ANY
 | WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 | FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 | details.
 |
 | You should have received a copy of the GNU General Public License along with
 | Bitsand.  If not, see <http://www.gnu.org/licenses/>.
 +---------------------------------------------------------------------------*/

include ('inc/inc_head_db.php');
include ('inc/inc_forms.php');

//Initialise $sWarn
$sWarn = '';

$key = CRYPT_KEY;
$db_prefix = DB_PREFIX;

if ($_POST ['btnSubmit'] != '' && CheckReferrer ('ooc_form.php')) {
	//Run OOC_Check to perform data validation
	$sWarn = OOC_Check ();
	if ($sWarn != '')
		$sWarn .= "<br>The details entered so far have been saved, but you will need to correct the above errors at some point";

	//Update database
	//Build up date of birth in YYYYMMDD format
	$dob = (int) $_POST ['selDobYear'];
	if ($_POST ['selDobMonth'] < 10)
		$dob .= '0';
	$dob .= (int) $_POST ['selDobMonth'];
	if ($_POST ['selDobDate'] < 10)
		$dob .= '0';
	$dob .= (int) $_POST ['selDobDate'];
	//Set up $sMedInfo
	if ($_POST ['txtMedicalInfo'] == 'Enter details here')
		$sMedInfo = '';
	else
		$sMedInfo = $_POST ['txtMedicalInfo'];
	//Remove any spaces in car registration
	if ($_POST ['txtCarRegistration'] == 'Enter NA if you do not drive')
		$sCarReg = ba_db_real_escape_string ($link, 'Enter NA if you do not drive');
	else
		$sCarReg = ba_db_real_escape_string ($link, str_replace (' ', '', $_POST ['txtCarRegistration']));

	if (AUTO_ASSIGN_BUNKS == False) {
		if ($_POST ['chkBunk'] == '')
			$iBunk = 0;
		else
			$iBunk = 1;
	}


	//get value of event pack by post
	if ($_POST ['chkEventPackByPost'] == '')
		$iByPost = 0;
	else
		$iByPost = 1;

	$refnumber = (int) $_POST ["txtRefNumber{$value}"];
	$marshal = stripslashes($_POST ["cboMarshal{$value}"]);

	$playerNumber = isset($_POST['txtPlayerNumber']) ? $_POST['txtPlayerNumber'] : '';

	//Set up UPDATE query
	$sql = "UPDATE {$db_prefix}players SET plFirstName = '" . ba_db_real_escape_string ($link, $_POST ['txtFirstName']) . "', " .
		"plSurname = '" . ba_db_real_escape_string ($link, $_POST ['txtSurname']) . "', " .
		"plPlayerNumber = '" . ba_db_real_escape_string($link, $playerNumber) . "', " .
		"pleAddress1 = AES_ENCRYPT('" . ba_db_real_escape_string ($link, $_POST ['txtAddress1']) . "', '$key'), " .
		"pleAddress2 = AES_ENCRYPT('". ba_db_real_escape_string ($link, $_POST ['txtAddress2']) . "', '$key'), " .
		"pleAddress3 = AES_ENCRYPT('". ba_db_real_escape_string ($link, $_POST ['txtAddress3']) . "', '$key'), " .
		"pleAddress4 = AES_ENCRYPT('". ba_db_real_escape_string ($link, $_POST ['txtAddress4']) . "', '$key'), " .
		"plePostcode = AES_ENCRYPT('". ba_db_real_escape_string ($link, $_POST ['txtPostcode']) . "', '$key'), " .
		"pleTelephone = AES_ENCRYPT('". ba_db_real_escape_string ($link, $_POST ['txtPhone']) . "', '$key'), " .
		"pleMobile = AES_ENCRYPT('". ba_db_real_escape_string ($link, $_POST ['txtMobile']) . "', '$key'), " .
		"plDOB = '$dob', " .
		"pleMedicalInfo = AES_ENCRYPT('". ba_db_real_escape_string ($link, $sMedInfo). "', '$key'), " .
		"plEmergencyName = '" . ba_db_real_escape_string ($link, $_POST ['txtEmergencyName']) . "', " .
		"pleEmergencyNumber = AES_ENCRYPT('". ba_db_real_escape_string ($link, $_POST ['txtEmergencyNumber']) . "', '$key'), " .
		"plEmergencyRelationship = '" . ba_db_real_escape_string ($link, $_POST ['txtEmergencyRelationship']) . "', " .
		"plCarRegistration = '$sCarReg', " .
		"plDietary = '" . ba_db_real_escape_string ($link, $_POST ['selDiet']) . "', ";
		//"plBookAs = '" . ba_db_real_escape_string ($link, $_POST ['selBookAs']) . "', ";
		//if (AUTO_ASSIGN_BUNKS == False)
//			$sql .= "plBunkRequested = $iBunk, ";
		$sql .= "plNotes = '" . ba_db_real_escape_string ($link, $_POST ['txtNotes']) . "', ";
		$sql .= "plRefNumber = $refnumber, plMarshal = '$marshal',";
		$sql .= "plEventPackByPost = $iByPost " .
		"WHERE plPlayerID = $PLAYER_ID";
	//Run UPDATE query
	if (ba_db_query ($link, $sql)) {
		//Query should affect exactly one row. Log a warning if it affected more
		if (ba_db_affected_rows ($link) > 1)
			LogWarning ("More than one row updated during OOC update. Player ID: $PLAYER_ID");
		//Do not redirect if there are any warnings (required fields not filled in, etc)

		if ($sWarn == '') {
			//Update Monster only if person is playing
			//$sql = "update {$db_prefix}players inner join {$db_prefix}characters on plPlayerID = chPlayerID set chMonsterOnly = 0 where plBookAs = 'Player' and plPlayerID = $PLAYER_ID";
			//ba_db_query ($link, $sql);
			//Send e-mail
			$sBody = "Your OOC details have been entered at " . SYSTEM_NAME . ".\n\n" .
				"Player ID: " . PID_PREFIX . sprintf ('%03s', $PLAYER_ID) . "\n" .
				"OOC Name: " . $_POST ['txtFirstName'] . " " . $_POST ['txtSurname'] .
				"\n\n" . fnSystemURL ();
			if ($bEmailOOCChange)
			{
				$sql = "Select plEmail FROM {$db_prefix}players WHERE plPlayerID = $PLAYER_ID";
				$result = ba_db_query ($link, $sql);
				$playerrow = ba_db_fetch_assoc ($result);
				mail ($playerrow['plEmail'], SYSTEM_NAME . ' - OOC details', $sBody, "From:" . SYSTEM_NAME . " <" . EVENT_CONTACT_MAIL . ">");
			}
			//Make up URL & redirect to index.php with message
			$sURL = fnSystemURL () . 'start.php?green=' . urlencode ('Your OOC details have been updated');
			header ("Location: $sURL");
		}
	}
	else {
		$sWarn = "There was a problem updating your OOC details";
		LogError ("Error updating OOC information. Player ID: $PLAYER_ID\nSQL: $sql");
	}
}

//Get existing details if there are any
$sql = "SELECT plFirstName, " .
	"plSurname, " .
	"plPlayerNumber, " .
	"AES_DECRYPT(pleAddress1, '$key') AS dAddress1, " .
	"AES_DECRYPT(pleAddress2, '$key') AS dAddress2, " .
	"AES_DECRYPT(pleAddress3, '$key') AS dAddress3, " .
	"AES_DECRYPT(pleAddress4, '$key') AS dAddress4, " .
	"AES_DECRYPT(plePostcode, '$key') AS dPostcode, " .
	"AES_DECRYPT(pleTelephone, '$key') AS dTelephone, " .
	"AES_DECRYPT(pleMobile, '$key') AS dMobile, " .
	"plEmail, " .
	"plDOB, " .
	"AES_DECRYPT(pleMedicalInfo, '$key') AS dMedicalInfo, " .
	"plEmergencyName, " .
	"AES_DECRYPT(pleEmergencyNumber, '$key') AS dEmergencyNumber, " .
	"plEmergencyRelationship, " .
	"plCarRegistration, " .
	"plDietary, " .
	//"plBookAs, " .
	"plEventPackByPost, ".
	"plRefNumber, ".
	"plMarshal, ";
	//if (AUTO_ASSIGN_BUNKS == False)
	//	$sql .= "plBunkRequested, ";

	$sql .= "plNotes " .
	"FROM {$db_prefix}players WHERE plPlayerID = $PLAYER_ID";

$result = ba_db_query ($link, $sql);
$playerrow = ba_db_fetch_assoc ($result);

include ('inc/inc_head_html.php');
include ('inc/inc_js_forms.php');
?>

<h1><?php echo TITLE?> - OOC Details</h1>

<?php
if ($sWarn != '')
	echo "<p class = 'warn'>$sWarn</p>";
?>

    <p>
        <i>Required fields are highlighted in red.</i>. Details will appear on your character card <i>exactly</i> as you type them - if you don't use capitals, capitals won't appear on your character card.
    </p>

<form action = 'ooc_form.php' method = 'post' accept-charset="iso-8859-1">

    <div class="form-group">
<label for='txtFirstName'>First name(s):</label>
<input required type = "text" class = "form-control w-75 is-invalid" id='txtFirstName' name = "txtFirstName" value = "<?php echo htmlentities (stripslashes ($playerrow ['plFirstName']))?>">
    </div><div class="form-group">
    <label for='txtSurname'>Last name:</label>
<input required type = "text" class = "form-control w-75 is-invalid" id='txtSurname' name = "txtSurname" value = "<?php echo htmlentities (stripslashes ($playerrow ['plSurname']))?>">
    </div><div class="form-group">
    <label for='txtPlayerNumber'>Player ID:</label>
<input type="text" class="form-control text w-75" id="txtPlayerNumber" name="txtPlayerNumber" value="<?php echo htmlentities(stripslashes($playerrow['plPlayerNumber'])); ?>"; ?>
    </div><div class="form-group">
        <label for='txtAddress'>Address:</label>
<td><input required type = "text" class = "form-control is-invalid w-75" id="textAddress" name = "txtAddress1" value = "<?php echo htmlentities (stripslashes ($playerrow ['dAddress1']))?>"><br>
<input type = "text" class = "form-control text w-75" name = "txtAddress2" value = "<?php echo htmlentities (stripslashes ($playerrow ['dAddress2']))?>"><br>
<input type = "text" class = "form-control text w-75" name = "txtAddress3" value = "<?php echo htmlentities (stripslashes ($playerrow ['dAddress3']))?>"><br>
<input type = "text" class = "form-control text w-75" name = "txtAddress4" value = "<?php echo htmlentities (stripslashes ($playerrow ['dAddress4']))?>">
    </div><div class="form-group">

        <label for='txtPostcode'>Postcode:</label>
<input type = "text" class = "form-control text w-75" id="txtPostcode" name = "txtPostcode" value = "<?php echo htmlentities (stripslashes ($playerrow ['dPostcode']))?>">
    </div><div class="form-group">
        <label for='txtPhone'>Telephone Number:</label>
<input type = "text" class = "form-control text w-75" id = "txtPhone" name = "txtPhone" value = "<?php echo htmlentities (stripslashes ($playerrow ['dTelephone']))?>">
    </div><div class="form-group">
        <label for='txtMobile'>Mobile Number:</label>
<input type = "text" class = "form-control text w-75" id = "txtMobile" name = "txtMobile" value = "<?php echo htmlentities (stripslashes ($playerrow ['dMobile']))?>">
    </div><div class="form-group">
        <label for='txtEmail'>E-mail address:</label>
        <div id = "txtEmail class="form-control w-75"><?php echo htmlentities (stripslashes ($playerrow ['plEmail']))?>&nbsp;<a href = "change_password.php">change</a></div>
    </div><div class="form-group">

        <label for='p1DOB'>Date Of Birth:</label>


<?php
$sDoB = $playerrow ['plDOB'];
if ($sDoB != '') {
	$iDobYear = substr ($sDoB, 0, 4);
	$iMonth = substr ($sDoB, 4, 2);
	$iDate = substr ($sDoB, 6, 2);
	$iYear = getdate ();
	$iYear = $iDobYear - $iYear ['year'];
	DatePicker ('Dob', $iYear, $iMonth, $iDate);
}
else
	DatePicker ('Dob', -25);
?>
    </div><div class="form-group">
        <label for='chkMedical'>Tick if you have any medical issues we need to know about:
<?php
$sMedInfo = htmlentities (stripslashes ($playerrow ['dMedicalInfo']));
if ($sMedInfo == '')
	echo "<input class='form-check-input ml-2' id = 'chkMedical' name = 'chkMedical' type = 'checkbox' onclick = 'fnShowMedical ()'><br>\n";
else
	echo "<input class='form-check-input ml-2' id = 'chkMedical' name = 'chkMedical' type = 'checkbox' checked onclick = 'fnShowMedical ()'><br>\n";
?>
        </label>
<!--
SPAN is used to hide/show medical info box. JavaScript is used to write
SPAN tags so that, if JS is disabled, medical info box is always shown
-->
<script type = 'text/javascript'>
<!--
<?php
if ($sMedInfo == '')
	echo "document.write ('<span id = \"spMedicalInfo\" style = \"display: none\">')\n";
else
	echo "document.write ('<span id = \"spMedicalInfo\" style = \"display: inline\">')\n";
?>
// -->
</script>
<textarea class ='form-control w-75' cols = "60" rows = "4" class = "text" name = "txtMedicalInfo" onfocus = "fnClearValue ('txtMedicalInfo', 'Enter details here')">
<?php
$sMedInfo = htmlentities (stripslashes ($playerrow ['dMedicalInfo']));
if ($sMedInfo == '')
	echo 'Enter details here';
else
	echo $sMedInfo;
?>
</textarea>
    </div><div class="form-group">
<script type = 'text/javascript'>
<!--
document.write ('<\/span>')
// -->
</script>

        <label for='txtEmergencyName'>Emergency Contact Name:</label>
<input required type = "text" class = "form-control is-invalid w-75" id = "txtEmergencyName" name = "txtEmergencyName" value = "<?php echo htmlentities (stripslashes ($playerrow ['plEmergencyName']))?>">
        </div><div class="form-group">
        <label for='txtEmergencyNumber'>Emergency contact number:</label>
<?php
if ($playerrow ['dEmergencyNumber'] == '')
	$sValue = '(`On site` is OK)';
else
	$sValue = $playerrow ['dEmergencyNumber'];
?>
<input required type = "text" class = "form-control is-invalid w-75" id = "txtEmergencyNumber" name = "txtEmergencyNumber" value = '<?php echo htmlentities (stripslashes ($sValue))?>' onfocus = "fnClearValue ('txtEmergencyNumber', '(`On site` is OK)')">
    </div><div class="form-group">
<label for="txtEmergencyRelationship">Relationship to emergency contact:</label>
<td><input required type = "text" class = "form-control is-invalid w-75" id="txtEmergencyRelationship" name = "txtEmergencyRelationship" value = "<?php echo htmlentities (stripslashes ($playerrow ['plEmergencyRelationship']))?>">
    </div><div class="form-group">
<label for="txtCarRegistration">Car registration:</label>
<?php
if ($playerrow ['plCarRegistration'] == '')
	$sValue = 'Enter NA if you do not drive';
else
	$sValue = $playerrow ['plCarRegistration'];
?>
<input required type = "text" class = "form-control is-invalid w-75" id = "txtCarRegistration" name = "txtCarRegistration" value = '<?php echo htmlentities (stripslashes ($sValue))?>' onfocus = "fnClearValue ('txtCarRegistration', 'Enter NA if you do not drive')">

    </div><div class="form-group">

<label for="selDiet">Dietary requirements:</label>
<td><select required id = "selDiet" name = "selDiet" class = "form-control is-invalid w-75">
<?php
if ($playerrow ['plDietary'] == '')
	$sValue = 'Select one';
else
	$sValue = $playerrow ['plDietary'];
$asOptions = array ('Select one', 'Omnivore', 'Vegetarian', 'Vegan', 'Other/allergy (details in Medical Information box)');
foreach ($asOptions as $sOption) {
	echo "<option value = '$sOption'";
	if ($sOption == $sValue)
		echo ' selected';
	echo ">$sOption</option>\n";
}
?>
</select>


<!--
<tr>
<td>Booking as:</td>
<td><select name = "selBookAs" id = "selBookAs" class = "req_colour" onchange = "return UpdateBunkCheckbox ()">
<?php
if ($playerrow ['plBookAs'] == '')
	$sValue = 'Select one';
else
	$sValue = $playerrow ['plBookAs'];
if (ALLOW_MONSTER_BOOKINGS)
	$asOptions = array ('Select one', 'Player', 'Monster', 'Staff');
else
	$asOptions = array ('Select one', 'Player', 'Staff');
foreach ($asOptions as $sOption) {
	echo "<option value = '$sOption'";
	if ($sOption == $sValue)
		echo ' selected';
	echo ">$sOption</option>\n";
}
?>
</select></td>
</tr>
-->
    </div><div class='form-group'>
<?php
echo "<label for='cboMarshal'>Are you a Ref or Marshal</label>";
echo "<td><select class='form-control w-75' id='cboMarshal' name='cboMarshal'>";
echo "<option "; if ($playerrow ['plMarshal']== "No") { echo "selected"; }; echo " >No</option>";
echo "<option "; if ($playerrow ['plMarshal']== "Marshal") { echo "selected"; }; echo " >Marshal</option>";
echo "<option "; if ($playerrow ['plMarshal']== "Referee") { echo "selected"; }; echo " >Referee</option>";
echo "<option "; if ($playerrow ['plMarshal']== "Senior Referee") { echo "selected"; }; echo " >Senior Referee</option>";
echo "</select>\n";
echo "</div><div class='form-group'>";
echo "<label for='txtRefNumber'>Ref Number:</label><input type=text class = 'form-control text w-75' id='txtRefNumber' name='txtRefNumber' size=5 value='" . htmlentities (stripslashes ($playerrow ['plRefNumber'])) . "'/>\n";
echo "</div><div class='form-group'>";

/*
echo "<tr><td>Tick to request a bunk:</td>\n";
if ((AUTO_ASSIGN_BUNKS == False && $bBunksAvailable) || $playerrow ['plBookAs'] == '') {
	if ($playerrow ['plBunkRequested'] == 1)
		$sTick = ' checked';
	else
		$sTick = '';
	echo "<td><span id = 'spCheckBunk'><input type = 'checkbox' name = 'chkBunk' value = 'Bunk'$sTick></span>";
	echo "</td></tr>\n";
}
if ($bBunksAvailable == False && $playerrow ['plBookAs'] != '') {
	echo "<td><span id = 'spCheckBunk'><i>No bunks available</i></span></td></tr>\n";
}
*/

if (ALLOW_EVENT_PACK_BY_POST)
{
	echo "<label for='chkEventPackByPost'>Tick to request event pack by post:</label>";
	if ($playerrow ['plEventPackByPost'] == 1)
		$sTick = ' checked';
	else
		$sTick = '';
	echo "<input class='form-check-input' type = 'checkbox' id = 'chkEventPackByPost' name = 'chkEventPackByPost' value = 'ByPost'$sTick>";
    echo "</div><div class='form-group'>";
}
?>
</div><div class='form-group'>
<label for="txtNotes">General OOC Notes (not medical/allergy):<label>
<small id="helpOOCNotes" class="form-text text-muted">Please do not include IC notes here -
there is a box on the IC form for notes
related to your character</small>
<textarea rows = "4" class = "form-control text w-75" name = 'txtNotes'><?php echo htmlentities (stripslashes ($playerrow ['plNotes']))?></textarea>
</div><div class='form-group'>
<button type = 'submit' value = "Submit" name = "btnSubmit" class='btn btn-success mr-2'><i class='fas fa-check mr-1'></i>Submit</button>
<button type = 'reset' value = "Reset form" class='btn btn-secondary mr-2'><i class='fas fa-undo mr-1'></i>Reset</button>
</div>

</form>

<?php
include ('inc/inc_foot.php');