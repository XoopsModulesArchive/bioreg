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
$GLOBALS['xoopsOption']['template_main'] = 'bioreg-manage_listings.html';

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

if (!$xoopsUser) {
    redirect_header(XOOPS_URL . '/modules/bioreg/index.php', 1, _BIOREG_NO_PERMISSION);

    exit;
}

// Include the bioreg functions
include 'language/english/admin.php';
include 'include/bioreg_funcs.php';

if (!has_perm(_BR_GPERM_ADD_LISTING)) {
    redirect_header(XOOPS_URL . '/modules/bioreg/index.php', 1, _BIOREG_NO_PERMISSION);

    exit;
}

if (has_perm(_BR_GPERM_ADD_POP)) {
    $xoopsTpl->assign('can_add_populations', 'y');
} else {
    $xoopsTpl->assign('can_add_populations', '');
}

$nomen_email = $xoopsModuleConfig['nomen_email'];
$xoopsTpl->assign('nomen_email', $nomen_email);

// Code Assignments - this is where the codes are actually defined.
$xoopsTpl->assign('typeid', ['F', 'E', 'FE', 'S', 'W', 'R']);
$xoopsTpl->assign('typedesc', [_MI_BIOREG_TYPE_F, _MI_BIOREG_TYPE_E, _MI_BIOREG_TYPE_FE, _MI_BIOREG_TYPE_S, _MI_BIOREG_TYPE_W, _MI_BIOREG_TYPE_R]);

$xoopsTpl->assign('statusid', ['A', 'M', 'N', '']);
$xoopsTpl->assign('statusdesc', [_MI_BIOREG_STATUS_A, _MI_BIOREG_STATUS_M, _MI_BIOREG_STATUS_N, _MI_BIOREG_STATUS__]);
//$xoopsTpl->assign('statusdesc', array('Available','Maintain','New','none'));

$xoopsTpl->assign('visibleid', ['A', 'M', 'S']);
$xoopsTpl->assign('visibledesc', [_MI_BIOREG_VIS_A, _MI_BIOREG_VIS_M, _MI_BIOREG_VIS_S]);
//$xoopsTpl->assign('visibledesc', array('All','AKA','SMC'));

if (isset($_REQUEST['bio_pop2sel'])) {
    if (isset($_REQUEST['deleteconfirmed'])) {
        remove_bio_listing($_REQUEST['bio_pop2sel']);

        redirect_header(XOOPS_URL . '/modules/bioreg/bioreg-manage_listings.php', 1, _MI_BIOREG_DEL_MSG);
    } elseif (isset($_REQUEST['delete'])) {
        $xoopsTpl->assign('delete', 'y');

        $xoopsTpl->assign('bio_pop2sel', $_REQUEST['bio_pop2sel']);
    } else {
        $xoopsTpl->assign('delete', '');
    }

    if (isset($_REQUEST['sendconfirmed'])) {
        send_bio_listing($_REQUEST['bio_pop2sel']);

        redirect_header(XOOPS_URL . '/modules/bioreg/bioreg-manage_listings.php', 1, _MI_BIOREG_SEND_MSG);
    } elseif (isset($_REQUEST['send'])) {
        $xoopsTpl->assign('send', 'y');

        $xoopsTpl->assign('bio_pop2sel', $_REQUEST['bio_pop2sel']);
    } else {
        $xoopsTpl->assign('send', '');
    }
}

if (isset($_REQUEST['update'])) {
    foreach (array_keys($_REQUEST['listings_array']) as $listing) {
        edit_bio_listing($listing, $_REQUEST['price'][$listing], $_REQUEST['comment'][$listing], $_REQUEST['type'][$listing], $_REQUEST['status'][$listing], $_REQUEST['visible'][$listing]);
    }

    unset($_REQUEST['update']);
}

if (isset($_REQUEST['qeadd'])) {
    bio_quick_entry($_REQUEST['bio_pop2add']);

    unset($_REQUEST['qeadd']);
}

// Add listings code
if (isset($_REQUEST['sel_genus'])) {
    unset($_REQUEST['species_name']);
}

$genus = $_REQUEST['genus_name'] ?? '';
$xoopsTpl->assign('genus', $genus);

if (isset($_REQUEST['list_pops'])) {
    $list_pops = 'on';
} else {
    $list_pops = '';
}

if (isset($_REQUEST['species_name'])) {
    $species = $_REQUEST['species_name'];

    $list_pops = 'on';
} else {
    $species = '';
}

$xoopsTpl->assign('genus', $genus);
$xoopsTpl->assign('species', $species);
$xoopsTpl->assign('list_pops', $list_pops);

// Add new population
if (isset($_REQUEST['add_pop'])) {
    add_bio_pop($genus, $species, $_REQUEST['new_pop'], '', '', 'N');

    unset($_REQUEST['add_pop']);
}

// Populate arrays
$bio_listings_by_user = [];
$bio_listings_by_user = get_bio_listings_by_user();
$xoopsTpl->assign_by_ref('bio_listings_by_user', $bio_listings_by_user);
$numitems = count($bio_listings_by_user);
$xoopsTpl->assign('numitems', $numitems);

$bio_genus = [];
$bio_genus = get_bio_genus();
$xoopsTpl->assign_by_ref('bio_genus', $bio_genus);

if (isset($genus)) {
    $bio_species_by_genus = [];

    $bio_species_by_genus = get_bio_species_by_genus($genus);

    $xoopsTpl->assign_by_ref('bio_species_by_genus', $bio_species_by_genus);

    $bio_pop_by_genus = [];

    $bio_pop_by_genus = get_bio_pop($genus, $species);

    $xoopsTpl->assign_by_ref('bio_pop_by_genus', $bio_pop_by_genus);
}

// Include the page footer
require XOOPS_ROOT_PATH . '/footer.php';
