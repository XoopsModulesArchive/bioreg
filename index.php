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
$GLOBALS['xoopsOption']['template_main'] = 'bioreg-index.html';

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

// Include the bioreg functions
include 'language/english/admin.php';
include 'include/bioreg_funcs.php';

// Needed to print the module version number on the page
include 'xoops_version.php';
$xoopsTpl->assign('version', $modversion['version']);

if (!has_perm(_BR_GPERM_VIEW)) {
    redirect_header(XOOPS_URL, 1, _BIOREG_NO_PERMISSION);

    exit;
}

// Assign values of the checkboxes
if (!isset($_REQUEST['bio_prefs_set'])) {
    $showFish = 'on';

    $showEggs = 'on';

    if (isset($_REQUEST['all_types'])) {
        $showWTB = 'on';

        $showAll = 'on';

        $showNonfish = 'on';
    } else {
        $showWTB = '';

        $showAll = '';

        $showNonfish = '';
    }

    $xoopsTpl->assign('showFish', $showFish);

    $xoopsTpl->assign('showEggs', $showEggs);

    $xoopsTpl->assign('showWTB', $showWTB);

    $xoopsTpl->assign('showAll', $showAll);

    $xoopsTpl->assign('showNonfish', $showNonfish);
} else {
    $showFish = $_REQUEST['showFish'] ?? '';

    $xoopsTpl->assign('showFish', $showFish);

    $showEggs = $_REQUEST['showEggs'] ?? '';

    $xoopsTpl->assign('showEggs', $showEggs);

    $showWTB = $_REQUEST['showWTB'] ?? '';

    $xoopsTpl->assign('showWTB', $showWTB);

    $showAll = $_REQUEST['showAll'] ?? '';

    $xoopsTpl->assign('showAll', $showAll);

    $showNonfish = $_REQUEST['showNonfish'] ?? '';

    $xoopsTpl->assign('showNonfish', $showNonfish);
}

$filter_name = $_REQUEST['filter_name'] ?? '';
$xoopsTpl->assign('filter_name', $filter_name);

$filter_comment = $_REQUEST['filter_comment'] ?? '';
$xoopsTpl->assign('filter_comment', $filter_comment);

$filter_seller = $_REQUEST['filter_seller'] ?? '';
$xoopsTpl->assign('filter_seller', $filter_seller);

$xoopsTpl->assign('sortid', ['I', 'S', 'N']);
$xoopsTpl->assign('sortdesc', ['Item', 'Seller', 'Newest']);

if (isset($_REQUEST['seller_sel'])) {
    $xoopsTpl->assign('seller_sel', $_REQUEST['seller_sel']);

    $seller_info = get_seller_info($_REQUEST['seller_sel']);

    $xoopsTpl->assign('seller_info', $seller_info);
}

if (isset($_REQUEST['listing_sel'])) {
    $xoopsTpl->assign('listing_sel', 'y');

    $listing_info = get_listing_info($_REQUEST['listing_key']);

    $xoopsTpl->assign('listing_info', $listing_info);
} else {
    $xoopsTpl->assign('listing_sel', '');
}

$popkey = $_REQUEST['popkey'] ?? 0;

$xoopsTpl->assign('popkey', $popkey);

$sort = $_REQUEST['sort'] ?? 'I';
$xoopsTpl->assign('sort', $sort);

// Populate arrays
$bio_listings = [];
$bio_listings = get_bio_listings($showFish, $showEggs, $showWTB, $showAll, $showNonfish, $filter_name, $filter_comment, $filter_seller, $popkey, $sort);
$xoopsTpl->assign_by_ref('bio_listings', $bio_listings);

// Include the page footer
require XOOPS_ROOT_PATH . '/footer.php';
