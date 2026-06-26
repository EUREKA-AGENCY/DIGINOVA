<?php

namespace App\Http\Requests\Mail;

use App\Models\MailAccount;
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
            'type' => ['required', Rule::in([MailAccount::TYPE_MAILBOX, MailAccount::TYPE_ALIAS])],
            'local_part' => [
                'required',
                'string',
                'regex:/^[a-z0-9][a-z0-9._-]{0,62}$/',
                Rule::unique('mail_accounts', 'local_part')->where('mail_domain_id', $domain->id),
            ],
            'password' => ['required_if:type,'.MailAccount::TYPE_MAILBOX, 'nullable', 'string', 'min:10', 'max:255'],
            'forward_to' => ['required_if:type,'.MailAccount::TYPE_ALIAS, 'nullable', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'local_part.regex' => 'Lettres minuscules, chiffres, points, tirets uniquement (ex. contact, jean.dupont).',
            'local_part.unique' => 'Cette adresse existe déjà pour ce domaine.',
            'password.required_if' => 'Le mot de passe est requis pour une boîte mail complète.',
            'forward_to.required_if' => "L'adresse de destination est requise pour une redirection.",
        ];
    }
}
