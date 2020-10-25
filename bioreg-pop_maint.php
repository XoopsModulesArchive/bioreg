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
$GLOBALS['xoopsOption']['template_main'] = 'bioreg-pop_maint.html';

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

// create arrays for select boxes
$xoopsTpl->assign('statusid', ['A', 'N', 'I']);
$xoopsTpl->assign('statusdesc', [_MI_BIOREG_POP_STATUS_A, _MI_BIOREG_POP_STATUS_N, _MI_BIOREG_POP_STATUS_I]);

$status = $_REQUEST['status'] ?? '';

if (isset($_REQUEST['popkey']) and (isset($_REQUEST['delete']))) {
    del_bio_pop($_REQUEST['popkey']);

    redirect_header(XOOPS_URL . '/modules/bioreg/index.php', 5);
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
$bio_pop_by_genus = [];
$bio_pop_by_genus = get_bio_pop('', '', $status);
$xoopsTpl->assign_by_ref('bio_pop_by_genus', $bio_pop_by_genus);

// Include the page footer
require XOOPS_ROOT_PATH . '/footer.php';
