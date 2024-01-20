# Sobre o Projeto

Este é um projeto feito em Laravel que ajudará com gerenciamento de campeonatos de futsal. Ele fornece uma API Rest com autenticação para se criar, ler (um ou mais), editar e excluir times, jogadores, partidas e o campeonato. 

## Iniciando o Projeto

Tendo presuposto que você já possui o php na versão 8.1.2 instalada em sua máquina junto com as extensões necessárias (curl, xml, mysql), o mysql e git instalado, basta seguir os seguintes comandos: 

1 - Clonar o projeto
```bash
  git clone https://github.com/RJpedro/futebol-api.git
```

2 - Entrar na pasta do projeto
```bash
  cd futebol-api
```

3 - Instalar as dependências do node
```bash
  npm install
```

4 - Instalar as dependências do composer (Caso ele diga que o lock file não cont)
```bash
  composer install
```

5 - Clone o arquivo .env.example da raiz do sistema, o renomeie para .env e edite as variáveis de conexão com banco de dados.

6 - Antes de executar a migrate será necessário criar o banco que irá armazenar as tabelas

7 - Execute a migrate com o artisan para que as tabelas necessárias para o funcionamento do projeto sejam criadas
```bash
  php artisan migrate
```
 
8 - Este comando gera a chave de aplicação necessária para a segurança da sua aplicação Laravel
```bash
  php artisan key:generate
```

9 - Inicie o projeto com o artisan
```bash
  php artisan serve
```

## Documentação das rotas da API

- ```/api/v1/championship```
- ```/api/v1/championship-match```
- ```/api/v1/player```
- ```/api/v1/team```
- ```/api/v1/user```
- ```/api/v1/login```

Essas são as possíveis rotas que foram criadas afim de satisfazer todo o projeto, vale resaltar que apenas a rota login possui um único método que é o POST (pois essa rota apenas irá conceder o bearer token para utilização das demais rotas caso o usuário possua cadastro). 

### Exemplo de utilização da API

#### Cadastro de usuário  
```http
  POST /api/v1/user
```
```json
{
  "name": "Usuário de Exemplo",
  "email": "email@exemplo.com",
  "password": "senhaMtForte"
}
```

#### Solicitando Token de Acesso as Demais Rotas
```http
  POST /api/v1/login
```
```json
{
  "email": "email@exemplo.com",
  "password": "senhaMtForte"
}
```

Assim que receber o retorno dessa rota, guarde o bearer token para utiliza-lo nas demais requisições, caso esteja utilizando o POSTMAN você terá de entrar na aba 'Authorization', selecionar o tipo 'Bearer Token' e no campo que aparecer basta colar o token recebido e assim poderá utilizar de determinados serviços dessa API.

#### Criando um Time
```http
  POST /api/v1/team
```
```json
{
  "name": "Palmeiras"
}
```

Ao criar um time ele automaticamente será adicionado a tabela do campeonato (championships), então não há necessidade de adicioná-lo a mão nessa tabela. Após criar o time será possível começar a criar os jogadores e então atribui-los aos seus respectivos times.

#### Criando um Jogador
```http
  POST /api/v1/player
```
```json
{
  "name": "Joãozinho",
  "number": 15,
  "team_id": 1 
}
```

Após criar pelomenos dois times será possível começar a criar as partidas do campeonato, para isso basta seguir o exemplo abaixo. 

#### Cadastrando uma Partida
```http
  POST /api/v1/championship-match
```
```json
{
  "date": "2024-01-19",
  "start_time": "10:00",
  "away_team_id": 1,
  "home_team_id": 2
}
```

Com isso será possível cadastrar a partida entretanto assim que a mesma for finalizada será necessário atualizar a tabela para que seja contabilizado na tabela de campeonato os pontos de ambos os times.

#### Atualizando uma Partida
```http
  PATCH /api/v1/championship-match/1
```
```json
{
  "away_team_goals": 2,
  "home_team_goals": 0,
  "end_time": "11:32",
  "is_ended": true
}
```

Dessa forma será contabilizado corretamente na tabela do campeonato os pontos de cada time, assim comos os gols feitos (Vale ressaltar que depois que um jogo é finalizado não é mais possível alterar qualquer dado que seja).

#### Verificando a Tabela Campeonato

Agora que você já entendeu como funciona o fluxo de cadastro nessa Api, sugiro fazer uma requisição para saber como ficou a tabela no final do seu campeonato. 

```http
  GET /api/v1/championship
```