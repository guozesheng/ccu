<?php
!defined('MODULE') && exit('Forbidden');
function GetUsergroupElement($id,$n='name') {
	global $_usergroup;
	return $_usergroup[$id]['name'];
}