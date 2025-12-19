@component('mail::message')
# Nouvelle demande de contact

**Nom**: {{ $contact->first_name }} {{ $contact->last_name }}  
**Email**: {{ $contact->email }}  
**Téléphone**: {{ $contact->phone ?? '—' }}  
**Sujet**: {{ $contact->topic }}

@component('mail::panel')
{{ $contact->message }}
@endcomponent

@component('mail::button', ['url' => config('app.url')])
Ouvrir le site
@endcomponent

— {{ config('app.name') }}
@endcomponent
