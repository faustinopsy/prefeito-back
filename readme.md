# Sistema de Estatísticas de Intenção de Votos - Back-end

Este é o back-end do sistema autônomo para levantamento de estatísticas de intenção de votos para a prefeitura de São Paulo. Ele é responsável por armazenar os votos dos usuários e fornecer as informações necessárias para a geração de gráficos no front-end.
(Nessa versão não colocado conexão com mongo devido a limitação da versão do php utilizada, mas existe aqui a versão em node com o mongo db)
o link do front funcionando:
https://prefeitosp.faustinopsy.com

## Funcionalidades

- Registro de votos de usuários, incluindo o candidato e a região escolhidos.
- Verificação se um usuário já registrou um voto.
- Geração de dados agregados para os gráficos, tanto por candidato quanto por região.
- Suporte a várias bases de dados, incluindo MySQL, SQLite, PostgreSQL, Oracle, SQL Server e MongoDB.

## Estrutura do Projeto

- **/config/**: Contém a configuração de banco de dados.
- **/controllers/**: Contém os controladores responsáveis por gerenciar as requisições e respostas da API.
- **/models/**: Modelos que interagem com o banco de dados para manipulação dos dados.
- **/vendor/**: Diretório gerado pelo Composer que contém as dependências do projeto.

## Instalação

1. Clone este repositório:

   ```bash
   git clone https://github.com/faustinopsy/prefeito-back.git

## Instale as dependências via Composer:
```bash 
  composer install
```

## Configure o arquivo .env com as informações do seu banco de dados:
```
DB_TYPE=mysql
DB_HOST=localhost
DB_NAME=nome_do_banco
DB_USER=usuario
DB_PASS=senha
DB_PORT=3306
DB_SID=nome_do_sid # Para Oracle
```

## Inicie o servidor PHP:
```
php -S localhost:8000
```
## Uso
API de Votação:
- POST /voto: Registra um novo voto.
- GET /voto/quantidade: Retorna a quantidade total de votos por candidato.
- GET /voto/quantidade_por_regiao: Retorna a quantidade de votos por candidato agrupados por região.
- Privacidade: O sistema não captura endereços IP ou qualquer dado pessoal identificável. Apenas um identificador único, gerado a partir de dados do navegador, é utilizado para verificar se um usuário já votou.
## Tecnologias Utilizadas
- PHP 7+
- Composer: Gerenciamento de dependências.
- PDO: Interface de banco de dados para múltiplos SGBDs.
- Dotenv: Para gerenciamento de variáveis de ambiente.
## Licença
Este projeto é distribuído sob a licença MIT. Consulte o arquivo LICENSE para mais informações.
