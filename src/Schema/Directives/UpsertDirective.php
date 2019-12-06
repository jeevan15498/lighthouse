<?php

namespace Nuwave\Lighthouse\Schema\Directives;

use Illuminate\Database\Eloquent\Relations\Relation;
use Nuwave\Lighthouse\Execution\Arguments\SaveModel;
use Nuwave\Lighthouse\Execution\Arguments\UpsertModel;

class UpsertDirective extends MutationExecutorDirective
{
    public function name(): string
    {
        return 'upsert';
    }

    public static function definition(): string
    {
        return /* @lang GraphQL */ <<<'SDL'
"""
Create or update an Eloquent model with the input values of the field.
"""
directive @upsert(
  """
  Specify the class name of the model to use.
  This is only needed when the default model resolution does not work.
  """
  model: String

  """
  Set to `true` to use global ids for finding the model.
  If set to `false`, regular non-global ids are used.
  """
  globalId: Boolean = false

  """
  Specify the name of the relation on the parent model.
  This is only needed when using this directive as a nested arg
  resolver and if the name of the relation is not the arg name.
  """
  relation: String
) on FIELD_DEFINITION | ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
SDL;
    }

    protected function makeExecutionFunction(?Relation $parentRelation = null): callable
    {
        return new UpsertModel(new SaveModel($parentRelation));
    }
}
