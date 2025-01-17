<?php

namespace Regnerisch\LaravelBeyond\Commands;

use Illuminate\Console\Command;
use Regnerisch\LaravelBeyond\Resolvers\AppNameSchemaResolver;

class MakeRequestCommand extends Command
{
    protected $signature = 'beyond:make:request {name}';

    protected $description = 'Make a new request';

    public function handle(): void
    {
        try {
            $name = $this->argument('name');

            $schema = new AppNameSchemaResolver($name);

            beyond_copy_stub(
                'request.stub',
                base_path() . '/src/App/' . $schema->getPath('Requests') . '.php',
                [
                    '{{ application }}' => $schema->getAppName(),
                    '{{ module }}' => $schema->getModuleName(),
                    '{{ className }}' => $schema->getClassName(),
                ]
            );

            $this->info("Request created.");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
