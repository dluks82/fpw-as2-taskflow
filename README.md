# TaskFlow

TaskFlow é um web app de gerenciamento de tarefas, que pode ser usado por qualquer pessoa que deseja organizar suas atividades. O aplicativo permite criar, editar, excluir e marcar tarefas como concluídas, com uma interface amigável e intuitiva. Ele também suporta filtros para visualizar tarefas pendentes, concluídas e vencidas.

## Requisitos

- Docker
- Docker Compose

## Estrutura do Projeto

- **Dockerfile**: Define a configuração do contêiner Docker para o servidor web Apache e PHP.
- **docker-compose.yml**: Arquivo principal do Docker Compose que define os serviços comuns.
- **docker-compose.override.yml**: Extensão do arquivo principal para o ambiente de desenvolvimento, adicionando serviços de banco de dados MySQL e phpMyAdmin.
- **docker-compose.prod.yml**: Extensão do arquivo principal para o ambiente de produção, configurando apenas o servidor web.
- **entrypoint.sh**: Script de entrada para o contêiner Docker que instala dependências do Composer e inicia o servidor Apache.

## Executando o Projeto Localmente

### Passo 1: Clonar o Repositório

```sh
git clone https://github.com/dluks82/fpw-as2-taskflow.git
cd taskflow
```

### Passo 2: Configurar as Variáveis de Ambiente

Crie um arquivo `.env` na raiz do projeto e configure as variáveis de ambiente necessárias:

```env
# Comum
APP_ENV=local

# Local
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=taskflow
MYSQL_USER=user
MYSQL_PASSWORD=password
```

### Passo 3: Iniciar os Serviços com Docker Compose

Para iniciar o ambiente de desenvolvimento local, que inclui o servidor web, MySQL e phpMyAdmin, execute:

```sh
docker-compose up -d
```

### Passo 4: Criar as Tabelas no Banco de Dados

Após iniciar os serviços, você precisa criar as tabelas no banco de dados. Conecte-se ao MySQL no contêiner e execute o script SQL.

#### Usando phpMyAdmin (apenas em desenvolvimento)

- Acesse phpMyAdmin em <http://localhost:8083>.

- Faça login com as credenciais configuradas no .env (MYSQL_USER e MYSQL_PASSWORD).

- Selecione o banco de dados taskflow.

- Importe o arquivo SQL localizado em init-scripts/init.sql.

#### Usando a linha de comando

- Acesse o contêiner MySQL:

```sh
docker exec -it mysql_php_taskflow mysql -u user -ppassword taskflow
```

- Execute o script SQL:

```sql
SOURCE /db_scripts/init.sql;
```

### Passo 5: Acessar o Aplicativo

Depois que os contêineres estiverem em execução, você pode acessar o aplicativo no navegador:

Aplicativo Web: <http://localhost:8082>

phpMyAdmin: <http://localhost:8083> (apenas em desenvolvimento)

### Passo 6: Encerrar os Serviços

Para parar e remover os contêineres, volumes e redes criadas pelo Docker Compose, execute:

```sh
docker-compose down
```

## Estrutura de Diretórios

- **src/**: Código-fonte do aplicativo.
- **init-scripts/**: Scripts SQL para inicialização do banco de dados.
- **.env**: Arquivo de variáveis de ambiente.
- **Dockerfile**: Arquivo de configuração do Docker para o servidor web.
- **docker-compose.yml**: Arquivo de configuração do Docker Compose.
- **docker-compose.override.yml**: Arquivo de configuração adicional para desenvolvimento.
- **docker-compose.prod.yml**: Arquivo de configuração adicional para produção.
- **entrypoint.sh**: Script de entrada do contêiner Docker.

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
