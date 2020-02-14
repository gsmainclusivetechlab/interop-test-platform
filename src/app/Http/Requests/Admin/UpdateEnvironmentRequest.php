<?php declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Rules\YamlRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnvironmentRequest extends FormRequest
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
            'description' => ['string', 'nullable'],
            'variables' => ['required', new YamlRule()],
        ];
    }
}
