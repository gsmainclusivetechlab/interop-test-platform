<?php declare(strict_types=1);

namespace App\Http\Requests\Sessions;

use Illuminate\Foundation\Http\FormRequest;
use JsonException;

class StoreTestDatumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'method' => ['required'],
            'uri' => ['required', 'string', 'max:255'],
            'headers' => [
                'required',
                function ($attribute, $value, $fail) {
                    try {
                        $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                    } catch (JsonException $e) {
                        $fail(__('The :attribute is invalid'));
                    }

                    array_map(function ($value) use ($fail) {
                        if (!is_scalar($value)) {
                            $fail(__('The :attribute is invalid'));
                        }
                    }, $value);
                },
            ],
            'body' => [
                'required',
                function ($attribute, $value, $fail) {
                    try {
                        json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                    } catch (JsonException $e) {
                        $fail(__('The :attribute is invalid'));
                    }
                },
            ],
        ];
    }
}
