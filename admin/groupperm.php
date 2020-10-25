<?php

require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
$module_id = $xoopsModule->getVar('mid');

// language files
//$language = $xoopsConfig['language'] ;
//if( ! file_exists( XOOPS_ROOT_PATH . "/modules/system/language/$language/admin/blocksadmin.php") ) $language = 'english' ;
//include_once( XOOPS_ROOT_PATH . "/modules/system/language/$language/modinfo.php" ) ;

if (!empty($_POST['submit'])) {
    redirect_header(XOOPS_URL . '/modules/bioreg/admin/groupperm.php', 1, 'Group Permission Updated');

    exit;
}

$item_list = [
    _BR_GPERM_VIEW => _BR_GPERM_VIEW_DESC,
_BR_GPERM_VIEW_MEMB => _BR_GPERM_VIEW_MEMB_DESC,
_BR_GPERM_VIEW_SMC => _BR_GPERM_VIEW_SMC_DESC,
_BR_GPERM_ADD_LISTING => _BR_GPERM_ADD_LISTING_DESC,
_BR_GPERM_ADD_POP => _BR_GPERM_ADD_POP_DESC,
_BR_GPERM_ADD_SPEC => _BR_GPERM_ADD_SPEC_DESC,
_BR_GPERM_ADD_GEN => _BR_GPERM_ADD_GEN_DESC,
];

$title_of_form = 'AKA Registry Permission Settings';
$perm_name = 'Access Permission';
$perm_desc = 'Set permissions that determine what each group is allowed to do';

$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $form->addItem($item_id, $item_name);
}

xoops_cp_header();
echo $form->render();
xoops_cp_footer();
