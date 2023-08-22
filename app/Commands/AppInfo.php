<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AppInfo extends BaseCommand
{
    protected $group       = 'TGÖ Services';
    protected $name        = 'tgoe:appinfo';
    protected $description = 'Run data quality chekc bac.';
    
    public function run(array $params)
    {
        CLI::write('PHP Version: ' . CLI::color(PHP_VERSION, 'yellow'));
        CLI::write('CI Version: ' . CLI::color(\CodeIgniter\CodeIgniter::CI_VERSION, 'yellow'));
        CLI::write('APPPATH: ' . CLI::color(APPPATH, 'yellow'));
        CLI::write('SYSTEMPATH: ' . CLI::color(SYSTEMPATH, 'yellow'));
        CLI::write('ROOTPATH: ' . CLI::color(ROOTPATH, 'yellow'));
        CLI::write('WRITEPATH: ' . CLI::color(WRITEPATH, 'yellow'));
        CLI::write('Included files: ' . CLI::color(count(get_included_files()), 'yellow'));
    }
}