<?php

namespace App\Modules\Core\Http\Controllers\Common;

use App\Http\Controllers\Controller;

class SidebarController extends Controller
{
    /**
     * Toggle the sidebar in session.
     *
     * @return void
     */
    public function toggle()
    {
        if (session()->has('sidebarToggled')) {
            if (session('sidebarToggled')) {
                session(['sidebarToggled' => false]);
            } else {
                session(['sidebarToggled' => true]);
            }
        } else {
            session(['sidebarToggled' => true]);
        }
    }
}
