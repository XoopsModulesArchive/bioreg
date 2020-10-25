<?php

// ------------------------------------------------------------------------- //
//                     Membership Module for Xoops                           //
// ------------------------------------------------------------------------- //
// Author: Dennis Heltzel (dheltzel)                                         //
// Purpose: Registry for populations of biological organisms                 //
// email: dennis@keystonekilly.org                                           //
// URLs: http://www.keystonekilly.org                                        //
//---------------------------------------------------------------------------//
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//---------------------------------------------------------------------------//

// include the form loader
require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
// create the form object
$grpselect_form = new XoopsThemeForm(_BIOREG_MI_EXP_TITLE, 'membership', 'export.php');

// create select box for the groups
// we don't use XoopsFormSelectGroup because we want to include an entry for "(all groups)"
$grp_select = new XoopsFormSelect(_BIOREG_MI_GRP_EXP_TITLE, 'groupid');

$user_in = $xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

$memberHandler = xoops_getHandler('member');
$groups = $memberHandler->getGroups();
$grp_select->addOption(0, '(all groups)');
foreach ($groups as $group) {
    if (in_array($group->getVar('groupid'), $user_in, true)) {
        $grp_select->addOption($group->getVar('groupid'), $group->getVar('name'));
    }
}
$grpselect_form->addElement($grp_select);

// create renew date entry
$renew_sel = new XoopsFormElementTray(_BIOREG_MI_EXP_EXP_TITLE, '&nbsp;');
// Display checkbox, initially checked.
$renew_chk_val = 1;
$checkbox1 = new XoopsFormCheckBox('', 'use_exptime', !$renew_chk_val);
$checkbox1->addOption($renew_chk_val, '&nbsp;');
$renew_sel->addElement($checkbox1);
$renew_sel->addElement(new XoopsFormDateTime('&nbsp;', 'exptime'));
$grpselect_form->addElement($renew_sel);

/* Disabling email 8/5/05
// create check box for email
$email_sel = new XoopsFormElementTray(_BIOREG_MI_EXP_EMAIL_TITLE, '&nbsp;');
// Display checkbox, initially checked.
$email_chk_val = 1;
$checkbox2 = new XoopsFormCheckBox('', "send_email", !$email_chk_val);
$checkbox2->addOption($email_chk_val, '&nbsp;');
$email_sel->addElement($checkbox2);
$email_sel->addElement(new XoopsFormText(_BIOREG_MI_EXP_EMAIL_DESC, "email_addr", 20, 20, $email));
$grpselect_form->addElement($email_sel);
*/

// create the submit button
$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormButton('', 'post', _BIOREG_MI_SELECT, 'submit'));
$grpselect_form->addElement($button_tray);

// display the form, unless the display is via a template
$grpselect_form->display();

//$m_expire = strtotime($exptime['date']) + $exptime['time'];
