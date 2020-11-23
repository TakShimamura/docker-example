<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeTrait extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name} {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a template for a new trait';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(){
        return 'stubs/trait.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Support\Traits';
        $type = $this->option('type');
        if(!empty($type)){
            $namespace = $namespace.'\\'.$type;
        }
        return $namespace;
    }

}
