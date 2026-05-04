<?php

namespace App\Services;

use App\Models\Lead;
use Gemini\Laravel\Facades\Gemini;

class GeminiLeadAnalyzer
{
    public function analyze(Lead $lead)
    {
        $prompt = "Você é um classificador de leads de IA para uma imobiliária de alto padrão (imóveis R$500k+).
Sua missão é analisar a conversa de WhatsApp de um lead, cruzar com o perfil de conversão e dar uma nota de intenção.

**Perfil do Lead Quente (Alta conversão)**:
- Pergunta detalhes técnicos, condomínio, documentação.
- Quer agendar visita rapidamente.
- Já possui o valor ou carta de crédito aprovada.
- Dá detalhes de uma dor real (ex: família crescendo, mudança de emprego).

**Perfil do Curioso (Baixa conversão)**:
- Pergunta só o preço.
- Demora a responder.
- Diz que está 'só dando uma olhadinha'.

**Conversa do WhatsApp do Lead:**
{$lead->whatsapp_conversation}

**Tarefa:**
Baseado nisso, responda EXATAMENTE com um objeto JSON válido (sem markdown, sem crases, apenas o JSON puro) contendo:
{
  \"score\": <nota de 0 a 100 inteira>,
  \"intent_level\": \"<quente, morno ou frio>\",
  \"analysis\": \"<breve explicação do porquê dessa nota e se o corretor deve priorizar>\"
}";

        try {
            // Using the Facade from google-gemini-php/laravel
            $response = Gemini::generateContent($prompt);
            $text = $response->text();

            // Limpar possiveis markdown de json (```json ... ```)
            $text = str_replace(['```json', '```'], '', $text);
            $data = json_decode(trim($text), true);

            if ($data && isset($data['score'])) {
                $lead->update([
                    'ai_score' => $data['score'],
                    'intent_level' => strtolower($data['intent_level']),
                    'ai_analysis' => $data['analysis']
                ]);
            } else {
                // Fallback caso o JSON venha mal formatado
                $lead->update([
                    'ai_score' => 50,
                    'intent_level' => 'morno',
                    'ai_analysis' => 'Não foi possível analisar detalhadamente o JSON retornado pela IA. Resposta bruta: ' . substr($text, 0, 100)
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao analisar lead no Gemini: ' . $e->getMessage());
            $lead->update([
                'ai_score' => 0,
                'intent_level' => 'frio',
                'ai_analysis' => 'Erro na API da IA: ' . $e->getMessage()
            ]);
        }
    }
}
