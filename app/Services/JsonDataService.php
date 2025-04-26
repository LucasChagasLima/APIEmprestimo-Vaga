<?php

namespace App\Services;

/**
 * Responsável por carregar e manipular os dados dos arquivos JSON recebidos  
 *que contêm informações sobre instituições, convênios e taxas.
 **/
class JsonDataService
{

    protected $basePath;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->basePath = storage_path('app/json');
    }

    /**
     * instituições disponíveis
     * 
     * @return array 
     */
    public function getInstituicoes()
    {
        return $this->loadJsonFile('instituicoes.json');
    }

    /**
     * Obtém a lista de convênios disponíveis
     * 
     * @return array Lista de convênios no formato chave-valor
     */
    public function getConvenios()
    {
        return $this->loadJsonFile('convenios.json');
    }

    /**
     * Obtém as taxas das instituições
     * 
     * @return array Lista de taxas por instituição
     */
    public function getTaxasInstituicoes()
    {
        return $this->loadJsonFile('taxas_instituicoes.json');
    }

    /**
     * Carrega um arquivo JSON e retorna seu conteúdo
     * 
     * @param string $filename Nome do arquivo JSON
     * @return array Conteúdo do arquivo JSON
     */
    protected function loadJsonFile($filename)
    {
        $path = $this->basePath . '/' . $filename;
        
        if (!file_exists($path)) {
            return [];
        }
        
        $content = file_get_contents($path);
        return json_decode($content, true) ?? [];
    }
}
