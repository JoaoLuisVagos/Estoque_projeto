# EstoqueBebibas

Sistema completo para gestão de estoque de bebidas, com frontend em React (Vite), backend em PHP (FlightPHP) e banco de dados MySQL, totalmente dockerizado.

---

## Funcionalidades

- Cadastro, edição e exclusão de bebidas
- Registro de movimentações de entrada e saída (com volume decimal)
- Listagem e filtro de bebidas e movimentações
- Controle de estoque disponível por tipo de bebida
- Interface web responsiva e intuitiva
- Autenticação de usuários

---

## Tecnologias Utilizadas

- **Frontend:** React + Vite
- **Backend:** PHP 8.1 + Apache + FlightPHP
- **Banco de Dados:** MySQL 8
- **Containerização:** Docker & Docker Compose

---

## Instalação e Uso

### Pré-requisitos

- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados

### Passos para rodar o projeto

1. **Clone o repositório:**
   ```bash
   git clone https://gitlab.com/JoaoLuisVagos/EstoqueBebidas.git
   cd EstoqueBebidas
   ```

2. **Suba os containers:**
   ```bash
   docker compose up --build
   ```

3. **Acesse no navegador:**
   - Frontend: [http://localhost:5173](http://localhost:5173)
   - Backend/API: [http://localhost:8081](http://localhost:8081)

4. **Banco de dados:**
   - Host: `mysql`
   - Usuário: `estoque_user`
   - Senha: `estoque_pass`
   - Banco: `estoque`
   - Porta local: `3307`

5. **Crie as tabelas (caso não existam):**
   ```bash
   docker exec -i estoque-mysql mysql -u estoque_user -pestoque_pass estoque < Estoque-Backend/migration/create_tables.sql
   ```

---

## Estrutura do Projeto

```
EstoqueBebibas/
├── docker-compose.yml
├── Estoque-Frontend/
│   ├── Dockerfile
│   ├── src/
│   │   └── components/
│   │       ├── bebidas/
│   │       └── movimentacoes/
|   |       └── login/
│   └── ...
├── Estoque-Backend/
│   ├── Dockerfile
│   ├── index.php
│   ├── .htaccess
│   ├── config/
│   ├── controller/
│   ├── dao/
│   ├── migration/
│   ├── model/
│   ├── routes/
│   ├── utils/
│   └── vendor/
└── ...
```

---

## Observações

- O sistema aceita valores decimais para estoque e movimentações, garantindo precisão no controle de volumes.
- O backend retorna corretamente os valores decimais nas rotas de consulta.
- O frontend está preparado para manipular e exibir valores decimais.
- O controle de estoque é feito exclusivamente pelo campo `estoque_total` das bebidas.
- O histórico de movimentações registra o volume movimentado em cada operação.

---

## Autor

João Luís Vagos

---
