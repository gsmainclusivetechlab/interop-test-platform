<?php declare(strict_types=1);

namespace App\Http\Requests\Sessions;

use App\Rules\YamlRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTestDatumRequest extends FormRequest
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
            'headers' => ['required', new YamlRule()],
            'body' => ['required', new YamlRule()],
        ];
    }
}
