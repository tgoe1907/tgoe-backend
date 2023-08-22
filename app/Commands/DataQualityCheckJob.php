<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use TgoeSrv\Member\Validator\ValidationRunner;
use App\Libraries\DataQualityCheckHelper;
use App\Libraries\DataQualityCheckResult;

class DataQualityCheckJob extends BaseCommand
{
    protected $group       = 'TGÖ Services';
    protected $name        = 'tgoe:data-quality-check';
    protected $description = 'Run data quality check background job.';
    
    public function run(array $params)
    {
        if( !DataQualityCheckHelper::obtainLock()) {
            CLI::write(CLI::color('ERROR: ', 'red').'Cannot obtain lock. Maybe process is already running.');
            return;
        }
        
        CLI::write(CLI::color('INFO: ', 'green').'Running validations...');
        
        $starttime = time();
        DataQualityCheckHelper::writeStatusmessage("Prüfjob gestartet am ".date('d.m.Y H:i'));
        
        $data = new DataQualityCheckResult();
        $data->updateTimestamp = time();
        $data->validationMessages = ValidationRunner::runAll();
        
        DataQualityCheckHelper::writeResult($data);
        
        $duration = time() - $data->updateTimestamp;
        DataQualityCheckHelper::writeStatusmessage("Prüfjob beendet am ".date('d.m.Y H:i')." / Laufzeit {$duration} Sekunden");
    
        CLI::write(CLI::color('INFO: ', 'green')."... finished after {$duration} seconds.");
    }
}