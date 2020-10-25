<?php

echo '<small><a href="index.php">' . _MI_BIOREG_LISTING . '</a> | <a href="bioreg-manage_listings.php">' . _MI_BIOREG_MANAGE_LISTING . '</a> | <a href="bioreg-info.php">' . _MI_BIOREG_INFO . '</a></small>';

// include the form loader
require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
// creación del formulario (sencillo o con el tema del sitio)
$my_form = new XoopsThemeForm(_MI_BIOREG_NEW_ITEM_TITLE, 'bioreg', 'bioreg-newitem.php');

// create text box for the genus
$my_form->addElement(new XoopsFormText(_MI_BIOREG_GENUS, 'genus', 50, 100, ''), false);

// create text box for the species
$my_form->addElement(new XoopsFormText(_MI_BIOREG_SPECIES, 'species', 50, 100, ''), false);

// create text box for the population
$my_form->addElement(new XoopsFormText(_MI_BIOREG_POP, 'population', 50, 100, ''), false);

// create a simple textarea for the message
$my_form->addElement(new XoopsFormTextArea(_MI_BIOREG_NEW_ITEM_COMMENT, 'comment', ''), false);

// a variant of the textarea including all DHTML options (insert links, smileys, etc.)
//$my_form->addElement(new XoopsFormDhtmlTextArea(_MI_MESSAGE, 'comment', $comment, 15, 60), false);

// create the preview and submit buttons
$button_tray = new XoopsFormElementTray('', '');
//$button_tray->addElement(new XoopsFormButton('', 'preview', _MI_PREVIEW, 'submit'));
$button_tray->addElement(new XoopsFormButton('', 'post', _MI_BIOREG_SUBMIT, 'submit'));
$my_form->addElement($button_tray);

// display the form, unless the display is via a template
$my_form->display();
