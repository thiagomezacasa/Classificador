<!DOCTYPE html>
<html>
<head>
    <title>Testar Webhook WhatsApp</title>
    @vite(['resources/css/app.css'])
</head>
<body class="p-10 bg-gray-100 font-sans">
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-4">Simular Lead do WhatsApp</h2>
        <form method="POST" action="/webhook/whatsapp">
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Nome</label>
                <input type="text" name="name" class="w-full border border-gray-300 p-2 rounded-lg" value="João Teste">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Telefone</label>
                <input type="text" name="phone" class="w-full border border-gray-300 p-2 rounded-lg" value="(11) 99999-9999">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Mensagem (Conversa)</label>
                <textarea name="message" class="w-full border border-gray-300 p-2 rounded-lg" rows="5">Olá, vi o anúncio da casa de 800 mil no Morumbi. Gostaria de agendar uma visita amanhã de manhã. Já tenho o valor da entrada liberado na conta.</textarea>
                <p class="text-xs text-gray-500 mt-1">Altere a mensagem para simular um lead "Curioso" ou "Frio".</p>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">Enviar Simulação</button>
        </form>
    </div>
</body>
</html>
