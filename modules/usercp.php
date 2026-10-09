<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_3').'</span></div>';

$cfg = loadConfig('usercp');
if(!is_array($cfg)) throw new Exception('Could not load usercp, please contact support.');

echo '<style>
.usercp-menu-grid {
	display: flex;
	flex-wrap: wrap;
	gap: 20px;
	margin-top: 30px;
	margin-bottom: 30px;
}

.usercp-menu-item {
	width: 130px;
	text-align: center;
}

.usercp-menu-item a {
	display: inline-flex;
	flex-direction: column;
	align-items: center;
	gap: 8px;
	text-decoration: none;
}

.usercp-menu-item img {
	width: 100px;
	height: auto;
}

@media (max-width: 768px) {
	.usercp-menu-grid {
		justify-content: center;
	}
}
</style>';

echo '<div class="usercp-menu-grid">';
	foreach($cfg as $element) {
		if(!is_array($element)) continue;
		if(!$element['active']) continue;
		$link = $element['type'] == 'internal' ? __BASE_URL__ . $element['link'] : $element['link'];
		$title = check_value(lang($element['phrase'], true)) ? lang($element['phrase']) : 'ERROR';
		$icon = check_value($element['icon']) ? __PATH_TEMPLATE_IMG__ . 'icons/' . $element['icon'] : __PATH_TEMPLATE_IMG__ . 'icons/usercp_default.png';
		
		echo '<div class="usercp-menu-item">';
			echo $element['newtab'] ? '<a href="'.$link.'" target="_blank">' : '<a href="'.$link.'">';
				echo '<img src="'.$icon.'" alt="'.$title.'"/>';
				echo '<span>'.$title.'</span>';
			echo '</a>';
		echo '</div>';
	}
echo '</div>';

