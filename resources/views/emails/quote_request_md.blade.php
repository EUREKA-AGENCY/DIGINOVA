@component('mail::message')
# Nouvelle demande de devis

**Contact**: {{ $quote->contact_name }} ({{ $quote->email }})  
**Téléphone**: {{ $quote->phone ?? '—' }}  
**Entreprise**: {{ $quote->company_name ?? '—' }}  
**Projet**: {{ $quote->project_name ?? '—' }}  
**Type**: {{ $quote->project_type ?? '—' }}  
**Budget**: {{ $quote->budget_range ?? '—' }}  
**Délai**: {{ $quote->deadline ?? '—' }}

@if(!empty($quote->details))
@component('mail::panel')
{{ $quote->details }}
@endcomponent
@endif

@component('mail::button', ['url' => config('app.url')])
Ouvrir le site
@endcomponent

— {{ config('app.name') }}
@endcomponent
