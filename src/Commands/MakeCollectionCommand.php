<?php

namespace Regnerisch\LaravelBeyond\Commands;

use Illuminate\Console\Command;
use Regnerisch\LaravelBeyond\Resolvers\DomainNameSchemaResolver;

class MakeCollectionCommand extends Command
{
    protected $signature = 'beyond:make:collection {name} {--model=}';

    protected $description = 'Make a new collection';

    public function handle(): void
    {
        try {
            $name = $this->argument('name');
            $model = $this->option('model');

            $stub = $model ? 'collection.stub' : 'collection.plain.stub';

            $schema = new DomainNameSchemaResolver($name);

            beyond_copy_stub(
                $stub,
                base_path() . '/src/Domain/' . $schema->getPath('Collections') . '.php',
                [
                    '{{ domain }}' => $schema->getDomainName(),
                    '{{ className }}' => $schema->getClassName(),
                    '{{ model }}' => $model,
                ]
            );

            $this->info(
                "Please add following code to your related model" . PHP_EOL . PHP_EOL .

                'public function newCollection(array $models = [])' . PHP_EOL .
                '{' . PHP_EOL .
                "\t" . 'return new ' . $schema->getClassName() . '($models); ' . PHP_EOL .
                '}'
            );

            $this->info("Collection created.");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
