# OMDb Laravel API - Bruno Menossi

## Instalação
1. Configurar o `.env` com as crendencias de banco de dados
2. Executar o comando `composer install` para insatalção das depêndencias do projeto
3. Executar o comando `php artisan migrate` para criação da estrutra do banco de dados
4. Executar o comando `php artisan movies:import "King Lion"` para importar filmes
5. Executar o comando `php artisan serve` para iniciar o servidor artisan

### Rodar testes
  Execute `php artisan test` para rodar os testes de unidade.

## Endpoint:
 GET /api/movies

Parâmetros opcionais:
- `title`
- `year`
- `director`

# Documentação da API

A documentação da API está disponível no arquivo [openapi.yaml](docs/openapi.yaml).





