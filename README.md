# Sistema de Tickets de Atendimento

Sistema desenvolvido em Laravel para gerenciamento de tickets de atendimento, proporcionando uma interface intuitiva para usuários e atendentes.

## Funcionalidades Principais

### Gestão de Tickets
- Criação de tickets com título, descrição, categoria e prioridade
- Atribuição automática de status (Aberto, Em Andamento, Resolvido, Fechado, Cancelado)
- Sistema de prioridades (Baixa, Média, Alta, Urgente)
- Categorização de tickets para melhor organização

### Sistema de Atendimento
- Interface específica para atendentes
- Botões de controle de atendimento:
  - Assumir ticket
  - Pausar atendimento
  - Retomar atendimento
  - Finalizar atendimento
- Controle de tempo de atendimento:
  - Registro de tempo total de atendimento
  - Registro de tempo em pausa
  - Histórico de início e fim de atendimento

### Sistema de Mensagens
- Troca de mensagens entre usuários e atendentes
- Suporte para anexos em mensagens
- Histórico completo de comunicação
- Opção de exclusão de mensagens

### Controle de Acesso
- Separação entre usuários comuns e atendentes
- Permissões específicas por tipo de usuário
- Proteção de rotas e ações baseada em permissões

### Interface
- Design responsivo com Tailwind CSS
- Feedback visual para ações importantes
- Indicadores visuais de status e prioridade
- Botões com efeitos visuais para melhor interatividade

## Tecnologias Utilizadas

- Laravel 10.x
- PHP 8.1+
- MySQL
- Tailwind CSS
- Laravel Breeze para autenticação

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Requisitos do Sistema

### Requisitos de Sistema
- PHP 8.1 ou superior
- Composer
- MySQL 5.7 ou superior
- Node.js e NPM (para assets)

### Extensões PHP Necessárias
- BCMath
- Ctype
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

## Instalação

1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/system-tickets.git
```

2. Instale as dependências do PHP
```bash
composer install
```

3. Copie o arquivo de ambiente
```bash
cp .env.example .env
```

4. Configure o banco de dados no arquivo .env

5. Gere a chave da aplicação
```bash
php artisan key:generate
```

6. Execute as migrações
```bash
php artisan migrate
```

7. Instale as dependências do frontend
```bash
npm install
npm run dev
```

## Uso

### Tipos de Usuário

1. **Usuário Comum**
   - Pode criar tickets
   - Visualizar seus próprios tickets
   - Trocar mensagens com atendentes

2. **Atendente**
   - Visualizar todos os tickets
   - Assumir tickets para atendimento
   - Controlar tempo de atendimento
   - Responder tickets
   - Finalizar atendimentos

## Manutenção

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Status dos Tickets

Os tickets podem ter os seguintes status:

- **Aberto**: Ticket recém criado, aguardando atendimento
- **Em Andamento**: Ticket assumido por um atendente
- **Resolvido**: Atendimento finalizado com sucesso
- **Fechado**: Ticket concluído e arquivado
- **Cancelado**: Ticket cancelado sem resolução

## Controle de Tempo

O sistema registra automaticamente:

- Momento em que o ticket foi assumido
- Períodos de pausa no atendimento
- Tempo total gasto no atendimento
- Tempo total em pausa
- Data e hora de resolução

## Mensagens e Anexos

- Suporte para troca de mensagens entre usuários e atendentes
- Possibilidade de anexar arquivos às mensagens
- Histórico completo de comunicação mantido
- Controle de permissões para exclusão de mensagens

## Licença

Este sistema é um software proprietário. Todos os direitos reservados.
