<?php
// $Id: menu.php 2684 2010-07-18 17:30:05Z dualface $

class Menu
{
	protected $_all;

	protected function __construct()
	{
		$arr = Helper_Yaml::load(dirname(__FILE__) . '/menu.yaml');
		$this->_all = $this->_prepareMenus($arr);
	}

	static function instance()
	{
		static $instance;
		if (is_null($instance))
		{
			$instance = new Menu();
		}
		return $instance;
	}

	function getAll()
	{
		return $this->_all;
	}

	function getCurrentMainMenu()
	{
	    $context = QContext::instance();
		$controller = $context->controller_name;

		foreach ($this->_all as $main)
		{
			if ($main['controller'] == $controller)
			{
				return $main;
			}
		}

		return null;
	}

	function getCurrentSubMenu(array $menu)
	{
	    $context = QContext::instance();
        $controller = $context->controller_name;
        $action = $context->action_name;

        foreach ($menu as $item)
        {
            if ($item['controller'] == $controller && $item['action'] == $action)
            {
                return $item;
            }
        }

        return null;
	}

    function compare($menu1, $menu2)
    {
        if ($menu1['ns'] != $menu2['ns']) { return false; }
        if ($menu1['controller'] != $menu2['controller']) { return false; }
        if ($menu1['action'] != $menu2['action']) { return false; }
        return true;
    }

    protected function _prepareMenus(array $arr)
    {
        $ret = array();
        foreach ($arr as $menu)
        {
            if (empty($menu['ns']))
            {
                $menu['ns'] = null;
            }
            if (empty($menu['action']))
            {
                $menu['action'] = 'index';
            }
            if (!empty($menu['items']))
            {
                $menu['items'] = $this->_prepareMenus($menu['items']);
            }
            $ret[] = $menu;
        }

        return $ret;
    }

}
