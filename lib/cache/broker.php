<?php
/**
 * Copyright (c) 2012 Bart Visscher <bartv@thisnet.nl>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

class OC_Cache_Broker {
	protected $fast_cache;
	protected $slow_cache;

	public function __construct($fast_cache, $slow_cache) {
		$this->fast_cache = $fast_cache;
		$this->slow_cache = $slow_cache;
	}

	public function get($key) {
		if ($r = $this->fast_cache->get($key)) {
			return $r;
		}
		return $this->slow_cache->get($key);
	}

	public function set($key, $value, $ttl=0) {
		if (!$this->fast_cache->set($key, $value, $ttl)) {
			if ($this->fast_cache->hasKey($key)) {
				$this->fast_cache->remove($key);
			}
			return $this->slow_cache->set($key, $value, $ttl);
		}
		return true;
	}

	public function hasKey($key) {
		if ($this->fast_cache->hasKey($key)) {
			return true;
		}
		return $this->slow_cache->hasKey($key);
	}

	public function remove($key) {
		if ($this->fast_cache->remove($key)) {
			return true;
		}
		return $this->slow_cache->remove($key);
	}

	public function clear(){
		$this->fast_cache->clear();
		$this->slow_cache->clear();
	}
}