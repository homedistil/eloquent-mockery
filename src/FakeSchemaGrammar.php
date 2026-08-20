<?php

namespace Imanghafoori\EloquentMockery;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as SchemaGrammar;
use Illuminate\Support\Fluent;

class FakeSchemaGrammar extends SchemaGrammar
{
    public static $query = [];

    public function compileCreate(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('createTable', [$blueprint, $command]);

        return parent::compileCreate($blueprint, $command);
    }

    public function compileAdd(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('add', [$blueprint, $command]);

        return parent::compileAdd($blueprint, $command);
    }

    public function compileChange(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('change', [$blueprint, $command]);

        return parent::compileChange($blueprint, $command);
    }

    public function compileIndex(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('index', [$blueprint, $command]);

        return parent::compileIndex($blueprint, $command);
    }

    public function compileKey(Blueprint $blueprint, Fluent $command, $type)
    {
    }

    public function compileRenameIndex(Blueprint $blueprint, Fluent $command)
    {
    }

    public function compileForeign(Blueprint $blueprint, Fluent $command)
    {
    }

    public function compileRenameColumn(Blueprint $blueprint, Fluent $command)
    {
        //self::keepData('renameColumn', [$blueprint, $command]);

        //return parent::compileRenameColumn($blueprint, $command);
    }

    public function compileUnique(Blueprint $blueprint, Fluent $command)
    {
    }

    public function compileRename(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('rename', [$blueprint, $command]);

        return parent::compileRename($blueprint, $command);
    }

    public function compileDrop(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('drop', [$blueprint, $command]);

        return parent::compileDrop($blueprint, $command);
    }

    public function compileDropIfExists(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('dropIfExists', [$blueprint, $command]);

        return parent::compileDropIfExists($blueprint, $command);
    }

    public function compileColumns($schema, $table)
    {
        return $this->stringy([
            'type' => 'columns',
            'args' => ['database' => $schema, 'table' => $table],
            'sql'  => parent::compileColumns($schema, $table),
        ]);
    }

    public function compileDropColumn(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('dropColumn', [$blueprint, $command]);

        return parent::compileDropColumn($blueprint, $command);
    }

    public function compileEnableForeignKeyConstraints()
    {
        self::keepData('enableForeignKeyConstraints');

        return parent::compileEnableForeignKeyConstraints();
    }

    public function compileDisableForeignKeyConstraints()
    {
        self::keepData('disableForeignKeyConstraints');

        return parent::compileDisableForeignKeyConstraints();
    }

    public function compilePrimary(Blueprint $blueprint, Fluent $command)
    {
        self::keepData('primary', [$blueprint, $command]);

        return parent::compilePrimary($blueprint, $command);
    }

    public function compileDropAllTables($tables)
    {
        self::keepData('dropAllTables', $tables);

        return parent::compileDropAllTables($tables);
    }

    public function compileTableExists($schema, $table)
    {
        return $this->stringy([
            'type' => 'tableExists',
            'sql'  => parent::compileTableExists($schema, $table),
        ]);
    }

    private function stringy(array $data)
    {
        return new class($data) {
            public $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function __toString()
            {
                return $this->data['sql'];
            }
        };
    }

    private static function keepData(string $type, array $args = [])
    {
        self::$query[] = [
            'type' => $type,
            'args' => $args,
        ];
    }
}