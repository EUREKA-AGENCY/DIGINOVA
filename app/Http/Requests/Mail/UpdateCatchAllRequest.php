<?php

namespace App\Http\Requests\Mail;

use App\Models\MailAccount;
use App\Models\MailDomain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCatchAllRequest extends FormRequest
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
                Rule::exists('mail_accounts', 'local_part')
                    ->where('mail_domain_id', $domain->id)
                    ->where('type', MailAccount::TYPE_MAILBOX),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'local_part.exists' => "Cette boîte mail n'existe pas sur ce domaine.",
        ];
    }
}
