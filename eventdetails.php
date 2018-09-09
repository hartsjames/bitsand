<?php
/*-----------------------------------------------------------------------------
 | Bitsand - an online booking system for Live Role Play events
 |
 | File inc/inc_config_dist.php.php
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

$bLoginCheck = False;


include ('inc/inc_head_db.php');
include ('inc/inc_commonqueries.php');

$db_prefix = DB_PREFIX;
$eventinfo = getEventDetails($_GET['EventID'], 0);
if (!$eventinfo || !isset($eventinfo['evEventID'])) {
	die('Sorry this event doesn\'t appear to exist?');
}
$metadescription = "Online booking for ".htmlentities (stripslashes ($eventinfo['evEventName']));
include ('inc/inc_head_html.php');


?>
<script src="inc/sorttable.js" type="text/javascript"></script>
<h2>Event Details - <?php echo htmlentities (stripslashes ($eventinfo['evEventName'])); ?></h2>

<?php
if ((BOOKING_LIST_IF_LOGGED_IN && $PLAYER_ID != 0) || !BOOKING_LIST_IF_LOGGED_IN) { echo "<p><a href='#booked'>View booked players</a></p>"; }
echo "<div class='eventdescription'>\n";
echo stripslashes ($eventinfo['evEventDescription']);
echo "</div>\n<div class='eventdetails'>\n";
echo stripslashes ($eventinfo['evEventDetails']);
echo "</div>";


//Get list of players that have paid
$sql = "SELECT plFirstName, " .
	"plSurname, " .
	"bkBookAs, " .
	"chName, chPreferredName, chGroupSel, chGroupText, chFaction, chMonsterOnly, " .
	"bkDatePaymentConfirmed " .
	"FROM {$db_prefix}players INNER JOIN {$db_prefix}bookings ON plPlayerID = bkPlayerID " .
	"LEFT OUTER JOIN {$db_prefix}characters ON plPlayerID = chPlayerID " .
	"WHERE bkDatePaymentConfirmed <> '' " .
	"AND bkDatePaymentConfirmed <> '0000-00-00' " .
	"AND bkEventID = ". $eventinfo['evEventID'];

$result = ba_db_query ($link, $sql);

if ((BOOKING_LIST_IF_LOGGED_IN && $PLAYER_ID != 0) || !BOOKING_LIST_IF_LOGGED_IN) {
	echo "<a name='booked' />";
	echo "<p>The people below have booked and paid for this event</p>";
	echo "<table class='sortable table table-hover table-striped table-sm'><tr><th>OOC First Name</th><th>OOC Surname</th><th>IC Name</th>";

	if (LIST_GROUPS_LABEL != '')
		echo "<th>Group</th>";

	echo "<th>Faction</th>";
	echo "<th>Booking As</th></tr>\n";

	while ($row = ba_db_fetch_assoc ($result)) {
		echo "<tr class = 'highlight'>\n";
		echo "<td>" . htmlentities (stripslashes ($row ['plFirstName'])) . "</td>\n";
		echo "<td>" . htmlentities (stripslashes ($row ['plSurname'])) . "</td>\n";

		if ($row['chMonsterOnly'] == 0)
		{
			if (strlen($row ['chPreferredName']) == 0)
			{
				echo "<td>" . htmlentities (stripslashes ($row ['chName'])) . "</td>\n";
			}
			else
			{
				echo "<td>" . htmlentities (stripslashes ($row ['chPreferredName'])) . "</td>\n";
			}
			if (LIST_GROUPS_LABEL != '') {
				echo "<td>\n";
				if ($row ['chGroupText'] == 'Enter name here if not in above list' || $row ['chGroupText'] == '')
					echo htmlentities (stripslashes ($row ['chGroupSel']));
				else
					echo "Other (" . htmlentities (stripslashes ($row ['chGroupText'])) . ")";
				echo "</td>\n";
			}

			echo "<td>". htmlentities (stripslashes ($row ['chFaction'])) ."</td>";

		}
		else {
			echo "<td></td>";
			if (LIST_GROUPS_LABEL != '') {
				echo "<td></td>";
			}
			echo "<td></td>";
		}
		$bookingtype = str_replace("Staff", $stafftext, $row ['bkBookAs']);
		echo "<td>" . htmlentities (stripslashes ($bookingtype)) . "</td>\n</tr>\n";
	}

	echo "</table>";

	if ($eventinfo['evAllowMonsterBookings']) {
		$sql = "SELECT plPlayerID, " .
			"bkBookAs, " .
			"bkDatePaymentConfirmed " .
			"FROM {$db_prefix}players, {$db_prefix}bookings " .
			"WHERE bkBookAs LIKE 'Monster' AND plPlayerID = bkPlayerID AND bkDatePaymentConfirmed <> '' AND bkDatePaymentConfirmed <> '0000-00-00' AND bkEventID = ". $eventinfo['evEventID'];
		$result = ba_db_query ($link, $sql);
		$iMonsters = ba_db_num_rows ($result);
	}
	else
		$iMonsters = 0;
	$sql = "SELECT plPlayerID, " .
		"bkBookAs, " .
		"bkDatePaymentConfirmed " .
		"FROM {$db_prefix}players, {$db_prefix}bookings " .
		"WHERE bkBookAs LIKE 'Player' AND plPlayerID = bkPlayerID AND bkDatePaymentConfirmed <> '' AND bkDatePaymentConfirmed <> '0000-00-00' AND bkEventID = ". $eventinfo['evEventID'];
	$result = ba_db_query ($link, $sql);
	$iPlayers = ba_db_num_rows ($result);
	$sql = "SELECT plPlayerID, " .
		"bkBookAs, " .
		"bkDatePaymentConfirmed " .
		"FROM {$db_prefix}players, {$db_prefix}bookings " .
		"WHERE bkBookAs LIKE 'Staff' AND plPlayerID = bkPlayerID AND bkDatePaymentConfirmed <> '' AND bkDatePaymentConfirmed <> '0000-00-00' AND bkEventID = ". $eventinfo['evEventID'];
	$result = ba_db_query ($link, $sql);
	$iStaff = ba_db_num_rows ($result);
	$iTotal = $iMonsters + $iPlayers + $iStaff;
	$iCrew = $iMonsters + $iStaff;
	echo "<p>\n";
	echo "$iStaff $stafftext, ";
	if ($eventinfo['evAllowMonsterBookings'])
		echo "$iMonsters Monsters, ";
	echo "$iPlayers Players. ($iTotal total)\n</p>";
	echo "<p class = 'smallprint'><a href = 'bookings_rss.php?event={$eventinfo['evEventID']}'>RSS feed of bookings for this event</a></p>\n";
}
else {
	echo "<p>The list of bookings for this event is only available if you are logged into the system.</p>";
}

echo "<p class = 'smallprint'><a href='iCalendar.php'>iCalendar feed of events</a></p>\n";
include ('inc/inc_foot.php');