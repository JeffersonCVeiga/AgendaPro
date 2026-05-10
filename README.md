# AgendaPro

Sistema de agenda eletrônica desenvolvido em PHP.

## Tecnologias
- PHP
- MySQL
- Bootstrap
- jQuery
- XAMPP

## Como executar

### 1. Instalar XAMPP
https://www.apachefriends.org/pt_br/index.html

Instale em:
C:\xampp

### 2. Iniciar serviços
Abra o XAMPP Control Panel e inicie:
- Apache
- MySQL

### 3. Colocar projeto
Extraia em:
C:\xampp\htdocs\agendapro

### 4. Criar banco
Abra:
http://localhost/phpmyadmin

Crie o banco:
agendapro

### 5. Importar banco
Importe:
schema.sql

### 6. Configurar conexão
Arquivo:
config/database.php

Configuração:
DB_HOST = localhost
DB_NAME = agendapro
DB_USER = root
DB_PASS = ''

### 7. Abrir sistema
http://localhost/agendapro

Caso necessário:
http://localhost/agendapro/public
