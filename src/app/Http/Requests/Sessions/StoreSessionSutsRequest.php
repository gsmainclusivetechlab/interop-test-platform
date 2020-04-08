<?php declare(strict_types=1);

namespace App\Http\Requests\Sessions;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionSutsRequest extends FormRequest
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
            'components.*.sut' => 'required|boolean',
            'components.*.base_url' => 'required|url|max:255',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'components.*.sut' => __('Type'),
            'components.*.base_url' => __('URL'),
        ];
    }
}
