<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Web\BaseWebController;

class InitialViewController extends BaseWebController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->myUser();
        $route = 'dashboard.index';
        if($user && $user->hasRole('technical')) {
            $route = 'vehicles.activate';
        } 
        return redirect()->route($route);
    }
}
