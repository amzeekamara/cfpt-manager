<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class DebugController extends Controller
{
    public function emails()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            return view('admin.debug.emails', ['emails' => []]);
        }

        $content = File::get($logPath);
        
        // Séparer par "Message-ID" qui marque souvent le début d'un email dans le log
        // Ou par la signature du mailer log de Laravel
        $rawEmails = explode('] local.DEBUG: Message-ID:', $content);
        
        $emails = [];
        // On ignore le premier élément qui est du log général avant le premier mail
        array_shift($rawEmails);
        
        // On prend les 10 derniers
        $rawEmails = array_slice(array_reverse($rawEmails), 0, 10);

        foreach ($rawEmails as $raw) {
            // Extraction basique du sujet, destinataire et contenu
            preg_match('/Subject: (.*)/', $raw, $subject);
            preg_match('/To: (.*)/', $raw, $to);
            
            // Le contenu est souvent après les headers
            $parts = explode('Content-Transfer-Encoding: quoted-printable', $raw);
            $body = isset($parts[1]) ? trim($parts[1]) : 'Contenu non extrait';

            $emails[] = [
                'subject' => $subject[1] ?? 'Sans sujet',
                'to' => $to[1] ?? 'Inconnu',
                'body' => $body,
                'date' => 'Dernièrement'
            ];
        }

        return view('admin.debug.emails', compact('emails'));
    }
}
