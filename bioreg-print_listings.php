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

if (!$xoopsUser) {
    redirect_header(XOOPS_URL . '/modules/bioreg/index.php', 1, _BIOREG_NO_PERMISSION);

    exit;
}

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

// Include the bioreg functions
include 'language/english/admin.php';
include 'include/bioreg_funcs.php';

// get list of permissions
$bio_perms = [];
$bio_perms = get_perms();

if (!in_array(_BR_GPERM_ADD_LISTING, $bio_perms, true)) {
    redirect_header(XOOPS_URL . '/modules/bioreg/index.php', 1, _BIOREG_NO_PERMISSION);

    exit;
}

$user2print = 0;
// if asking to edit another users listings, check permissions here
if (isset($_REQUEST['seller'])) {
    if (in_array(_BR_GPERM_ADM_LIST, $bio_perms, true)) {
        $user2print = $_REQUEST['seller'];
    } else {
        redirect_header(XOOPS_URL . '/modules/bioreg/index.php', 1, _BIOREG_NO_PERMISSION);

        exit;
    }
}

// Populate arrays
$bio_listings_by_user = [];
$bio_listings_by_user = get_bio_listings_by_user($user2print);
$numitems = count($bio_listings_by_user);

echo '<table border="2" frame="box" rules="all">';
echo '<tr>';
echo '<td width="50%"><center><b>' . _MI_BIOREG_LABEL_COL1 . '</td>';
echo '<td width="15%"><center><b>' . _MI_BIOREG_LABEL_COL2 . '</td>';
echo '<td width="20%"><center><b>' . _MI_BIOREG_LABEL_COL3 . '</td>';
echo '<td width="5%"><center><b>' . _MI_BIOREG_LABEL_COL4 . '</td>';
echo '<td width="5%"><center><b>' . _MI_BIOREG_LABEL_COL5 . '</td>';
echo '<td width="5%"><center><b>' . _MI_BIOREG_LABEL_COL6 . '</td>';
echo '</tr>';
foreach ($bio_listings_by_user as $item) {
    // Expand codes

    switch ($item['listings_type']) {
        case 'F':
            $item['listings_type'] = 'Fish';
            break;
        case 'E':
            $item['listings_type'] = 'Eggs';
            break;
        case 'FE':
            $item['listings_type'] = 'Both';
            break;
        case 'W':
            $item['listings_type'] = 'WTB';
            break;
        case 'R':
            $item['listings_type'] = 'Reg';
            break;
        case 'S':
            $item['listings_type'] = 'Sup';
            break;
    }

    switch ($item['listings_status']) {
        case 'A':
            $item['listings_status'] = 'Avail';
            break;
        case 'M':
            $item['listings_status'] = 'Maint';
            break;
        case 'N':
            $item['listings_status'] = 'New';
            break;
    }

    switch ($item['listings_visible']) {
        case 'A':
            $item['listings_visible'] = 'All';
            break;
        case 'M':
            $item['listings_visible'] = 'AKA';
            break;
        case 'S':
            $item['listings_visible'] = 'SMC';
            break;
    }

    echo '<tr>';

    echo '<td><i>' . $item['genus_name'] . ' ' . $item['species_name'] . '</i> <b>' . $item['pop_name'] . '</b></td>';

    echo '<td>' . $item['listings_price'] . '</td>';

    echo '<td>' . $item['listings_comment'] . '</td>';

    echo '<td>' . $item['listings_type'] . '</td>';

    echo '<td>' . $item['listings_status'] . '</td>';

    echo '<td>' . $item['listings_visible'] . '</td>';

    echo '</tr>';
}
echo '</table>';
