<?php
/*-----------------------------------------------------------------------------
 | Bitsand - an online booking system for Live Role Play events
 |
 | File inc/inc_error.php
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

//inc_error.php - error handling/logging routines
if (DEBUG_MODE)
	//Report all errors except E_NOTICE - use for debugging
	error_reporting (E_ALL ^ E_NOTICE);
else
	//Turn off all error reporting
	error_reporting (0);

//Log an error. $sMsg is text to write to file
function LogError ($sMsg) {
	if (ERROR_LOG != '') {
		$sWrite = date ('d M Y H:i:s') . "\n$sMsg\n------------\n";
		if (error_log ($sWrite, 3, ERROR_LOG)) {
			//Log written successfully. Now check size - if greater than 1MB, truncate
			if (filesize (ERROR_LOG) > 1048576) {
				$asLog = file (ERROR_LOG);
				//Note: using fopen (), fwrite (), fclose () to ensure compatibility with PHP4
				$fLogOut = fopen (ERROR_LOG, 'w');
				//Write last 500 lines back to file
				for ($i = count ($asLog) - 500; $i <= count ($asLog); $i++)
					fwrite ($fLogOut, $asLog [$i]);
				fclose ($fLogOut);
			}
		}
	}
}

//Log a warning. $sMsg is text to write to file
function LogWarning ($sMsg) {
	if (WARNING_LOG != '') {
		$sWrite = date ('d M Y H:i:s') . "\n$sMsg\n------------\n";
		if (error_log ($sWrite, 3, WARNING_LOG)) {
			//Log written successfully. Now check size - if greater than 1MB, truncate
			if (filesize (WARNING_LOG) > 1048576) {
				$asLog = file (WARNING_LOG);
				//Note: using fopen (), fwrite (), fclose () to ensure compatibility with PHP4
				$fLogOut = fopen (WARNING_LOG, 'w');
				//Write last 500 lines back to file
				for ($i = count ($asLog) - 500; $i <= count ($asLog); $i++)
					fwrite ($fLogOut, $asLog [$i]);
				fclose ($fLogOut);
			}
		}
	}
}