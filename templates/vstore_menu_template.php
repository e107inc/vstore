<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2017 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */

	$VSTORE_MENU_TEMPLATE = array();

	// Catiegories menu
	$VSTORE_MENU_TEMPLATE['categories']['start'] = '
    <div class="list-group vstore-categories">
    ';

	$VSTORE_MENU_TEMPLATE['categories']['end'] = '
    </div>
    ';

	$VSTORE_MENU_TEMPLATE['categories']['item'] = '
		<a href="{MENU_CAT:url}" class="list-group-item vstore-categories-item {MENU_CAT:active}">
			{MENU_CAT:name}
			<span class="badge badge-primary badge-pill">{MENU_CAT:badge}</span>
			<p class="list-group-item-text vstore-categories-item-text">{MENU_CAT:description}</p>
		</a>
    ';

