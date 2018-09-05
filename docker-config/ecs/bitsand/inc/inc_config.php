<?php
/*-----------------------------------------------------------------------------
 | Bitsand - an online booking system for Live Role Play events
 |
 | File inc/inc_config_dist.php
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

require "/secrets/bitsand.php";

// Prefix for player ID. Player ID will be this prefix followed by a number
define ('PID_PREFIX', '');

// Type of database. Valid values are 'mysql' (for the PHP MySQL extension) or 'mysqli' (for the PHP Improved MySQL extension)
define ('DB_TYPE','mysql');
// Table prefix. All tables will start with this, if defined. Can be useful if you have multiple applications sharing a single database
define ('DB_PREFIX','');
// MySQL user name & password. User must have SELECT, INSERT, UPDATE & DELETE privileges

define ('OLD_PW_SALT','');

// Web site address where the system is hosted.
// Include the  'http://' (or 'https://') prefix and trailing '/' but do not include 'index.php'.
// Examples:
// 'http://www.domain.tld/' is valid
// 'http://www.domain.tld' is invalid
// 'http://www.domain.tld/index.php' is invalid
// 'www.domain.tld/' is invalid
define ('SYSTEM_URL', $_ENV["BITSAND_SYSTEM_URL"]);

// Set to True to enable debug mode (errors & warnings are reported) DO NOT USE EXCEPT WHEN TESTING
// Note that True and False are not enclosed in quote marks
define ('DEBUG_MODE', $_ENV["BITSAND_DEBUG_MODE"] === "TRUE");
// Set to True to enable maintenance mode (ie users will be shown a 'site is down for maintenance' message)
define ('MAINTENANCE_MODE', $_ENV["BITSAND_MAINTAINENCE_MODE"] === "TRUE");

// Log files. These must be writeable by the web server user
// One file can be used for both if desired - just specify the same file name for both
// If no logs are required (not recommended), set both to ''
// NOTE THAT THIS IS NOT A URL - IT IS A LOCAL FILE. So, for instance,
// on a Windows server, this might be something like 'c:\web\bitsand\log.txt',
// on a Unix/Linux server, it might be something like '/home/web/bitsand.log'
define ('WARNING_LOG', $_ENV["BITSAND_LOG_WARNINGS"] === "TRUE");
define ('ERROR_LOG', $_ENV["BITSAND_LOG_ERRORS"] === "TRUE");

/* Items below here should not need to be edited */
define ('NUM_GUILDS',10);
define ('MAX_CHAR_PTS',16);
define ('MAX_NPC_PTS',20);
define ('MAX_OSPS',24);
