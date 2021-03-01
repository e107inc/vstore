<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2016 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Plugin - Vstore Menu
 *
*/

if (!defined('e107_INIT')) {
    exit;
}



require_once(e_PLUGIN . 'vstore/shortcodes/batch/vstore_shortcodes.php');
require_once(e_PLUGIN . 'vstore/e_sitelink.php');
$tp = e107::getParser();
$sc = new plugin_vstore_vstore_shortcodes;
$vst = new vstore_sitelink;

$caption = "Vstore Categories";

$items = $vst->storeCategories();

$template = e107::getTemplate('vstore', 'vstore_menu', 'categories');
$text = $tp->parseTemplate($template['start'], true, $sc);

foreach($items as $item) {
    $sc->setVars($item);
    $text .= $tp->parseTemplate($template['item'], true, $sc);
}

$text .= $tp->parseTemplate($template['end'], true, $sc);

e107::getRender()->tablerender($caption, $text);
