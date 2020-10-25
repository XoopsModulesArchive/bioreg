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

// header include
require dirname(__DIR__, 2) . '/mainfile.php';

// We must always set our main template before including the header
$GLOBALS['xoopsOption']['template_main'] = 'bioreg-edit_species.html';

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

// Include the bireg functions
include 'language/english/admin.php';
include 'include/bioreg_funcs.php';

// get list of permissions
$bio_perms = [];
$bio_perms = get_perms();

$can_add_species = in_array(_BR_GPERM_ADD_SPEC, $bio_perms, true) ? 'y' : '';
$xoopsTpl->assign('can_add_species', $can_add_species);

if (isset($_REQUEST['genus'])) {
    $genus = $_REQUEST['genus'];

    $xoopsTpl->assign('genus', $genus);
}
if (isset($_REQUEST['species'])) {
    $species = $_REQUEST['species'];

    $xoopsTpl->assign('species', $species);
}
if (isset($_REQUEST['pop'])) {
    $pop = $_REQUEST['pop'];

    $xoopsTpl->assign('pop', $pop);

    $xoopsTpl->assign('pop_selected', 'y');
} else {
    $xoopsTpl->assign('pop_selected', '');
}

if (('y' == $can_add_species) and (isset($_REQUEST['sel_genus']))) {
    change_genus($genus, $species, $_REQUEST['genus_name']);
}

// Populate arrays
$bio_genus = [];
$bio_genus = get_bio_genus();
$xoopsTpl->assign_by_ref('bio_genus', $bio_genus);

if (isset($genus)) {
    $bio_species_by_genus = [];

    $bio_species_by_genus = get_bio_species_by_genus($genus);

    $xoopsTpl->assign_by_ref('bio_species_by_genus', $bio_species_by_genus);
}

// Include the page footer
require XOOPS_ROOT_PATH . '/footer.php';
