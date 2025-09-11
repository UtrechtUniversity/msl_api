<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use app\Models\Surveys\QuestionTypes;

class asQuestion implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $className = $model->question_type->class;
        
        $questionArray = json_decode(
            json: $value,
            associative: true,
            depth: 512,
            flags: JSON_THROW_ON_ERROR
        );
        
        return new $className($questionArray);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return json_encode(
            $value,
            JSON_THROW_ON_ERROR,
        );
    }
}
