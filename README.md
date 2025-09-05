# EstoqueBebibas

Sistema de gestão de estoque de bebidas, com frontend em React (Vite), backend em PHP (FlightPHP) e banco de dados MySQL, totalmente dockerizado.

---

## Funcionalidades

- Cadastro, edição e exclusão de bebidas
- Registro de movimentações de entrada e saída
- Listagem e filtro de bebidas e movimentações
- Controle de estoque disponível
- Interface web responsiva

---

## Tecnologias

- **Frontend:** React + Vite
- **Backend:** PHP 8.1 + Apache + FlightPHP
- **Banco de Dados:** MySQL 8
- **Containerização:** Docker & Docker Compose

---

## Instalação e uso rápido

### Pré-requisitos

- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados

### Passos

1. **Clone o repositório:**
   ```bash
   git clone https://gitlab.com/joaoluisvagos1/EstoqueBebibas.git
   cd EstoqueBebibas
   ```

2. **Suba os containers:**
   ```bash
   docker compose up --build
   ```

3. **Acesse no navegador:**
   - Frontend: [http://localhost:5174](http://localhost:5174)
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

## Autor

João Luís Vagos

---

## Feedback ##

**1. O que achei do desafio?**

Achei o desafio bem estruturado e realista, pois simula um cenário comum de gestão de estoque, exigindo tanto a criação de uma API RESTful quanto de uma interface visual moderna. O grau de dificuldade é intermediário a avançado, já que envolve múltiplas tecnologias (PHP, FlightPHP, MySQL, React, Docker) e integração entre elas. Os principais desafios encontrados foram garantir a correta comunicação entre os containers Docker, tratar as regras de negócio específicas (como a separação dos tipos de bebidas e as restrições de volume), além de implementar o controle de histórico de movimentações de forma consistente e ordenável. Também foi importante lidar com detalhes como CORS e padronização das respostas da API.

**2. Alteraria algo no desafio? O que sugeriria para avaliar melhor suas habilidades?**

Não mudaria nada, porem acrescentaria algumas funcionalidades como excluir, editar.

No geral, gostei bastante do desafio e acredito que ele avalia bem as habilidades técnicas e de arquitetura, tomei a liberdade de adicionar algumas funcionalidades como edição e uma busca com filtros mais robusta, espero que gostem.

---
