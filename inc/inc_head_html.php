<?php
/*-----------------------------------------------------------------------------
 | Bitsand - an online booking system for Live Role Play events
 |
 | File inc/inc_head_html.php
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

//Function to obfuscate an e-mail address - returns address made up of HTML entities
function Obfuscate ($email) {
	$sReturn = '';
	for ($i = 0; $i < strlen ($email); $i++) {
		$bit = $email [$i];
		$sReturn .= '&#' . ord ($bit) . ';';
	}
	return $sReturn;
}

//Function to write a help link. The link is quite long (with alt/title, etc) so it
//is easier/quicker to implement it as a function
function HelpLink ($helppage) {
	echo "<a href = '" . SYSTEM_URL . "help/$helppage' target = 'help_popup' onClick = 'wopen(\"" . SYSTEM_URL . "help/$helppage\"); " .
		"return false;'><img src = '" . SYSTEM_URL . "img/help.png' style = 'border:none' " .
		"alt = 'Get help on this feature' title = 'Get help on this feature'></a>";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type = 'text/javascript'>
function wopen(url) {
	var win = window.open(url, 'help_popup', 'width=500, height=300, location=no, menubar=no, status=no, toolbar=no, scrollbars=yes, resizable=yes');
	//win.resizeTo(w, h);
	win.focus();
}
</script>

<?php
//Use HTTPS or HTTP link for JQuery, to avoid browser complaining that page contains non-secure items when using HTTPS
if ($_SERVER ["HTTPS"])
{
    echo "<script src=\"https://code.jquery.com/jquery-3.2.1.slim.min.js\" integrity=\"sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN\" crossorigin=\"anonymous\"></script>\n";
//	echo "<script src='https://ajax.microsoft.com/ajax/jquery/jquery-1.5.1.min.js' type='text/javascript'></script>\n";
    echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\" integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"></script>\n";
	echo "<script src='https://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.min.js' type='text/javascript'></script>\n";
	echo "<link href='https://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/flick/jquery-ui.css' rel='stylesheet' type='text/css' />\n";
	echo "<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"></script>/n";
}
else
{
	echo "<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.5.1.min.js' type='text/javascript'></script>\n";
	echo "<script src='http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.min.js' type='text/javascript'></script>\n";
	echo "<link href='http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/flick/jquery-ui.css' rel='stylesheet' type='text/css' />\n";
	echo "<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.3.1/css/all.css' integrity='sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU' crossorigin='anonymous'>\n";
}
echo "<link rel = 'shortcut icon' href = '" . SYSTEM_URL . "favicon.ico'>\n";
echo "<link rel = 'alternate' type = 'application/rss+xml'  href = '" . SYSTEM_URL .
	"bookings_rss.php' title = '" . TITLE . " - Booking List'>\n";

//Different style for some pages.
$sPage = basename ($_SERVER ["SCRIPT_FILENAME"]);

echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\">\n";
echo "<link rel = 'stylesheet' type = 'text/css' href = '{$CSS_PREFIX}inc/main.css' media = 'screen'>\n";
echo "<link rel = 'stylesheet' type = 'text/css' href = '{$CSS_PREFIX}inc/body.css' media = 'screen'>\n";
if (strpos($sPage, "admin") === 0 || strpos($sPage, "root") === 0)
{
	echo "<link rel = 'stylesheet' type = 'text/css' href = '{$CSS_PREFIX}inc/admin.css' media = 'screen'>\n";
	echo "<link rel = 'stylesheet' type = 'text/css' href = '{$CSS_PREFIX}inc/wysiwyg/jquery.wysiwyg.css' media = 'screen'>\n";
	echo "<link rel = 'stylesheet' type = 'text/css' href = '{$CSS_PREFIX}inc/wysiwyg/jquery.wysiwyg.modal.css' media = 'screen'>\n";
}
echo "<link rel = 'stylesheet' type = 'text/css' href = '{$CSS_PREFIX}inc/print.css' media = 'print'>\n";

if ($metadescription ==  '')
{
	$metadescription = "Online event bookings";
}
echo "<meta name=\"description\" content=\"$metadescription\">";
?>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title><?php echo TITLE?></title>

</head>

<?php
if ($sPage == 'index.php' || $sPage == 'start.php')
	echo "<body class = 'event'>\n";
elseif ($sPage == 'ic_form.php')
	echo "<body onload = 'fnCalculate ()'>\n";
else
	echo "<body>\n"?>

<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="start.php"><?php echo TITLE?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php

                    if ($PLAYER_ID == 0)
                        echo "{$CSS_PREFIX}index.php";
                    else
                        echo "{$CSS_PREFIX}start.php";

?>">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <?php echo "<a class='nav-link' href='{$CSS_PREFIX}eventlist.php'>Events</a>\n"?>
                </li>
            </ul>

        <?php
        if ($PLAYER_ID == 0)
                {
                echo "<form class='form-inline mt-2 mt-md-0' action = 'index.php' method = 'post'>";
                        echo "<input name = 'txtEmail' class='form-control mr-sm-2' type='text' placeholder='Email' aria-label='Email'/>";
                        echo "<input name = 'txtPassword' type='password' class='form-control mr-sm-2' type='text' placeholder='Password' aria-label='Password'/>";

                        echo "<button type = 'submit' name = 'btnSubmit' value = 'Login' class='btn btn-secondary mr-2'><i class='fas fa-sign-in-alt mr-1'></i>Login</button>";
                        echo "</form>";
                echo "<a href = '{$CSS_PREFIX}register.php' class='btn btn-warning' role='button'><i class='fas fa-user-plus mr-1'></i>Register</a>";
                }
                else
                {
                echo "<a href = '{$CSS_PREFIX}change_password.php' class='btn btn-secondary mr-2' role='button'><i class='fas fa-user-alt mr-1'></i>Manage Account</a>\n";
                echo "<a href = '{$CSS_PREFIX}index.php?green=" . urlencode ("You have been logged out") . "' class='btn btn-danger' role='button'><i class='fas fa-sign-out-alt mr-1'></i>Log out</a>\n";
                }
                ?>


        </div>
    </nav>
</header>

<div class="row">
    <div class="col-md-2 d-none d-md-block sidebar pt-3">
        <ul class="nav flex-column mt-5">
        <?php

$today = date("Y-m-d");
$sql = "select evEventID, evEventName, evEventDate from " . DB_PREFIX . "events where evBookingsOpen <= '".$today."' and evEventDate >= '".$today."'";
$result = ba_db_query ($link, $sql);

	if (ba_db_num_rows($result) == 1)
	{
		$evrow = ba_db_fetch_assoc($result);
		echo "<li class=' nav-item'><a class='nav-link' href = '{$CSS_PREFIX}eventdetails.php?EventID=".$evrow['evEventID']."'><i class='fas fa-calendar-alt pr-2'></i>Event details</a></li>\n";
	}
	else
	{
		echo "<li class='nav-item'><a class='nav-link' href = '{$CSS_PREFIX}eventlist.php'><i class='fas fa-calendar-alt pr-2'></i>Event list</a></li>\n";
	}



if ($PLAYER_ID != 0)
{
	if (($sOOC == '' || $sOOC == '0000-00-00'))
		echo "<li class='nav-item'><a class='nav-link' href = '{$CSS_PREFIX}ooc_form.php'><i class='fas fa-user-circle pr-2'></i>OOC information</a></li>\n";
	else
		echo "<li class='nav-item'><a class='nav-link' href = '{$CSS_PREFIX}ooc_view.php'><i class='fas fa-user-circle pr-2'></i>OOC information</a></li>\n";
	if (($sDateIC == '' || $sDateIC == '0000-00-00'))
		echo "<li class='nav-item'><a class='nav-link' href = '{$CSS_PREFIX}ic_form.php'><i class='far fa-user-circle pr-2'></i>IC information</a></li>\n";
	else
		echo "<li class='nav-item'><a class='nav-link' href = '{$CSS_PREFIX}ic_view.php'><i class='far fa-user-circle pr-2'></i>IC information</a></li>\n";

	// Show link to admin page if user is an admin or root user (also handle player number)
	$sql = "SELECT plAccess, plPlayerNumber FROM " . DB_PREFIX . "players WHERE plPlayerID = $PLAYER_ID";
	$result = ba_db_query ($link, $sql);
	$inc_head_html_row = ba_db_fetch_assoc ($result);
	if ($inc_head_html_row ['plAccess'] == 'admin' || ROOT_USER_ID == $PLAYER_ID) {
		echo "<li class='nav-item'><a class='nav-link' href = '{$CSS_PREFIX}admin/admin.php'><i class='fas fa-cog pr-2'></i>Admin</a></li>\n";
	}
}

echo "</ul>";
?>
    </div>

    <div class="col-10 bg-white" style="margin-top: 3.8rem;">




<?php


if (ini_get ('error_reporting') != 0)
	echo "<p style = 'border: solid thin orange; background: orange; text-align: center;'><b>DEBUG MODE ENABLED</b></p>\n";

if (isset($inc_head_html_row) && empty($inc_head_html_row['plPlayerNumber'])) {
	echo '<p class="green" style="text-align: center;"><a href="ooc_form.php" style="color: inherit">Please set your Player Number</a></p>' . "\n";
}

if (($inc_head_html_row ['plAccess'] == 'admin' || ROOT_USER_ID == $PLAYER_ID) && $PLAYER_ID != 0) {
    //Check for install & NON_WEB directories
    if (file_exists (dirname ($_SERVER ["SCRIPT_FILENAME"]) . "/install"))
        echo "<span class = 'sans-warn'>The <a href = 'install/'>install</a> directory is present. It should be removed if the system is live</span><br />";
    if (file_exists (dirname ($_SERVER ["SCRIPT_FILENAME"]) . "/NON_WEB"))
        echo "<span class = 'sans-warn'>The NON_WEB directory is present. It should be removed</span><br />";
}