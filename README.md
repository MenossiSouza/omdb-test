# OMDb Laravel API - Bruno Menossi

## Instalação
1. Configurar o `.env` com as crendencias de banco de dados e a key(OMDB_API_KEY) que você pode gerar em: [omdbapi](https://www.omdbapi.com/apikey.aspx)
2. Configurar `API_SECRET` no `.env` com: `API_SECRET=AS214412OJ@IXAS@OAPAU`
2. Executar o comando `composer install` para instalação das dependências do projeto
3. Executar o comando `php artisan key:generate` (caso ainda não tenha uma APP_KEY)
4. Executar o comando `php artisan migrate` para criação da estrutura do banco de dados
5. Executar o comando `php artisan movies:import "King Lion"` para importar filmes
6. Executar o comando `php artisan serve` para iniciar o servidor artisan

### Rodar testes
  Execute `php artisan test` para rodar os testes de unidade e funcionais.

## Endpoint:
 GET /api/movies

Parâmetros opcionais:
- `title`
- `year`
- `director`

# Documentação da API
A documentação da API está disponível no arquivo [openapi.yaml](docs/openapi.yaml).

## Como usar a documentação
Você pode usar ferramentas como o [Swagger UI](https://editor.swagger.io) para visualizar a documentação da API a partir do arquivo `openapi.yaml`.





