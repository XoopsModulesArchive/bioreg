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
//include "../language/english/admin.php";

$genus_table = $xoopsDB->prefix('bioreg_genus');
$species_table = $xoopsDB->prefix('bioreg_species');
$pop_table = $xoopsDB->prefix('bioreg_population');
$listings_table = $xoopsDB->prefix('bioreg_listings');
//$user_table = $xoopsDB->prefix('users');

// General use functions
function has_perm($perm_itemid)
{
    global $xoopsModule;

    global $xoopsUser;

    $perm_name = 'Access Permission';

    if ($xoopsUser) {
        $groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    $module_id = $xoopsModule->getVar('mid');

    $gpermHandler = xoops_getHandler('groupperm');

    return $gpermHandler->checkRight($perm_name, $perm_itemid, $groups, $module_id);
}

function get_seller_info($seller_id)
{
    //    global $xoopsDB;

    //    global $user_table;

    //    global $xoopsUser;

    $seller_info = [];

    //echo "seller_id:".$seller_id;

    $memberHandler = xoops_getHandler('member');

    $Seller = new XoopsUser($seller_id);

    $seller_info['name'] = $Seller->getVar('name', 'E');

    $seller_info['uname'] = $Seller->getVar('uname', 'E');

    $seller_info['url'] = $Seller->getVar('url', 'E');

    $seller_info['email'] = $Seller->getVar('email', 'E');

    $seller_info['user_icq'] = $Seller->getVar('user_icq', 'E');

    $seller_info['user_from'] = $Seller->getVar('user_from', 'E');

    $seller_info['user_intrest'] = $Seller->getVar('user_intrest', 'E');

    $seller_info['bio'] = $Seller->getVar('bio', 'E');

    return $seller_info;
    /*
        $sql = "SELECT name,uname,url,email,user_icq,user_from,user_intrest,bio FROM ".$user_table." WHERE uname='".$seller."'";
        $res = $xoopsDB->queryF($sql);
        if ( $res ) {
            $row = $xoopsDB->fetchArray($res);
            return $row;
        } else {
            print "Error getting seller info: ".$seller;
            return false;
        }
    */
}

// Genus table functions
function add_bio_genus($genus)
{
    global $xoopsDB;

    global $xoopsUser;

    global $genus_table;

    $uid = $xoopsUser->getVar('uid', 'E');

    $sql = 'INSERT INTO ' . $genus_table . "(genus_name,create_time,created_by,updated_by) VALUES('" . $genus . "',NOW(),'" . $uid . "','" . $uid . "')";

    //echo $sql;

    $res = $xoopsDB->queryF($sql);

    if ($res) {
        return 'ok';
    }

    return 'failed to insert ' . $genus;
}

function get_genus_key($genus_name)
{
    global $xoopsDB;

    global $genus_table;

    $sql = 'SELECT genus_key FROM ' . $genus_table . " WHERE genus_name='" . $genus_name . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

function get_family_name($genus_name)
{
    global $xoopsDB;

    global $genus_table;

    $sql = 'SELECT family_name FROM ' . $genus_table . " WHERE genus_name='" . $genus_name . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

function edit_bio_genus($old_genus_name, $new_genus_name, $family_name)
{
    global $xoopsDB;

    global $xoopsUser;

    global $genus_table;

    $uid = $xoopsUser->getVar('uid', 'E');

    $genus_key = get_genus_key($old_genus_name);

    $sql = 'UPDATE ' . $genus_table . " SET genus_name='" . $new_genus_name . "', family_name='" . $family_name . "', updated_by='" . $uid . "' WHERE genus_key=" . $genus_key;

    $xoopsDB->queryF($sql);
}

function get_bio_genus()
{
    $result = [];

    global $xoopsDB;

    global $genus_table;

    $sql = 'SELECT genus_key,genus_name,family_name FROM ' . $genus_table . ' ORDER BY genus_name';

    $res = $xoopsDB->query($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }

    $i = 0;

    while (false !== ($item = $xoopsDB->fetchArray($res))) {
        $result[$i++] = $item;
    }

    return $result;
}

// Species table functions
function add_bio_species($genus, $species)
{
    global $xoopsDB;

    global $xoopsUser;

    global $species_table;

    global $pop_table;

    $uid = $xoopsUser->getVar('uid', 'E');

    $genus_key = get_genus_key($genus);

    if ('' == $genus_key) {
        $res = add_bio_genus($genus);

        $genus_key = get_genus_key($genus);
    }

    if ($genus_key > 0) {
        $sql = 'INSERT INTO ' . $species_table . '(genus_key,species_name,create_time,created_by,updated_by) VALUES(' . $genus_key . ",'" . $species . "',NOW(),'" . $uid . "','" . $uid . "')";

        $xoopsDB->queryF($sql);

        // Add record to bio_population with an empty pop_name to make queries show "no population" as a valid option

        $species_key = get_species_key($genus, $species);

        $sql = 'INSERT INTO ' . $pop_table . '(species_key,create_time,created_by,updated_by) VALUES(' . $species_key . ",NOW(),'" . $uid . "','" . $uid . "')";

        $xoopsDB->queryF($sql);
    } else {
        print 'Error inserting: ' . $genus . ' ' . $species;
    }
}

function get_species_key($genus, $species_name)
{
    global $xoopsDB;

    global $species_table;

    $genus_key = get_genus_key($genus);

    if (0 == $genus_key) {
        add_bio_genus($genus);

        $genus_key = get_genus_key($genus);
    }

    $sql = 'SELECT species_key FROM ' . $species_table . ' WHERE genus_key=' . $genus_key . " AND species_name='" . $species_name . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

function get_group_name($genus, $species_name)
{
    global $xoopsDB;

    global $species_table;

    $genus_key = get_genus_key($genus);

    $sql = 'SELECT group_name FROM ' . $species_table . ' WHERE genus_key=' . $genus_key . " AND species_name='" . $species_name . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

function edit_bio_species($genus, $old_species_name, $new_species_name, $group_name)
{
    global $xoopsDB;

    global $xoopsUser;

    global $species_table;

    $uid = $xoopsUser->getVar('uid', 'E');

    $species_key = get_species_key($genus, $old_species_name);

    $sql = 'UPDATE ' . $species_table . " SET species_name='" . $new_species_name . "', group_name='" . $group_name . "', updated_by='" . $uid . "' WHERE species_key=" . $species_key;

    $res = $xoopsDB->queryF($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }
}

function get_bio_species_by_genus($genus)
{
    $result = [];

    global $xoopsDB;

    global $genus_table;

    global $species_table;

    $sql = 'SELECT bg.genus_name, bs.species_name, bs.species_key
 FROM ' . $genus_table . ' bg, ' . $species_table . " bs
 WHERE bg.genus_key = bs.genus_key
 AND bg.genus_name = '" . $genus . "' ORDER BY bs.species_name";

    $res = $xoopsDB->query($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }

    $i = 0;

    while (false !== ($item = $xoopsDB->fetchArray($res))) {
        $result[$i++] = $item;
    }

    return $result;
}

// Population table functions
function get_pop_key($genus, $species, $pop)
{
    global $xoopsDB;

    global $pop_table;

    $species_key = get_species_key($genus, $species);

    $sql = 'SELECT pop_key FROM ' . $pop_table . ' WHERE species_key=' . $species_key . " AND pop_name='" . $pop . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

function add_bio_pop($genus, $species, $pop = '', $common_name = '', $pop_url = '', $status = 'N')
{
    global $xoopsDB;

    global $xoopsUser;

    global $pop_table;

    // don't bother trying an insert if it already exists

    if (0 == get_pop_key($genus, $species, $pop)) {
        $uid = $xoopsUser->getVar('uid', 'E');

        $species_key = get_species_key($genus, $species);

        // if the species isn't in the db, add it

        if (0 == $species_key) {
            add_bio_species($genus, $species);

            $species_key = get_species_key($genus, $species);
        }

        $sql = 'INSERT INTO ' . $pop_table . '(species_key,pop_name,common_name,pop_url,status,create_time,created_by,updated_by) VALUES(' . $species_key . ",'" . $pop . "','" . $common_name . "','" . $pop_url . "','" . $status . "',NOW(),'" . $uid . "','" . $uid . "')";

        $res = $xoopsDB->queryF($sql);

        /*
                if ($res === false) {
                   echo "Failed to insert: ".$genus." ".$species." ".$pop."<br>";
                }
                if ($res) {
                   echo "Ins: ".$genus." ".$species." ".$pop."<br>";
                } else {
                   echo "Failed to insert: ".$genus." ".$species." ".$pop."<br>";
                   echo $sql."<br>";
                }
        */
    }
}

function get_common_name($genus, $species, $pop)
{
    global $xoopsDB;

    global $pop_table;

    $species_key = get_species_key($genus, $species);

    $sql = 'SELECT common_name FROM ' . $pop_table . ' WHERE species_key=' . $species_key . " AND pop_name='" . $pop . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

function get_url($genus, $species, $pop)
{
    global $xoopsDB;

    global $pop_table;

    $species_key = get_species_key($genus, $species);

    $sql = 'SELECT pop_url FROM ' . $pop_table . ' WHERE species_key=' . $species_key . " AND pop_name='" . $pop . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

function get_pop_status($genus, $species, $pop)
{
    global $xoopsDB;

    global $pop_table;

    $species_key = get_species_key($genus, $species);

    $sql = 'SELECT status FROM ' . $pop_table . ' WHERE species_key=' . $species_key . " AND pop_name='" . $pop . "'";

    $res = $xoopsDB->query($sql);

    if ($res) {
        [$key] = $xoopsDB->fetchRow($res);
    } else {
        $key = 0;
    }

    return $key;
}

//function edit_bio_pop($genus,$species,$old_population_name,$new_population_name,$common_name,$pop_url,$pop_status)
function edit_bio_pop($pop_key, $pop_name, $common_name, $pop_url, $pop_status)
{
    global $xoopsDB;

    global $xoopsUser;

    global $pop_table;

    $uid = $xoopsUser->getVar('uid', 'E');

    $sql = 'UPDATE ' . $pop_table . " SET pop_name='" . $pop_name . "', common_name='" . $common_name . "', pop_url='" . $pop_url . "', status='" . $pop_status . "', updated_by='" . $uid . "' WHERE pop_key=" . $pop_key;

    $xoopsDB->queryF($sql);
}

function get_bio_pop($genus = '', $species = '')
{
    $result = [];

    global $xoopsDB;

    global $genus_table;

    global $species_table;

    global $pop_table;

    global $listings_table;

    global $for_export;

    $sql = "SELECT bg.genus_name, bs.species_name, bp.pop_name, bp.pop_url, bp.common_name, bp.status, bp.pop_key, 'n' checked
 FROM " . $genus_table . ' bg, ' . $species_table . ' bs, ' . $pop_table . ' bp
 WHERE bg.genus_key = bs.genus_key
 AND bs.species_key = bp.species_key';

    if ($genus > '') {
        $sql .= " AND bg.genus_name = '" . $genus . "'";
    }

    if ($species > '') {
        $sql .= " AND bs.species_name = '" . $species . "'";
    }

    $sql .= ' ORDER BY bg.genus_name, bs.species_name, bp.pop_name';

    $res = $xoopsDB->query($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }

    $i = 0;

    while (false !== ($item = $xoopsDB->fetchArray($res))) {
        // add slashes for export listing

        if ('y' == $for_export) {
            $item['pop_name'] = addslashes($item['pop_name']);
        }

        // Count number of listings for this population

        $lsql = 'SELECT count(*) FROM ' . $listings_table . ' WHERE pop_key = ' . $item['pop_key'];

        //echo "lsql:".$lsql."<br>";

        $lres = $xoopsDB->queryF($lsql);

        [$lcount] = $xoopsDB->fetchRow($lres);

        $item['lcount'] = $lcount;

        $result[$i++] = $item;
    }

    return $result;
}

// Listing table functions
function add_bio_listing_key($uid, $uname, $pop_key, $listings_visible = 'S')
{
    global $xoopsDB;

    global $listings_table;

    $sql = 'INSERT INTO ' . $listings_table . '(listings_uid,listings_userid,pop_key,listings_visible,create_time) VALUES(' . $uid . ",'" . $uname . "'," . $pop_key . ",'" . $listings_visible . "',NOW())";

    $res = $xoopsDB->queryF($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }
}

function add_bio_listing($uname, $genus, $species, $pop, $listings_visible, $listings_price, $listings_type, $listings_status, $listings_comment, $create_time)
{
    global $xoopsDB;

    global $listings_table;

    // Find the correct population key

    $pop_key = get_pop_key($genus, $species, $pop);

    // if uid == 0, get it from the users table and the uname

    $uid = 0;

    // Add the record to the DB

    $sql = 'INSERT INTO '
           . $listings_table
           . '(pop_key, listings_uid, listings_userid, listings_price, listings_type, listings_status, listings_visible, listings_comment, create_time) VALUES('
           . $pop_key
           . ', '
           . $uid
           . ", '"
           . $uname
           . "', '"
           . $listings_price
           . "', '"
           . $listings_type
           . "', '"
           . $listings_status
           . "', '"
           . $listings_visible
           . "', '"
           . $listings_comment
           . "', '"
           . $create_time
           . "')";

    $res = $xoopsDB->queryF($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }
}

function get_listing_info($listing_key)
{
    global $xoopsDB;

    global $genus_table;

    global $species_table;

    global $pop_table;

    global $listings_table;

    $sql = 'SELECT bg.family_name, bg.genus_name, bs.group_name, bs.species_name, bp.pop_name, bp.pop_url,
 bl.listings_price, bl.listings_userid, bl.listings_comment, bp.common_name,
 bl.listings_type, bl.listings_status
 FROM ' . $genus_table . ' bg, ' . $species_table . ' bs, ' . $pop_table . ' bp, ' . $listings_table . ' bl
 WHERE bg.genus_key = bs.genus_key
 AND bs.species_key = bp.species_key
 AND bp.pop_key = bl.pop_key
 AND bl.listings_key = ' . $listing_key;

    $res = $xoopsDB->queryF($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }

    $row = $xoopsDB->fetchArray($res);

    return $row;
}

function edit_bio_listing($key, $price, $comment, $type, $status, $listings_visible)
{
    global $xoopsDB;

    global $listings_table;

    $sql = 'UPDATE ' . $listings_table . " SET listings_price='" . $price . "', listings_comment='" . $comment . "', listings_type='" . $type . "', listings_status='" . $status . "', listings_visible='" . $listings_visible . "' WHERE listings_key = " . $key;

    $xoopsDB->queryF($sql);
}

function bio_quick_entry($bio_pop2add)
{
    global $xoopsUser;

    $uid = $xoopsUser->getVar('uid', 'E');

    $uname = $xoopsUser->getVar('uname', 'E');

    foreach ($bio_pop2add as $pop_key) {
        add_bio_listing_key($uid, $uname, $pop_key, 'S');
    }
}

function remove_bio_listing($bio_pop2del)
{
    global $xoopsDB;

    global $listings_table;

    foreach ($bio_pop2del as $pop_key) {
        $sql = 'DELETE FROM ' . $listings_table . ' WHERE listings_key = ' . $pop_key;

        if (!$xoopsDB->queryF($sql)) {
            echo 'delete failed: ' . $sql;
        }
    }
}

function send_bio_listing($bio_pop2del)
{
    global $xoopsDB;

    global $xoopsUser;

    global $xoopsMailer;

    global $xoopsModuleConfig;

    global $genus_table;

    global $species_table;

    global $pop_table;

    global $listings_table;

    $result = [];

    $uname = $xoopsUser->getVar('uname', 'E');

    $fel_email = $xoopsModuleConfig['fel_email'];

    $bodytext = "Listings for the next F&EL:\n\n";

    foreach ($bio_pop2del as $pop_key) {
        $sql = 'SELECT bg.genus_name, bs.species_name, bp.pop_name, bl.listings_price, bl.listings_comment,
 bl.listings_type
 FROM ' . $genus_table . ' bg, ' . $species_table . ' bs, ' . $pop_table . ' bp, ' . $listings_table . ' bl
 WHERE bg.genus_key = bs.genus_key
 AND bs.species_key = bp.species_key
 AND bp.pop_key = bl.pop_key
 AND bl.listings_key = ' . $pop_key;

        $res = $xoopsDB->query($sql);

        if ($res) {
            $row = $xoopsDB->fetchRow($res);

            $bodytext .= $row[0] . ' ' . $row[1] . ' ' . $row[2] . ' ' . $row[3] . ' ' . $row[4] . ' ' . $row[5] . "\n";
        }
    }

    $xoopsMailer = getMailer();

    $xoopsMailer->setToEmails($fel_email);

    $xoopsMailer->setFromName('AKA website');

    $xoopsMailer->setFromEmail('fel@aka.org');

    $xoopsMailer->setSubject('F&EL Listing from ' . $uname);

    $xoopsMailer->setBody($bodytext);

    $xoopsMailer->useMail();

    $xoopsMailer->send(true);

    echo $xoopsMailer->getSuccess();

    echo $xoopsMailer->getErrors();
}

function get_bio_listings($showFish, $showEggs, $showWTB, $showAll, $showNonfish, $filter_name, $filter_comment, $filter_seller, $popkey = 0, $sort = 'I')
{
    $result = [];

    global $xoopsDB;

    global $genus_table;

    global $species_table;

    global $pop_table;

    global $listings_table;

    $sql = "SELECT bg.genus_name, bs.species_name, bp.pop_name, bp.pop_url,
 bl.listings_price, bl.listings_uid, bl.listings_userid, bl.listings_comment, bl.listings_key,
 bl.listings_type, bl.listings_status, DATE_FORMAT(bl.last_update,'%c/%e') lup
 FROM " . $genus_table . ' bg, ' . $species_table . ' bs, ' . $pop_table . ' bp, ' . $listings_table . ' bl
 WHERE bg.genus_key = bs.genus_key
 AND bs.species_key = bp.species_key
 AND bp.pop_key = bl.pop_key';

    if (0 == $popkey) {
        if (mb_strlen($filter_name) > 0) {
            $sql .= " AND (bg.genus_name like '%" . $filter_name . "%'";

            $sql .= " OR bs.species_name like '%" . $filter_name . "%'";

            $sql .= " OR bp.pop_name like '%" . $filter_name . "%')";
        }

        if (mb_strlen($filter_comment) > 0) {
            $sql .= " AND (bl.listings_price like '%" . $filter_comment . "%'";

            $sql .= " OR bl.listings_comment like '%" . $filter_comment . "%')";
        }

        if (mb_strlen($filter_seller) > 0) {
            $sql .= " AND bl.listings_userid like '%" . $filter_seller . "%'";
        }
    } else {
        $sql .= ' AND bp.pop_key = ' . $popkey;
    }

    // Add visibility permission predicates

    if (has_perm(_BR_GPERM_VIEW_SMC)) {
    } else {
        if (has_perm(_BR_GPERM_VIEW_MEMB)) {
            $sql .= " AND bl.listings_visible in ('A','M')";
        } else {
            $sql .= " AND bl.listings_visible = 'A'";
        }
    }

    // Choose the sort order of the records

    switch ($sort) {
        case 'I':
            // sort by item (default)
            $sql .= ' ORDER BY bg.genus_name, bs.species_name, bp.pop_name';
            break;
        case 'S':
            // sort by seller,item
            $sql .= ' ORDER BY bl.listings_userid, bg.genus_name, bs.species_name, bp.pop_name';
            break;
        case 'N':
            // sort by last update
            $sql .= ' ORDER BY bl.last_update desc';
            break;
    }

    //echo $sql;

    $res = $xoopsDB->query($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }

    $i = 0;

    while (false !== ($item = $xoopsDB->fetchArray($res))) {
        $ltype = $item['listings_type'];

        if (('on' == $showFish and ('F' == $ltype or 'FE' == $ltype)) or ('on' == $showEggs and ('E' == $ltype or 'FE' == $ltype)) or ('on' == $showWTB and 'W' == $ltype) or ('on' == $showAll and 'R' == $ltype) or ('on' == $showNonfish and 'S' == $ltype)) {
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

            $result[$i++] = $item;
        }
    }

    return $result;
}

function get_bio_listings_by_user($uname = 0)
{
    global $xoopsDB;

    global $xoopsUser;

    global $genus_table;

    global $species_table;

    global $pop_table;

    global $listings_table;

    $result = [];

    if (0 == $uname) {
        $uname = $xoopsUser->getVar('uname', 'E');
    }

    $sql = 'SELECT bg.genus_name, bs.species_name, bp.pop_name, bp.pop_url,
 bl.listings_price, bl.listings_userid, bl.listings_comment, bl.listings_key,
 bl.listings_type, bl.listings_status, bl.listings_visible
 FROM ' . $genus_table . ' bg, ' . $species_table . ' bs, ' . $pop_table . ' bp, ' . $listings_table . " bl
 WHERE bg.genus_key = bs.genus_key
 AND bs.species_key = bp.species_key
 AND bp.pop_key = bl.pop_key
 AND bl.listings_userid = '" . $uname . "' ORDER BY bg.genus_name, bs.species_name, bp.pop_name";

    $res = $xoopsDB->query($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }

    $i = 0;

    while (false !== ($item = $xoopsDB->fetchArray($res))) {
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

        $result[$i++] = $item;
    }

    return $result;
}

function get_bio_listings_for_export()
{
    global $xoopsDB;

    global $xoopsUser;

    global $genus_table;

    global $species_table;

    global $pop_table;

    global $listings_table;

    $result = [];

    $sql = 'SELECT bg.genus_name, bs.species_name, bp.pop_name, 
 bl.listings_price, bl.listings_userid, bl.listings_comment,
 bl.listings_type, bl.listings_status, bl.listings_visible,
 bl.create_time, bl.last_update
 FROM ' . $genus_table . ' bg, ' . $species_table . ' bs, ' . $pop_table . ' bp, ' . $listings_table . ' bl
 WHERE bg.genus_key = bs.genus_key
 AND bs.species_key = bp.species_key
 AND bp.pop_key = bl.pop_key
 ORDER BY bl.listings_userid, bg.genus_name, bs.species_name, bp.pop_name';

    $res = $xoopsDB->query($sql);

    if (false === $res) {
        die('SQL Error: ' . $sql);
    }

    $i = 0;

    while (false !== ($item = $xoopsDB->fetchArray($res))) {
        $result[$i++] = $item;
    }

    return $result;
}
