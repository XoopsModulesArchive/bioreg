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
require dirname(__DIR__, 3) . '/include/cp_header.php';

$module_id = $xoopsModule->getVar('mid');

xoops_cp_header();
echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr><td class="odd">';
echo "<a href='./index.php'><h4>" . _MD_A_BIOREGCONF . '</h4></a>';
if (isset($mode)) {
} else {
    ?>
<table border="0" cellpadding="4" cellspacing="1" width="100%">

    <tr class='bg1' align="left">
        <td><span class='fg2'><a href="/modules/bioreg/bioreg-info.php"><?php echo _MD_A_NOMEN; ?></a></span></td>
        <td><span class='fg2'><?php echo _MD_A_NOMEN_DESC; ?></span></td>
    </tr>
    <tr class='bg1' align="left">
        <td><span class='fg2'><a href="bioreg-list.php"><?php echo _MD_A_LISTALL; ?></a></span></td>
        <td><span class='fg2'><?php echo _MD_A_LISTALL_DESC; ?></span></td>
    </tr>
    <tr class='bg1' align="left">
        <td><span class='fg2'><a href="bioreg-list.php?export=y"><?php echo _MD_A_EXPORT; ?></a></span></td>
        <td><span class='fg2'><?php echo _MD_A_EXPORT_DESC; ?></span></td>
    </tr>
    <tr class='bg1' align="left">
        <td><span class='fg2'><a href="groupperm.php"><?php echo _MD_A_GPERM; ?></a></span></td>
        <td><span class='fg2'><?php echo _MD_A_GPERM_DESC; ?></span></td>
    </tr>
    <tr class='bg1' align="left">
        <td><span class='fg2'><a href="/modules/system/admin.php?fct=preferences&op=showmod&mod=<?php echo $module_id . '">' . _MD_A_PREF; ?></a></span></td>
	<td><span class='fg2'><?php echo _MD_A_PREF_DESC; ?></span></td>
</tr>
</table>
<?php
}

                echo '</td></tr></table>';
                xoops_cp_footer();
                ?>
