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
            'cpf_cnpj' => 'CPF/CNPJ',
            'corporate_name' => 'Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'email' => 'Email',
            'phone' => 'Telefone',
        ],
        'form' => [
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
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
        ],
        'form' => [
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
            'password' => 'Senha',
        ],
    ],
    'product' => [
        'navigation_label' => 'Produtos',
        'plural_model_label' => 'Produtos',
        'model_label' => 'Produto',
        'table' => [
            'sku' => 'SKU',
            'barcode' => 'Código de Barras',
            'name' => 'Nome',
            'price' => 'Preço',
            'product_category_name' => 'Categoria do Produto',
        ],
        'infolist' => [
            'sku' => 'SKU',
            'barcode' => 'Código de Barras',
            'name' => 'Nome',
            'price' => 'Preço',
            'product_category_name' => 'Categoria do Produto',
        ],
        'form' => [
            'sku' => 'SKU',
            'barcode' => 'Código de Barras',
            'name' => 'Nome',
            'price' => 'Preço',
            'product_category_name' => 'Categoria do Produto',
        ],
    ],
];
