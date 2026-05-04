<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Services\GeminiLeadAnalyzer;

class WebhookController extends Controller
{
    public function verify(Request $request)
    {
        $verifyToken = env('WHATSAPP_VERIFY_TOKEN', 'meu_token_secreto_123');

        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verifyToken) {
                return response($challenge, 200);
            }
            return response('Forbidden', 403);
        }

        return response('Bad Request', 400);
    }

    public function receive(Request $request, GeminiLeadAnalyzer $analyzer)
    {
        $data = $request->all();

        // Verifica se é uma notificação do WhatsApp com mensagem
        if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
            $value = $data['entry'][0]['changes'][0]['value'];
            $message = $value['messages'][0];
            $contact = $value['contacts'][0] ?? null;

            // Apenas processa mensagens de texto
            if (isset($message['text']['body'])) {
                $phone = $message['from'];
                $textBody = $message['text']['body'];
                $name = $contact['profile']['name'] ?? 'Contato WhatsApp';

                // Busca lead existente ou cria um novo
                $lead = Lead::where('phone', $phone)->first();
                
                if (!$lead) {
                    $lead = Lead::create([
                        'name' => $name,
                        'phone' => $phone,
                        'whatsapp_conversation' => "Lead: " . $textBody,
                        'ai_analysis' => 'Analisando...'
                    ]);
                } else {
                    $lead->whatsapp_conversation .= "\nLead: " . $textBody;
                    $lead->ai_analysis = 'Reanalisando...';
                    $lead->save();
                }

                // Executa análise
                $analyzer->analyze($lead);
            }
        }

        // A Meta exige um status 200 OK para confirmar o recebimento do Webhook
        return response('EVENT_RECEIVED', 200);
    }
}
