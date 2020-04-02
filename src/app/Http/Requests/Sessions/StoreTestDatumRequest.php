<?php declare(strict_types=1);

namespace App\Http\Requests\Sessions;

use App\Rules\JsonRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'headers' => ['required', new JsonRule()],
            'body' => ['required', new JsonRule()],
        ];
    }
}
