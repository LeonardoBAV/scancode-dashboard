<?php

declare(strict_types=1);

return [

    'client' => [
        'navigation_label' => 'Clientes',
        'plural_model_label' => 'Clientes',
        'model_label' => 'Cliente',
        'table' => [
            'cpf_cnpj' => 'CPF/CNPJ',
            'corporate_name' => 'Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'email' => 'Email',
            'phone' => 'Telefone',
        ],
        'infolist' => [
            'title' => 'Informações do cliente',
            'cpf_cnpj' => 'CPF/CNPJ',
            'corporate_name' => 'Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'email' => 'Email',
            'phone' => 'Telefone',
            'timestamps_title' => 'Dados Temporais',
        ],
        'form' => [
            'title' => 'Dados do cliente',
            'cpf_cnpj' => 'CPF/CNPJ',
            'corporate_name' => 'Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'email' => 'Email',
            'phone' => 'Telefone',
        ],
    ],
    'sales_representative' => [
        'navigation_label' => 'Representantes',
        'plural_model_label' => 'Representantes',
        'model_label' => 'Representante',
        'table' => [
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
        ],
        'infolist' => [
            'title' => 'Informações do representante',
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
            'timestamps_title' => 'Dados Temporais',
        ],
        'form' => [
            'title' => 'Dados do representante',
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
            'password' => 'Senha',
        ],
    ],

];
