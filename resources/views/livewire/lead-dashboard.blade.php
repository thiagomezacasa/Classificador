<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Classificador de Leads</h1>
        <p class="text-gray-600">Leads avaliados pela IA com base em conversas do WhatsApp.</p>
    </div>

    <div class="flex gap-4 mb-6">
        <button wire:click="$set('filterIntent', '')" class="px-4 py-2 rounded-lg font-semibold {{ $filterIntent === '' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">Todos</button>
        <button wire:click="$set('filterIntent', 'quente')" class="px-4 py-2 rounded-lg font-semibold {{ $filterIntent === 'quente' ? 'bg-red-500 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">Quentes 🔥</button>
        <button wire:click="$set('filterIntent', 'morno')" class="px-4 py-2 rounded-lg font-semibold {{ $filterIntent === 'morno' ? 'bg-orange-400 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">Mornos 🟡</button>
        <button wire:click="$set('filterIntent', 'frio')" class="px-4 py-2 rounded-lg font-semibold {{ $filterIntent === 'frio' ? 'bg-blue-400 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">Frios ❄️</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($leads as $lead)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="p-5 border-b border-gray-50 flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">{{ $lead->name ?? 'Lead Anônimo' }}</h3>
                        <p class="text-sm text-gray-500">{{ $lead->phone }}</p>
                    </div>
                    <div>
                        @if($lead->intent_level == 'quente')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                🔥 Quente ({{ $lead->ai_score }})
                            </span>
                        @elseif($lead->intent_level == 'morno')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                🟡 Morno ({{ $lead->ai_score }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                ❄️ Frio ({{ $lead->ai_score }})
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-5 bg-gray-50/50">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Análise da IA:</p>
                    <p class="text-sm text-gray-600 line-clamp-3">{{ $lead->ai_analysis ?? 'Análise pendente...' }}</p>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Mensagem Original:</p>
                        <p class="text-xs text-gray-500 italic">"{{ \Illuminate\Support\Str::limit($lead->whatsapp_conversation, 100) }}"</p>
                    </div>
                </div>
                <div class="p-4 border-t border-gray-100">
                    <button onclick="alert('Funcionalidade de abrir WhatsApp: {{ $lead->phone }}')" class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.487-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                        Responder no WhatsApp
                    </button>
                </div>
            </div>
        @endforeach

        @if($leads->isEmpty())
            <div class="col-span-full bg-white rounded-xl p-10 text-center border border-gray-100">
                <p class="text-gray-500">Nenhum lead encontrado.</p>
            </div>
        @endif
    </div>
</div>
