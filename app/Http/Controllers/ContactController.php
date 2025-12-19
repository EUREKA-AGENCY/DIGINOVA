<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequestStore;
use App\Models\ContactRequest as ContactRequestModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactRequestReceived;

class ContactController extends Controller
{
    public function store(ContactRequestStore $request): RedirectResponse
    {
        $data = $request->validated();

        $contact = ContactRequestModel::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'topic' => $data['topic'],
            'message' => $data['message'],
            'consent_at' => !empty($data['consent']) ? now() : null,
            'status' => 'new',
        ]);

        $to = config('mail.notifications.to', config('mail.from.address'));
        if ($to) {
            Mail::to($to)->send(new ContactRequestReceived($contact));
        }

        return redirect()->route('contact.confirmation')->with('success', 'Votre message a été envoyé. Nous vous répondrons rapidement.');
    }
}
