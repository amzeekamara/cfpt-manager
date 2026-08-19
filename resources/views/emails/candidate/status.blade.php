<x-mail::message>
# Bonjour {{ $registration->user->name }},

@if($registration->status === 'pending')
## Votre candidature est en attente
Nous avons bien reçu votre inscription pour le séminaire **{{ $registration->seminar->title }}**. Votre dossier est actuellement en cours d'examen par nos services.

@elseif($registration->status === 'approved')
## Félicitations ! Votre candidature a été sélectionnée
Nous avons le plaisir de vous informer que vous avez été sélectionné pour participer au séminaire **{{ $registration->seminar->title }}**.

@elseif($registration->status === 'rejected')
## Information concernant votre candidature
Après examen attentif de votre dossier pour le séminaire **{{ $registration->seminar->title }}**, nous avons le regret de vous informer que votre candidature n'a pas été retenue pour cette session.

@elseif($registration->status === 'waiting_list')
## Votre candidature est sur liste d'attente
Votre dossier pour le séminaire **{{ $registration->seminar->title }}** a été placé sur liste d'attente. Nous vous contacterons si une place se libère.

@endif

Vous pouvez suivre l'évolution de votre dossier en vous connectant à votre espace candidat.

<x-mail::button :url="route('login')">
Accéder à mon espace
</x-mail::button>

Cordialement,<br>
L'équipe du **{{ config('app.name') }}**
</x-mail::message>
