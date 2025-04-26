# API de Simulação de Empréstimos

Esta API REST foi desenvolvida para permitir simulações de empréstimos, considerando diferentes instituições financeiras, convênios e taxas.
A API foi desenvolvida seguindo a arquitetura MVC (Model-View-Controller) com uma camada de serviço a Sevices/JsonDataService, esta camada é 
responsável por Acessar os dados dos arquivos JSON, Fornecer uma interface consistente para o controlador obter os dados, Isolar a lógica de 
acesso aos dados do restante da aplicação. Com isso podemos mudar a fonte de dados sem precisar alterar a Controller.

O arquivo do postman esta com o nome: "SimulacaoTeste.postman_collection" e também subi uma pasta RegTestes com as prints. (Também anexei ao e-mail esses arquivos)

Desde já agradeço a oportunidade. 



## Requisitos

- PHP 8.1 ou superior
- Composer

```
