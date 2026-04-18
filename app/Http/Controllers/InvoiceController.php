<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->get();
        return view('upload', compact('invoices'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'invoice_file' => 'required|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);
        
        $file = $request->file('invoice_file');
        $mimeType = $file->getMimeType();
        $base64Data = base64_encode(file_get_contents($file->path()));

        $prompt = "Analise este documento fiscal. Extraia os seguintes dados e retorne EXCLUSIVAMENTE um JSON válido com as seguintes chaves (em inglês):
        'company_name' (nome da empresa emissora),
        'cnpj' (apenas números ou formatado),
        'date' (formato YYYY-MM-DD),
        'total_value' (apenas o número com ponto para decimais),
        'items' (um array de strings com os nomes dos produtos),
        'category' (sugira uma categoria como: alimentação, transporte, eletrônicos, serviços, etc).
        Retorne SOMENTE o JSON, sem texto adicional, sem markdown, sem blocos de código.";

        $apiKey = env('ANTHROPIC_API_KEY');

        $isImage = in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg']);

        $contentBlock = $isImage
            ? ['type' => 'image', 'source' => ['type' => 'base64', 'media_type' => $mimeType, 'data' => $base64Data]]
            : ['type' => 'document', 'source' => ['type' => 'base64', 'media_type' => 'application/pdf', 'data' => $base64Data]];

        try {
            $response = Http::withoutVerifying()
                ->retry(3, 3000)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model' => 'claude-opus-4-7',
                    'max_tokens' => 1024,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                $contentBlock,
                                ['type' => 'text', 'text' => $prompt],
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                $errorDetail = $response->json('error.message') ?? 'O servidor da IA está sobrecarregado.';
                return back()->with('error', 'Falha na IA: ' . $errorDetail);
            }

            $result = $response->json();
            $jsonText = $result['content'][0]['text'] ?? '{}';
            $extractedData = json_decode($jsonText, true);

        } catch (\Exception $e) {
            return back()->with('error', 'O Claude não respondeu a tempo. Por favor, tente novamente.');
        }

        
        if (!$extractedData || !isset($extractedData['company_name'])) {
            return back()->with('error', 'A IA não conseguiu estruturar os dados desta nota.');
        }

      
        $path = $file->store('invoices', 'public');

        Invoice::create([
            'file_path'    => $path,
            'company_name' => $extractedData['company_name'] ?? 'Não identificado',
            'cnpj'         => $extractedData['cnpj'] ?? 'Não identificado',
            'date'         => $extractedData['date'] ?? null,
            'total_value'  => $extractedData['total_value'] ?? 0,
            'items'        => $extractedData['items'] ?? [],
            'category'     => $extractedData['category'] ?? 'Outros',
        ]);

        return back()->with('success', 'Nota fiscal processada e salva com sucesso!');
    }

   public function viewFile($id) {
    $invoice = Invoice::findOrFail($id);
    
  
    if (empty($invoice->file_path)) {
        return "Erro: Este registro não possui um arquivo vinculado no banco de dados.";
    }

    $path = storage_path('app/public/' . $invoice->file_path);

    if (!file_exists($path)) {
      
        return "Arquivo não encontrado no caminho: " . $path;
    }

    return response()->file($path);
    }
}