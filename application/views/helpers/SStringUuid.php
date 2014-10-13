<?php

class Zend_View_Helper_SstringUuid extends Zend_View_Helper_Abstract
{
	
	public function sstringUuid() {
		
		if (empty($node)) {
			$node = crc32('ce8d96d579d389e783f95b3772785783ea1a9854');
		}

		 
			$pid = mt_rand(0, 0xfff) | 0x4000;
		 

		list($timeMid, $timeLow) = explode(' ', microtime());
		$uuid = sprintf("%08x-%04x-%04x-%02x%02x-%04x%08x", (int)$timeLow, (int)substr($timeMid, 2) & 0xffff,
					mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3f) | 0x80, mt_rand(0, 0xff), $pid, $node);

		return $uuid;
	}	
}

 