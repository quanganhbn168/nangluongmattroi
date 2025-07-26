<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;

class SidebarMenu extends Component
{
    /**
     * The menu array.
     *
     * @var array
     */
    public $menu;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menu = config('menu', []);
    }

    /**
     * Check if a menu item is active.
     *
     * @param  array  $item
     * @return bool
     */
    public function isActive($item): bool
    {
        if (isset($item['active_routes'])) {
            foreach ($item['active_routes'] as $routePattern) {
                if (Request::routeIs($routePattern)) {
                    return true;
                }
            }
            return false;
        }

        if (!isset($item['route'])) {
            return false;
        }

        return Request::routeIs($item['route'] . '*');
    }


    /**
     * Check if a parent menu item should be open.
     *
     * @param  array  $item
     * @return bool
     */
    public function isOpen($item): bool
    {
        if (!isset($item['submenu'])) {
            return false;
        }

        foreach ($item['submenu'] as $sub) {
            if ($this->isActive($sub)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('components.sidebar-menu')->with([
            'menu' => $this->menu,
            'component' => $this,
        ]);
    }
}
