<p>Nouvelle demande de contact reçue:</p>
<ul>
  <li>Nom: {{ $contact->first_name }} {{ $contact->last_name }}</li>
  <li>Email: {{ $contact->email }}</li>
  <li>Téléphone: {{ $contact->phone }}</li>
  <li>Sujet: {{ $contact->topic }}</li>
  <li>Message:</li>
</ul>
<pre style="white-space: pre-wrap;">{{ $contact->message }}</pre>
