#!/usr/bin/php
<?php

$list_a = [];
$list_b = [];
$calls = [];
$verbose = false;
$vverbose = false;
$ra = false;
$time_start = microtime(true);
$done = false;

foreach ($argv as $key => $value) {
	if ($key !== 0 && is_numeric($value))
		$list_a[] = $value;
	if ($value == "-v")
		$verbose = true;
	if ($value == "-vv")
		$vverbose = true;
	if ($value == "-r")
		$ra = true;
}

if (count($list_a) > 1) {
	$count = count($list_a);
	if (!sorted()) {
		pushSwap();
	}
}

function pushA() {
	global $list_a, $list_b, $vverbose;
	foreach ($list_a as $key => $value) {
		if (isset($list_a[1]) && $list_a[0] > $list_a[count($list_a) -1]) {
			ra();
		}
		if (isset($list_a[1]) && $list_a[0] < $list_a[1]) {
			pb();
		} else {
			sa();
			if (sorted()) {
				break;
			}
			pb();
		}
		if (isset($list_b[1]) && $list_b[0] < $list_b[count($list_b) -1]) {
			rb();
		}
		if (isset($list_b[1]) && $list_b[0] < $list_b[1]) {
			sb();
		}
		if ($vverbose) {
			echo "A : [ " . implode(", ", $list_a) . "] - B : [ " . implode(", ", $list_b) . "]\n";
		}
	}
}

function pushB() {
	global $list_a, $list_b, $vverbose;
	foreach ($list_b as $key => $value) {
		if (isset($list_b[1]) && $list_b[0] < $list_b[count($list_b) -1]) {
			rb();
		}
		if (isset($list_b[1]) && $list_b[0] < $list_b[1]) {
			sb();
		}
		pa();
		if (isset($list_a[1]) && $list_a[0] > $list_a[1]) {
			sa();
		}
		if ($vverbose) {
			echo "A : [ " . implode(", ", $list_a) . "] - B : [ " . implode(", ", $list_b) . "]\n";
		}
	}
}


function gg() {
	global $list_a, $list_b, $vverbose, $count;
	for ($j = 0; $j < $count; $j++) { 
		if (isset($list_a[1])) {
			$key = array_search(min($list_a), $list_a);
			for ($i = 0; $i < $key ; $i++) { 
				ra();
			}
			pb();
		}
		if ($vverbose) {
			echo "A : [ " . implode(", ", $list_a) . "] - B : [ " . implode(", ", $list_b) . "]\n";
		}
	}
	foreach ($list_b as $key => $value) {
		pa();
	}
}

function pushSwap() {
	global $list_a, $list_b, $ra, $vverbose;
	if (!$ra) {
		pushA();
		pushB();
		
		if (!sorted()) {
			pushSwap();
		}
	} else {
		gg();
		if ($vverbose) {
			echo "A : [ " . implode(", ", $list_a) . "] - B : [ " . implode(", ", $list_b) . "]\n";
		}
	}
}

function sorted() {
	global $list_a;
	$a = $list_a;
	$b = $a;
	sort($b);
	return $a === $b;
}

function getName() {
	global $calls;
	$calls[] = debug_backtrace()[1]['function'];
}

function sa() {
	global $list_a;
	if (isset($list_a[1])) {
		$tmp = $list_a[0];
		$list_a[0] = $list_a[1];
		$list_a[1] = $tmp;
	}
	getName();
}

function sb() {
	global $list_b;
	if (isset($list_b[1])) {
		$tmp = $list_b[0];
		$list_b[0] = $list_b[1];
		$list_b[1] = $tmp;
	}
	getName();
}

function sc() {
	sa();
	sb();
}

function pa() {
	global $list_a, $list_b;
	array_unshift($list_a, array_shift($list_b));
	getName();
}

function pb() {
	global $list_a, $list_b;
	array_unshift($list_b, array_shift($list_a));
	getName();
}

function ra() {
	global $list_a;
	array_push($list_a, array_shift($list_a));
	getName();
}

function rb() {
	global $list_b;
	array_push($list_b, array_shift($list_b));
	getName();
}

function rr() {
	ra();
	rb();
}

function rra() {
	global $list_a;
	array_unshift($list_a, array_pop($list_a));
	getName();
}

function rrb() {
	global $list_b;
	array_unshift($list_b, array_pop($list_b));
	getName();
}

function rrr() {
	rra();
	rrb();
}

$time_end = microtime(true);

echo implode(" ", $calls) . "\n";
if ($verbose || $vverbose) {
	echo "Time : " . ($time_end - $time_start) . "\n";
	echo "Total : " . count($calls);
}
