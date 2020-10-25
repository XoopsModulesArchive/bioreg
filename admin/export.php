<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: XOOPS Foundation                                                  //
// URL: https://www.xoops.org/                                                //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
//
// To make this export work, you need to add the following fields to the database:
//   member_id
//   lastname
//   firstname
//   addr1
//   addr2
//   title
//   city
//   state
//   zip
//   country
//   member_status
//   join_date
//   renew_date
//   home_phone
//   work_phone
//
require dirname(__DIR__, 3) . '/include/cp_header.php';
xoops_cp_header();
$xTheme->loadModuleAdminMenu(6, _PROFILE_MI_USERS);

// to retrieve all the form's variables and values (avoids the usage of individual $_POST declarations)
foreach ($_POST as $k => $v) {
    ${$k} = $v;

    //echo "<br>".$k." - ".$v;
}

global $xoopsUser;

// check to make sure the user is authorized to make changes
$module_id = $xoopsModule->getVar('mid');
$my_groups = $xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gpermHandler = xoops_getHandler('groupperm');
//$perm_list = $gpermHandler->getItemIds(_MEMB_EDIT_PERM_DET_NAME, $my_groups, $module_id);
//if (! in_array(_MEMB_GPERM_EXPORT, $perm_list)) {
//    redirect_header( "index.php", 1, "Insufficient Permissions");
//}

// display the form if it has not been posted
if (!isset($post)) {
    //require XOOPS_ROOT_PATH."/header.php";
    //$email = $xoopsUser->getVar('email','E');
    include '../include/export-form.inc.php'; // include the form
    //require XOOPS_ROOT_PATH."/footer.php";
    xoops_cp_footer();

    exit();
}

$myts = MyTextSanitizer::getInstance();

// Passed in variables
$groupid = $_REQUEST['groupid'] ?? 0;
$renew_time = strtotime($exptime['date']) + $exptime['time'];

// Column titles
$cur_line = '"Member ID","LN","FN","Addr1","Addr2","Title","City","St","Zip","Country","Status","JoinDate","ExpDate","HPhone","WPhone","Email Address","Web Page","DP"';
$message = $cur_line . "\r\n";

// Select members and output CSV records
// Construct SQL statement
$sql = 'SELECT * FROM ' . $xoopsDB->prefix('groups_users_link') . ' g, ' . $xoopsDB->prefix('users') . ' u, ' . $xoopsDB->prefix('user_profile') . ' p WHERE u.uid = g.uid AND u.uid = p.profileid';
if ($groupid > 0) {
    $sql .= ' AND g.groupid = ' . $groupid;
}
if (isset($use_exptime)) {
    $sql .= ' AND p.renew_date > ' . $renew_time;
}

$res = $xoopsDB->query($sql);
if (false === $res) {
    die('SQL Error: ' . $sql);
}
while (false !== ($record = $xoopsDB->fetchArray($res))) {
    if (_BIOREG_DATE_LIFE == $record['renew_date']) {
        $record['renew_date'] = _BIOREG_DATE_LIFE_DESC;
    }

    if (_BIOREG_DATE_BLANK == $record['renew_date']) {
        $record['renew_date'] = '';
    }

    if (_BIOREG_DATE_BLANK == $record['join_date']) {
        $record['join_date'] = '';
    }

    //if ($record['birth_date'] == _BIOREG_DATE_BLANK) { $record['birth_date'] = ""; }

    $cur_line = '"'
                . $record['member_id']
                . '","'
                . $record['lastname']
                . '","'
                . $record['firstname']
                . '","'
                . $record['addr1']
                . '","'
                . $record['addr2']
                . '","'
                . $record['title']
                . '","'
                . $record['city']
                . '","'
                . $record['state']
                . '","'
                . $record['zip']
                . '","'
                . $record['country']
                . '","'
                . $record['member_status']
                . '","'
                . $record['join_date']
                . '","'
                . $record['renew_date']
                . '","'
                . $record['home_phone']
                . '","'
                . $record['work_phone']
                . '","'
                . $record['email']
                . '","'
                . $record['url']
                . '",""';

    //echo '<br>"'.$cur_line;

    $message .= $cur_line . "\r\n";
}

/* Disabling email 8/5/05
// Send email if requested
if (isset($send_email) and ($email_addr > "")) {
    global $xoopsMailer;
    global $xoopsModuleConfig;

    $xoopsMailer =& getMailer();
    $xoopsMailer->setToEmails($email_addr);
    $xoopsMailer->setFromName('AKA website');
    $xoopsMailer->setFromEmail('webmaster@aka.org');
    $xoopsMailer->setSubject('Membership export - generated '.strftime('%m/%d/%Y %H:%M'));
    $xoopsMailer->setBody($message);
    $xoopsMailer->useMail();
    $xoopsMailer->send(true);
    $messagesent = $xoopsMailer->getSuccess();
    $messagesent .= $xoopsMailer->getErrors();

} else {
*/
// Write to file and send as download
$filename = 'export-file.csv';
$fp = fopen(XOOPS_ROOT_PATH . '/modules/profile/cache/' . $filename, 'wb');
fwrite($fp, $message);
fclose($fp);
$mime_type = 'application/octet-stream';
header('Content-Type: ' . $mime_type);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Pragma: no-cache');
echo $message;
exit;
//}

xoops_cp_footer();
