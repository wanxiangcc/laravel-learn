<?php
function mkFolder($dir = '') {
	if (empty ( $dir ))
		return false;
	if (! is_dir ( $dir )) {
		if (! mkFolder ( dirname ( $dir ) )) {
			return false;
		}
		if (! mkdir ( $dir, 0777 )) {
			return false;
		}
	}
	return true;
}