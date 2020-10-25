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
$GLOBALS['xoopsOption']['template_main'] = 'bioreg-info.html';

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

// Include the bireg functions
include 'language/english/admin.php';
include 'include/bioreg_funcs.php';

//echo _BR_GPERM_ADD_GEN;
if (has_perm(_BR_GPERM_ADD_GEN)) {
    $can_add_genus = 'y';
} else {
    $can_add_genus = '';
}
$xoopsTpl->assign('can_add_genus', $can_add_genus);

if (has_perm(_BR_GPERM_ADD_SPEC)) {
    $can_add_species = 'y';
} else {
    $can_add_species = '';
}
$xoopsTpl->assign('can_add_species', $can_add_species);

if (has_perm(_BR_GPERM_ADD_POP)) {
    $can_add_populations = 'y';
} else {
    $can_add_populations = '';
}
$xoopsTpl->assign('can_add_populations', $can_add_populations);

$xoopsTpl->assign('statusid', ['A', 'N', 'I']);
$xoopsTpl->assign('statusdesc', [_MI_BIOREG_STATUS_A, _MI_BIOREG_STATUS_N, _MI_BIOREG_STATUS_I]);

if (isset($_REQUEST['sel_genus'])) {
    unset($_REQUEST['species_name']);

    unset($_REQUEST['pop_name']);
}

if (isset($_REQUEST['sel_species'])) {
    unset($_REQUEST['pop_name']);
}

if (isset($_REQUEST['genus_name'])) {
    $genus = $_REQUEST['genus_name'];

    $xoopsTpl->assign('genus', $genus);

    $xoopsTpl->assign('new_genus_name', $genus);

    $xoopsTpl->assign('new_family_name', get_family_name($genus));
}
if (isset($_REQUEST['species_name'])) {
    $species = $_REQUEST['species_name'];

    $xoopsTpl->assign('species', $species);

    $xoopsTpl->assign('new_species_name', $species);

    $xoopsTpl->assign('new_group_name', get_group_name($genus, $species));
}
//echo "pop_name: ".$_REQUEST["pop_name"];
if (isset($_REQUEST['pop_name'])) {
    $pop = $_REQUEST['pop_name'];

    $xoopsTpl->assign('pop', $pop);

    $xoopsTpl->assign('pop_selected', 'y');

    $xoopsTpl->assign('new_pop_name', $pop);

    $xoopsTpl->assign('new_common_name', get_common_name($genus, $species, $pop));

    $xoopsTpl->assign('new_url', get_url($genus, $species, $pop));

    $xoopsTpl->assign('new_pop_status', get_pop_status($genus, $species, $pop));
}

if ('y' == $can_add_genus) {
    if (isset($_REQUEST['add_genus'])) {
        add_bio_genus($_REQUEST['new_genus']);
    }

    if (isset($_REQUEST['edit_bio_genus'])) {
        edit_bio_genus($_REQUEST['genus_name'], $_REQUEST['new_genus_name'], $_REQUEST['new_family_name']);
    }
}

if ('y' == $can_add_species) {
    if (isset($_REQUEST['add_species'])) {
        add_bio_species($_REQUEST['genus_name'], $_REQUEST['new_species']);
    }

    if (isset($_REQUEST['edit_bio_species'])) {
        edit_bio_species($_REQUEST['genus_name'], $_REQUEST['species_name'], $_REQUEST['new_species_name'], $_REQUEST['new_group_name']);
    }
}

if ('y' == $can_add_populations) {
    if (isset($_REQUEST['add_pop'])) {
        if ('y' == $can_add_species) {
            $pop_status = 'A';
        } else {
            $pop_status = 'N';
        }

        add_bio_pop($_REQUEST['genus_name'], $_REQUEST['species_name'], $_REQUEST['new_pop'], $common_name, $pop_url, $pop_status);

        unset($_REQUEST['add_pop']);
    }
}

if ('y' == $can_add_species) {
    if (isset($_REQUEST['upd_pop'])) {
        foreach (array_keys($_REQUEST['populations_array']) as $population) {
            edit_bio_pop($population, $_REQUEST['pop_name_arr'][$population], $_REQUEST['common_name'][$population], $_REQUEST['pop_url'][$population], $_REQUEST['status'][$population]);
        }

        unset($_REQUEST['upd_pop']);
    }
}

// Populate arrays
$for_export = '';
$bio_genus = [];
$bio_genus = get_bio_genus();
$xoopsTpl->assign_by_ref('bio_genus', $bio_genus);

if (isset($genus)) {
    $bio_species_by_genus = [];

    $bio_species_by_genus = get_bio_species_by_genus($genus);

    $xoopsTpl->assign_by_ref('bio_species_by_genus', $bio_species_by_genus);

    if (isset($species)) {
        $bio_pop_by_genus = [];

        $bio_pop_by_genus = get_bio_pop($genus, $species);

        $xoopsTpl->assign_by_ref('bio_pop_by_genus', $bio_pop_by_genus);
    } else {
        $bio_pop_by_genus = [];

        $bio_pop_by_genus = get_bio_pop($genus);

        $xoopsTpl->assign_by_ref('bio_pop_by_genus', $bio_pop_by_genus);
    }
}

// Include the page footer
require XOOPS_ROOT_PATH . '/footer.php';
