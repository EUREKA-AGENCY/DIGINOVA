<p>Nouvelle demande de devis reçue:</p>
<ul>
  <li>Contact: {{ $quote->contact_name }} ({{ $quote->email }})</li>
  <li>Téléphone: {{ $quote->phone }}</li>
  <li>Entreprise: {{ $quote->company_name }}</li>
  <li>Projet: {{ $quote->project_name }}</li>
  <li>Type: {{ $quote->project_type }}</li>
  <li>Budget: {{ $quote->budget_range }}</li>
  <li>Délai: {{ $quote->deadline }}</li>
</ul>
<p>Détails:</p>
<pre style="white-space: pre-wrap;">{{ $quote->details }}</pre>
