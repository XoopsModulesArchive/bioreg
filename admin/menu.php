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

$adminmenu[0]['title'] = _MI_BIOREG_INFO;
$adminmenu[0]['link'] = 'bioreg-info.php';

$adminmenu[1]['title'] = _MI_BIOREG_LISTALL;
$adminmenu[1]['link'] = 'admin/bioreg-list.php';

$adminmenu[2]['title'] = _MI_BIOREG_EXPORT;
$adminmenu[2]['link'] = 'admin/bioreg-list.php?export=y';

$adminmenu[3]['title'] = _MI_BIOREG_GRPPERM;
$adminmenu[3]['link'] = 'admin/groupperm.php';
