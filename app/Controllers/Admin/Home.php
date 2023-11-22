<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\CIHelper;

class Home extends BaseController
{
    public function index(): string
    {
        $ci = new CIHelper();
        return $ci->view('admin/home');
    }
}
