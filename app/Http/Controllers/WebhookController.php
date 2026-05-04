<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Services\GeminiLeadAnalyzer;

class WebhookController extends Controller
{
    public function receive(Request $request, GeminiLeadAnalyzer $analyzer)
    {
        // Ignora verificação CSRF para API/Webhook, garantindo que em /routes/web.php funcione (no Laravel 11/12 middlewares podem ser excluídos no bootstrap/app.php, mas para simplificar aceitamos)
        $request->validate([
            'phone' => 'required',
            'message' => 'required'
        ]);

        $lead = Lead::create([
            'name' => $request->input('name', 'Contato WhatsApp'),
            'phone' => $request->input('phone'),
            'whatsapp_conversation' => "Lead: " . $request->input('message'),
            'ai_score' => null,
            'intent_level' => null,
            'ai_analysis' => 'Analisando...'
        ]);

        // Executa análise
        $analyzer->analyze($lead);

        return response()->json([
            'success' => true, 
            'lead_id' => $lead->id,
            'score' => $lead->ai_score,
            'intent' => $lead->intent_level
        ]);
    }
}
