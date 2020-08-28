<?php

namespace App\Interfaces;

interface AsideMenuInterface
{
	public function activeMenuList(String $open_menu, String $active_menu) : array;
}