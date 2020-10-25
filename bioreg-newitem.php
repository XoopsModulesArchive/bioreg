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

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

// to retrieve all the form's variables and values (avoids the usage of individual $_POST declarations)
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if (isset($post)) {
    $myts = MyTextSanitizer::getInstance();

    // retrieval and preparation of data

    $message = _MI_BIOREG_GENUS . ': ' . $genus . "\n" . _MI_BIOREG_SPECIES . ': ' . $species . "\n" . _MI_BIOREG_POP . ': ' . $population . "\nNotes: " . $comment;

    global $xoopsUser;

    global $xoopsMailer;

    global $xoopsModuleConfig;

    $name = $xoopsUser->getVar('name', 'E');

    if (empty($name)) {
        $name = $xoopsUser->getVar('uname', 'E');
    }

    $nomen_email = $xoopsModuleConfig['nomen_email'];

    $xoopsMailer = getMailer();

    $xoopsMailer->setToEmails($nomen_email);

    $xoopsMailer->setFromName('AKA website');

    $xoopsMailer->setFromEmail('nomen@aka.org');

    $xoopsMailer->setSubject('Item request from ' . $name);

    $xoopsMailer->setBody($message);

    $xoopsMailer->useMail();

    $xoopsMailer->send(true);

    $messagesent = $xoopsMailer->getSuccess();

    $messagesent .= $xoopsMailer->getErrors();

    redirect_header('index.php', 2, $messagesent);
} else {
    require XOOPS_ROOT_PATH . '/header.php';

    include 'include/bioreg-newitem.inc.php'; // include the form

    require XOOPS_ROOT_PATH . '/footer.php';
}
