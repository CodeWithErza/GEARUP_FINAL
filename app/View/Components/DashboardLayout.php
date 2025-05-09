<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DashboardLayout extends Component
{
    public $title;
    public $icon;

    /**
     * Create a new component instance.
     *
     * @param  string|null  $title
     * @param  string|null  $icon
     * @return void
     */
    public function __construct($title = null, $icon = null)
    {
        $this->title = $title;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.dashboard');
    }
} 