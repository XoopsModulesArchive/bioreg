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
require dirname(__DIR__, 3) . '/mainfile.php';

// We must always set our main template before including the header
$GLOBALS['xoopsOption']['template_main'] = 'bioreg-list.html';

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

// Include the bireg functions
include '../language/english/admin.php';
include '../include/bioreg_funcs.php';

if (isset($_REQUEST['export'])) {
    $for_export = 'y';
} else {
    $for_export = '';
}
$xoopsTpl->assign('for_export', $for_export);

// Populate arrays
$bio_pop_list = [];
$bio_pop_list = get_bio_pop();
$xoopsTpl->assign_by_ref('bio_pop_list', $bio_pop_list);

$bio_list_arr = [];
$bio_list_arr = get_bio_listings_for_export();
$xoopsTpl->assign_by_ref('bio_list_arr', $bio_list_arr);

// Include the page footer
require XOOPS_ROOT_PATH . '/footer.php';
