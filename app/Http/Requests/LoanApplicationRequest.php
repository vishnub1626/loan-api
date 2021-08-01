<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class LoanApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|gt:100',
            'term' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $term = collect($value);
                    $years = $term->get('years');
                    $months = $term->get('months');

                    if (($years != 0 && empty($years)) || ($months != 0 && empty($months))) {
                        $fail('The term is invalid.');
                    }

                    if (!is_numeric($years) || !is_numeric($months)) {
                        $fail('The term is invalid.');
                    }

                    if ($months == 0 && $years == 0) {
                        $fail('The term is invalid.');
                    }

                    if ($months < 0 || $years < 0) {
                        $fail('The term is invalid.');
                    }

                    if ($months > 12) {
                        $fail('The term is invalid.');
                    }
                },
            ],
        ];
    }
}
