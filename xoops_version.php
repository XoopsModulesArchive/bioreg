<?php

// ------------------------------------------------------------------------- //
//                     Biological Registry for Xoops                         //
//                              Version:  1.0                                //
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
global $xoopsDB;

$modversion['name'] = _MI_BIOREG_NAME;
$modversion['version'] = '1.0.003';
$modversion['description'] = _MI_BIOREG_DESC;
$modversion['credits'] = 'Dennis Heltzel (bioreg@keystonekilly.org)';
$modversion['author'] = 'written for Xoops<br>by Dennis Heltzel<br>http://www.keystonekilly.org';
$modversion['license'] = 'GPL LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'bioreg_slogo.png';
$modversion['dirname'] = 'bioreg';
$modversion['help'] = 'bioreg-help.html';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;

global $xoopsModule;
if (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $modversion['dirname']) {
    global $xoopsUser;

    global $xoopsModuleConfig;

    if ($xoopsUser) {
        $groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    $module_id = $xoopsModule->getVar('mid');

    $gpermHandler = xoops_getHandler('groupperm');

    $gperm_id = 4;  // hard-coded so we don't have to load the lang file

    if ($gpermHandler->checkRight('Access Permission', $gperm_id, $groups, $module_id)) {
        $modversion['sub'][1]['name'] = _MI_BIOREG_MANAGE_LISTING;

        $modversion['sub'][1]['url'] = 'bioreg-manage_listings.php';

        $modversion['sub'][2]['name'] = _MI_BIOREG_INFO;

        $modversion['sub'][2]['url'] = 'bioreg-info.php';

        $modversion['sub'][3]['name'] = _MI_BIOREG_NEWITEM;

        $modversion['sub'][3]['url'] = 'bioreg-newitem.php';

        $menu_custname1 = $xoopsModuleConfig['menu_custname1'];

        $menu_custurl1 = $xoopsModuleConfig['menu_custurl1'];

        if (!empty($menu_custname1) and !empty($menu_custurl1)) {
            $modversion['sub'][4]['name'] = $menu_custname1;

            $modversion['sub'][4]['url'] = $menu_custurl1;
        }

        $menu_custname2 = $xoopsModuleConfig['menu_custname2'];

        $menu_custurl2 = $xoopsModuleConfig['menu_custurl2'];

        if (!empty($menu_custname2) and !empty($menu_custurl2)) {
            $modversion['sub'][5]['name'] = $menu_custname2;

            $modversion['sub'][5]['url'] = $menu_custurl2;
        }
    } else {
        $modversion['sub'][1]['name'] = _MI_BIOREG_INFO;

        $modversion['sub'][1]['url'] = 'bioreg-info.php';
    }
}

// Templates
$modversion['templates'][1]['file'] = 'bioreg-index.html';
$modversion['templates'][1]['description'] = 'Biological Registry main template file';

$modversion['templates'][2]['file'] = 'bioreg-manage_listings.html';
$modversion['templates'][2]['description'] = 'Bioreg Manage Listings template file';

$modversion['templates'][3]['file'] = 'bioreg-info.html';
$modversion['templates'][3]['description'] = 'Bioreg Admin template file';

$modversion['templates'][4]['file'] = 'bioreg-list.html';
$modversion['templates'][4]['description'] = 'Bioreg List/Export template file';

// Config Settings (only for modules that need config settings generated automatically)
$modversion['hasconfig'] = 1;

/* name of config option for accessing its specified value. i.e. $xoopsModuleConfig['storyhome']
title of this config option displayed in config settings form
description of this config option displayed under title
form element type used in config form for this option. can be one of either textbox, textarea, select, select_multi, yesno, group, group_multi
value type of this config option. can be one of either int, text, float, array, or other
form type of group_multi, select_multi must always be value type of array
the default value for this option
ignore it if no default
'yesno' formtype must be either 0(no) or 1(yes)
*/

$modversion['config'][1]['name'] = 'nomen_email';
$modversion['config'][1]['title'] = '_MI_BIOREG_NOM_EMAIL_TITLE';
$modversion['config'][1]['description'] = '_MI_BIOREG_NOM_EMAIL_DESC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';

$modversion['config'][2]['name'] = 'fel_email';
$modversion['config'][2]['title'] = '_MI_BIOREG_FEL_EMAIL_TITLE';
$modversion['config'][2]['description'] = '_MI_BIOREG_FEL_EMAIL_DESC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';

$modversion['config'][3]['name'] = 'menu_custname1';
$modversion['config'][3]['title'] = '_MI_BIOREG_MENU_CUSTN1_TITLE';
$modversion['config'][3]['description'] = '_MI_BIOREG_MENU_CUSTN1_DESC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'text';

$modversion['config'][4]['name'] = 'menu_custurl1';
$modversion['config'][4]['title'] = '_MI_BIOREG_MENU_CUSTURL1_TITLE';
$modversion['config'][4]['description'] = '_MI_BIOREG_MENU_CUSTURL1_DESC';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'text';

$modversion['config'][5]['name'] = 'menu_custname2';
$modversion['config'][5]['title'] = '_MI_BIOREG_MENU_CUSTN2_TITLE';
$modversion['config'][5]['description'] = '_MI_BIOREG_MENU_CUSTN2_DESC';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'text';

$modversion['config'][6]['name'] = 'menu_custurl2';
$modversion['config'][6]['title'] = '_MI_BIOREG_MENU_CUSTURL2_TITLE';
$modversion['config'][6]['description'] = '_MI_BIOREG_MENU_CUSTURL2_DESC';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'text';

// Search
$modversion['hasSearch'] = 0;

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'bioreg_genus';
$modversion['tables'][1] = 'bioreg_species';
$modversion['tables'][2] = 'bioreg_population';
$modversion['tables'][3] = 'bioreg_listings';
