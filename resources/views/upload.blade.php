<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkalaCode - Extrator Fiscal IA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen pb-12">

    <nav class="bg-white border-b border-slate-200 mb-10">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">S</div>
                <span class="font-bold text-lg tracking-tight">Skala<span class="text-indigo-600">Code</span></span>
            </div>
            <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded">Desafio Técnico</span>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 space-y-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h1 class="text-lg font-bold text-slate-800">Processar Nova Nota</h1>
                <p class="text-sm text-slate-500">Envie fotos ou PDFs para extração automática via Inteligência Artificial.</p>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-3 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor text-emerald-500">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('invoice.process') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Selecione o arquivo da Nota Fiscal</label>
                        <div class="relative">
                            <input type="file" name="invoice_file" accept=".pdf,image/*" required 
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg p-1 bg-slate-50/50">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-all font-bold shadow-md shadow-indigo-200 active:scale-95 flex justify-center items-center gap-2 uppercase text-xs tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Extrair via IA
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Histórico de Notas
                </h2>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700">{{ count($invoices ?? []) }} registros</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">CNPJ</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Produtos</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Categoria</th>
                    </tr>
                </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($invoices ?? [] as $invoice)
                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="openModal('{{ url('/invoice/view/' . $invoice->id) }}')">
                            <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-900">{{ $invoice->company_name }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-500">{{ $invoice->cnpj }}</div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-600 max-w-xs truncate">
                                    {{ is_array($invoice->items) ? implode(', ', $invoice->items) : $invoice->items }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                                {{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '--/--/----' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-indigo-600">R$ {{ number_format($invoice->total_value, 2, ',', '.') }}</span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full uppercase">
                                    {{ $invoice->category }}
                                </span>
                            </td>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 text-center">
                                <button onclick="openModal('{{ url('/invoice/view/' . $invoice->id) }}')" 
                                        class="inline-flex items-center justify-center w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100" 
                                        title="Visualizar Nota">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <span class="text-slate-400 font-medium italic">Nenhuma nota fiscal processada ainda.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <footer class="mt-20 text-center text-slate-400 text-xs">
        &copy; {{ date('Y') }} Michael Mallmann - Desenvolvido para Desafio Técnico
    </footer>
            <div id="invoiceModal" class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden">
                    
                    <div class="p-4 border-b flex justify-between items-center bg-white">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="p-1.5 bg-indigo-100 text-indigo-600 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            Documento Fiscal
                        </h3>
                        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-3xl leading-none">&times;</button>
                    </div>
                    
                    <div class="flex-1 bg-slate-200 relative">
                        <iframe id="pdfViewer" src="" class="absolute inset-0 w-full h-full" frameborder="0"></iframe>
                    </div>
                    
                    <div class="p-4 border-t bg-slate-50 flex justify-end">
                        <button onclick="closeModal()" class="px-6 py-2 bg-slate-800 text-white rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-slate-700 transition-all">
                            Fechar Visualização
                        </button>
                    </div>
                </div>
            </div>

            <script>
                function openModal(url) {
                    console.log("Abrindo URL:", url); // Isto ajuda a debugar no F12
                    const modal = document.getElementById('invoiceModal');
                    const viewer = document.getElementById('pdfViewer');
                    
                    viewer.src = url;
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
                
                function closeModal() {
                    const modal = document.getElementById('invoiceModal');
                    const viewer = document.getElementById('pdfViewer');
                    
                    modal.classList.add('hidden');
                    viewer.src = '';
                    document.body.style.overflow = 'auto';
                }
            </script>

            <script>
                function openModal(url) {
                    const viewer = document.getElementById('pdfViewer');
                    viewer.src = url;
                    document.getElementById('invoiceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Trava o scroll da página atrás
                }
                
                function closeModal() {
                    document.getElementById('invoiceModal').classList.add('hidden');
                    document.getElementById('pdfViewer').src = '';
                    document.body.style.overflow = 'auto'; // Devolve o scroll
                }

                // Fecha o modal se clicar fora da área branca
                window.onclick = function(event) {
                    const modal = document.getElementById('invoiceModal');
                    if (event.target == modal) {
                        closeModal();
                    }
                }
            </script>
</body>
</html>