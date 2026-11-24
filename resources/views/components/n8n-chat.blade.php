@props([
    'webhookUrl' => null,
    'title' => null,
    'subtitle' => null,
    'showWelcomeScreen' => false,
])

@php
    $webhook =
        $webhookUrl ??
        (config('services.n8n.chat_webhook_url') ??
            env(
                'N8N_CHAT_WEBHOOK_URL',
                'https://amzee.app.n8n.cloud/webhook/411474fa-3b94-4079-9d20-7bc669b062b4/chat',
            ));
    $chatTitle = $title ?? config('services.n8n.chat_title', 'Assistant CFPT Manager');
    $chatSubtitle =
        $subtitle ??
        config('services.n8n.chat_subtitle', 'Posez vos questions sur les cours, emplois du temps et évaluations.');
@endphp

<!-- Container cible pour le chatbot flottant n8n -->
<div id="n8n-chat"></div>

<!-- Styles officiels @n8n/chat -->
<link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />

<!-- Thème personnalisé aux couleurs officielles du CFPT / PFPT -->
<style>
    :root {
        --chat--color--primary: #4B6A95;
        --chat--color--primary-shade-50: #354e70;
        --chat--color--primary--shade-100: #263a54;
        --chat--color--secondary: #162536;
        --chat--color-light: #f8fafc;
        --chat--color-dark: #0f172a;
        --chat--font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        --chat--border-radius: 16px;
        --chat--window--width: 400px;
        --chat--window--height: 580px;
        --chat--window--z-index: 999999;
        --chat--window--border: 1px solid rgba(75, 106, 149, 0.2);
        --chat--header--background: linear-gradient(135deg, #162536 0%, #4B6A95 100%);
        --chat--header--color: #ffffff;
        --chat--header--padding: 1.25rem;
        --chat--toggle--size: 60px;
        --chat--toggle--background: #4B6A95;
        --chat--toggle--color: #ffffff;
        --chat--message--bot--background: #f1f5f9;
        --chat--message--bot--color: #0f172a;
        --chat--message--user--background: #4B6A95;
        --chat--message--user--color: #ffffff;
        --chat--textarea--height: 50px;
        --chat--input--font-size: 14px;
        --chat--input--padding: 10px 14px;
        --chat--input--background: #ffffff;
        --chat--input--text-color: #0f172a;
        --chat--input--container--background: #ffffff;
        --chat--input--container--border: 1px solid #cbd5e1;
        --chat--input--container--border-radius: 12px;
        --chat--input--send--button--background: #4B6A95;
        --chat--input--send--button--color: #ffffff;
        --chat--input--send--button--background-hover: #354e70;
    }

    /* Effet d'élévation et fluidité du bouton flottant */
    #n8n-chat button[aria-label="Open chat"],
    #n8n-chat .chat-toggle {
        box-shadow: 0 10px 25px -5px rgba(75, 106, 149, 0.5), 0 8px 10px -6px rgba(75, 106, 149, 0.3) !important;
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    #n8n-chat button[aria-label="Open chat"]:hover,
    #n8n-chat .chat-toggle:hover {
        transform: scale(1.06) !important;
    }
</style>

<!-- Initialisation du widget n8n chat -->
<script type="module">
    import {
        createChat
    } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

    createChat({
        webhookUrl: @json($webhook),
        target: '#n8n-chat',
        mode: 'window',
        loadPreviousSession: false,
        showWelcomeScreen: @json($showWelcomeScreen),
        defaultLanguage: 'fr',
        initialMessages: [
            'Bonjour ! 👋',
            'Je suis l\'assistant virtuel de CFPT Manager (CFPT Sénégal-Japon).',
            'Comment puis-je vous aider aujourd\'hui ?'
        ],
        i18n: {
            fr: {
                title: @json($chatTitle),
                subtitle: @json($chatSubtitle),
                footer: 'CFPT Manager - CFPT Sénégal-Japon',
                getStarted: 'Nouvelle conversation',
                inputPlaceholder: 'Posez votre question ici...',
                closeButtonTooltip: 'Fermer la discussion',
                repostButton: 'Renvoyer le message',
            },
            en: {
                title: @json($chatTitle),
                subtitle: @json($chatSubtitle),
                footer: 'CFPT Manager - CFPT Sénégal-Japon',
                getStarted: 'Nouvelle conversation',
                inputPlaceholder: 'Posez votre question ici...',
                closeButtonTooltip: 'Fermer la discussion',
                repostButton: 'Renvoyer le message',
            }
        }
    });
</script>
