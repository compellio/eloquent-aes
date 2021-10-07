<?php
namespace Compellio\EloquentAES\Command;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Compellio\EloquentAES\EloquentAESFacade;

class KeyGenerateCommand extends Command
{
    use ConfirmableTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:eloquent
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the eloquent key';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if(EloquentAESFacade::exists()){

            $this->warn('Application key is already set');
            $this->warn('**********************************************************************');
            $this->warn('* If you reset your keys you will lose access to any encrypted data. *');
            $this->warn('**********************************************************************');
            
            if ($this->confirm('Do you wish to reset your encryption key?') === false) {

                $this->info('The encryption key has not been overwritten');

                return;
            }
        }

        $this->info('Creating key for Application');

        EloquentAESFacade::generateRandomKey();
    }
}