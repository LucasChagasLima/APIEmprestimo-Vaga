<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\JsonDataService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Rotas da API de empréstimos
 * 
 * Endpoints necessários para a simulação de empréstimos conforme pedido no projeto.
 */
class EmprestimoController extends Controller
{
 
    protected $jsonDataService;

    /**
     * @param JsonDataService 
     */
    public function __construct(JsonDataService $jsonDataService)
    {
        $this->jsonDataService = $jsonDataService;
    }

    /**
     * Retorna a lista de instituições disponíveis
     * 
     * @return JsonResponse Lista de instituições no formato chave-valor
     */
    public function getInstituicoes(): JsonResponse
    {
        $instituicoes = $this->jsonDataService->getInstituicoes();
        return response()->json($instituicoes);
    }

    /**
     * Retorna a lista de convênios disponíveis
     * 
     * @return JsonResponse Lista de convênios no formato chave-valor
     */
    public function getConvenios(): JsonResponse
    {
        $convenios = $this->jsonDataService->getConvenios();
        return response()->json($convenios);
    }

    /**
     * Realiza a simulação de crédito disponível para o cliente
     * 
     * @param Request $request Requisição com os parâmetros da simulação
     * @return JsonResponse Resultado da simulação
     */
    public function simularEmprestimo(Request $request): JsonResponse
    {
        // Validação dos dados de entrada
        $request->validate([
            'valor_emprestimo' => 'required|numeric',
            'instituicoes' => 'nullable|array',
            'convenios' => 'nullable|array',
            'parcela' => 'nullable|integer'
        ]);

        // Obtém os parâmetros da requisição
        $valorEmprestimo = $request->input('valor_emprestimo');
        $instituicoesFiltro = $request->input('instituicoes', []);
        $conveniosFiltro = $request->input('convenios', []);
        $parcelaFiltro = $request->input('parcela');

        // Obtém todas as taxas disponíveis
        $taxas = $this->jsonDataService->getTaxasInstituicoes();
        
        // Filtra as taxas de acordo com os parâmetros recebidos
        $resultado = $this->filtrarTaxas(
            $taxas, 
            $valorEmprestimo, 
            $instituicoesFiltro, 
            $conveniosFiltro, 
            $parcelaFiltro
        );

        return response()->json($resultado);
    }

    /**
     * @param array $taxas Lista de taxas disponíveis
     * @param float $valorEmprestimo Valor do empréstimo solicitado
     * @param array $instituicoesFiltro Lista de instituições para filtrar
     * @param array $conveniosFiltro Lista de convênios para filtrar
     * @param int|null $parcelaFiltro Número de parcelas para filtrar
     * @return array Lista de taxas filtradas com o valor da parcela calculado
     */
    private function filtrarTaxas(
        array $taxas, 
        float $valorEmprestimo, 
        array $instituicoesFiltro, 
        array $conveniosFiltro, 
        ?int $parcelaFiltro
    ): array {
        $resultado = [];

        foreach ($taxas as $taxa) {
            // Filtra por instituição se especificado
            if (!empty($instituicoesFiltro) && !in_array($taxa['instituicao'], $instituicoesFiltro)) {
                continue;
            }

            // Filtra por convênio se especificado
            if (!empty($conveniosFiltro) && !in_array($taxa['convenio'], $conveniosFiltro)) {
                continue;
            }

            // Filtra por parcela se especificado
            if ($parcelaFiltro !== null && $taxa['parcelas'] != $parcelaFiltro) {
                continue;
            }

            // Calcula o valor da parcela
            $valorParcela = $valorEmprestimo * $taxa['coeficiente'];

            // Adiciona o item ao resultado
            $resultado[] = [
                'parcelas' => $taxa['parcelas'],
                'taxaJuros' => $taxa['taxaJuros'],
                'coeficiente' => $taxa['coeficiente'],
                'valor_parcela' => round($valorParcela, 2),
                'instituicao' => $taxa['instituicao'],
                'convenio' => $taxa['convenio']
            ];
        }

        return $resultado;
    }
}
