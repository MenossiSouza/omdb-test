# OMDb Laravel API - Bruno Menossi

## Instalação
1. Configurar o `.env` com as crendencias de banco de dados
2. Executar o comando `composer install` para insatalção das depêndencias do projeto
3. Executar o comando `php artisan migrate` para criação da estrutra do banco de dados
4. Executar o comando `php artisan movies:import "King Lion"` para importar filmes
5. Executar o comando `php artisan serve` para iniciar o servidor artisan

## Endpoint:
 GET /api/movies

Parâmetros opcionais:
- `title`
- `year`
- `director`

## Exemplo de requisição:
   # Request
    'GET http://127.0.0.1:8000/api/movies?title=King lion'
   # Response
    [
        {
            "id": 37,
            "imdb_id": "tt2374051",
            "title": "Simba: The King Lion",
            "year": "1995",
            "director": "N\/A",
            "genre": "Animation, Action, Drama",
            "created_at": "2025-04-26T08:14:03.000000Z",
            "updated_at": "2025-04-26T08:14:03.000000Z"
        }
    ]

## Testes
-  `can create movie instance - consegue criar uma instância de movie?`
-  `movies endpoint returns data - endpoit da consulta de filmes retorna resultados?`
-  `import movies successfully - importação de filmes existentes/encontrados funciona com sucesso?`
-  `import movies not found   - importação de filmes não existentes/não encontrados funciona com sucesso?`

### Rodar testes
  Execute `php artisan test` para rodar os testes de unidade.
