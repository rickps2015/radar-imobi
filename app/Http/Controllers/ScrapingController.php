<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScrapingController extends Controller
{
    public function postLeilaoData($property_number)
    {
        // URL da página para scraping
        $url = 'https://venda-imoveis.caixa.gov.br/sistema/detalhe-imovel.asp?hdnOrigem=index&hdnimovel=' . $property_number;

        // Usando cURL para pegar o conteúdo HTML
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Definindo o cabeçalho User-Agent para simular um navegador real
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ]);

        // Definindo a opção para seguir redirecionamentos
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Executa a requisição
        $html = curl_exec($ch);

        // Verifica se houve erro na requisição
        if (curl_errno($ch)) {
            return response()->json(['error' => 'Erro ao acessar a página: ' . curl_error($ch)], 500);
        }

        // Fecha a conexão cURL
        curl_close($ch);

        // Verifica se a requisição retornou conteúdo
        if ($html === false) {
            return response()->json(['error' => 'Erro ao acessar a página'], 500);
        }

        // Carregar o HTML com DOMDocument
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        // Usar DOMXPath para filtrar o conteúdo da tag <span>
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//span/i[@class="fa fa-gavel"]/parent::span');
        $data = [];

        foreach ($nodes as $node) {
            $data[] = $node->textContent;  // Extrai o texto dentro da tag <span>
        }

        // Verifica se encontrou os elementos
        if (!empty($data)) {
            $primaryLeilao = $data[0] ?? null;
            $secondLeilao = $data[1] ?? null;

            // Separar data e hora
            $primaryLeilaoParts = explode(' ', $primaryLeilao);
            $secondLeilaoParts = explode(' ', $secondLeilao);

            // Ajustar o formato da hora
            $primaryLeilaoHora = isset($primaryLeilaoParts[8]) ? str_replace('h', ':', $primaryLeilaoParts[8]) : null;
            $secondLeilaoHora = isset($secondLeilaoParts[8]) ? str_replace('h', ':', $secondLeilaoParts[8]) : null;

            // Salvar os dados na tabela properties
            \DB::table('properties')->where('property_number', $property_number)->update([
                'primary_leilao_data' => isset($primaryLeilaoParts[6]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $primaryLeilaoParts[6])->toDateString() : null,
                'primary_leilao_hora' => $primaryLeilaoHora ? \Carbon\Carbon::createFromFormat('H:i', $primaryLeilaoHora)->toTimeString() : null,
                'second_leilao_data' => isset($secondLeilaoParts[6]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $secondLeilaoParts[6])->toDateString() : null,
                'second_leilao_hora' => $secondLeilaoHora ? \Carbon\Carbon::createFromFormat('H:i', $secondLeilaoHora)->toTimeString() : null
            ]);

            return response()->json(['success' => 'Dados atualizados com sucesso']);
        }

        return response()->json(['error' => 'Elemento não encontrado'], 404);
    }
}
