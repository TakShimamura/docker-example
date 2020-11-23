<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeContract extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:contract {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a template for a new contract';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(){
        return 'stubs/contract.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Contracts';
    }

    // public function handle(){
    //     $dirs = $this->files->files('templates');
    //     foreach($dirs as $dir){
    //         echo $dir. '|| ';
    //     }
    //     echo ' File: '. $this->getStub();
    // }

}
