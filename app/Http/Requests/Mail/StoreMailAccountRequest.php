<?php

namespace App\Http\Requests\Mail;

use App\Models\MailDomain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMailAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', $this->route('domain'));
    }

    public function rules(): array
    {
        /** @var MailDomain $domain */
        $domain = $this->route('domain');

        return [
            'local_part' => [
                'required',
                'string',
                'regex:/^[a-z0-9][a-z0-9._-]{0,62}$/',
                Rule::unique('mail_accounts', 'local_part')->where('mail_domain_id', $domain->id),
            ],
            'password' => ['required', 'string', 'min:10', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'local_part.regex' => 'Lettres minuscules, chiffres, points, tirets uniquement (ex. contact, jean.dupont).',
            'local_part.unique' => 'Cette adresse existe déjà pour ce domaine.',
        ];
    }
}
