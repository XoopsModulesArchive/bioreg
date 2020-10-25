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
require_once XOOPS_ROOT_PATH . '/modules/bioreg/language/english/modinfo.php';

function statistics()
{
    global $xoopsDB;

    $genus_table = $xoopsDB->prefix('bioreg_genus');

    $species_table = $xoopsDB->prefix('bioreg_species');

    $pop_table = $xoopsDB->prefix('bioreg_population');

    $listings_table = $xoopsDB->prefix('bioreg_listings');

    $sql = 'SELECT count(*) FROM ' . $genus_table;

    [$genus_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    $sql = 'SELECT count(*) FROM ' . $species_table;

    [$species_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    $sql = 'SELECT count(*) FROM ' . $pop_table . " WHERE type <> 'I'";

    [$pop_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    $sql = 'SELECT count(*) FROM ' . $listings_table;

    [$listings_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    $sql = 'SELECT count(distinct listings_uid) FROM ' . $listings_table . " WHERE listings_status = 'A'";

    [$seller_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    // count of listings that are visible to all

    $sql = 'SELECT count(*) FROM ' . $listings_table . " WHERE listings_visible = 'A' AND listings_status = 'A'";

    [$anon_listings_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    // count of listings that are visible to members

    $sql = 'SELECT count(*) FROM ' . $listings_table . " WHERE listings_visible IN ('A','M') AND listings_status = 'A'";

    [$memb_listings_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    // count of listings visible to SMC

    $sql = 'SELECT count(*) FROM ' . $listings_table . " WHERE listings_type <> 'W'";

    [$smc_listings_count] = $xoopsDB->fetchRow($xoopsDB->query($sql));

    // Statistics about the number of records in the tables

    $block['database'] = "<TABLE BORDER=1 RULES='all' >";

    $block['database'] .= '<TR><TH>' . _BR_STATS_HEAD1 . '</TH><TH>' . _BR_STATS_HEAD2 . '</TH></TR>';

    $block['database'] .= '<TR><TD>' . _BR_STATS_T1R1 . '</TD><TD>' . $genus_count . '</TD></TR>';

    $block['database'] .= '<TR><TD>' . _BR_STATS_T1R2 . '</TD><TD>' . $species_count . '</TD></TR>';

    $block['database'] .= '<TR><TD>' . _BR_STATS_T1R3 . '</TD><TD>' . $pop_count . '</TD></TR>';

    $block['database'] .= '<TR><TD>' . _BR_STATS_T1R4 . '</TD><TD>' . $listings_count . '</TD></TR>';

    $block['database'] .= '<TR><TD>' . _BR_STATS_T1R5 . '</TD><TD>' . $seller_count . '</TD></TR>';

    $block['database'] .= '<TR><TD>' . _BR_STATS_T1R6 . '</TD><TD>' . round($memb_listings_count / $seller_count, 2) . '</TD></TR>';

    $block['database'] .= '</TABLE>';

    // Statistics about the number of "available" listings"

    $block['listings'] = "<TABLE BORDER=1 RULES='all' >";

    $block['listings'] .= '<TR><TH>' . _BR_STATS_HEAD3 . '</TH><TH></TH></TR>';

    $block['listings'] .= '<TR><TD>' . _BR_STATS_T2R1 . '</TD><TD>' . $anon_listings_count . '</TD></TR>';

    $block['listings'] .= '<TR><TD>' . _BR_STATS_T2R2 . '</TD><TD>' . $memb_listings_count . '</TD></TR>';

    $block['listings'] .= '<TR><TD>' . _BR_STATS_T2R3 . '</TD><TD>' . $smc_listings_count . '</TD></TR>';

    $block['listings'] .= '</TABLE>';

    return $block;
}
