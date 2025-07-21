<?php

return [
    'title' => 'Funções',
    'role' => 'Função',
    'roles' => 'Funções',
    'name' => 'Nome',
    'description' => 'Descrição',
    'permissions' => 'Permissões',
    'users_count' => 'Usuários',
    
    // Actions
    'create' => 'Criar Função',
    'edit' => 'Editar Função',
    'delete' => 'Excluir Função',
    'view' => 'Visualizar Função',
    'assign' => 'Atribuir Função',
    'remove' => 'Remover Função',
    
    // Messages
    'created_successfully' => 'Função criada com sucesso!',
    'updated_successfully' => 'Função atualizada com sucesso!',
    'deleted_successfully' => 'Função excluída com sucesso!',
    'assigned_successfully' => 'Função atribuída com sucesso!',
    'removed_successfully' => 'Função removida com sucesso!',
    
    // Confirmations
    'confirm_delete' => 'Tem certeza que deseja excluir esta função?',
    'confirm_remove' => 'Tem certeza que deseja remover esta função do usuário?',
    
    // Errors
    'not_found' => 'Função não encontrada.',
    'cannot_delete' => 'Não é possível excluir esta função.',
    'already_assigned' => 'Esta função já está atribuída ao usuário.',
    'not_assigned' => 'Esta função não está atribuída ao usuário.',
    
    // Placeholders
    'search_placeholder' => 'Buscar funções...',
    'name_placeholder' => 'Digite o nome da função',
    'description_placeholder' => 'Digite a descrição da função',
    
    // Default roles
    'admin' => 'Administrador',
    'manager' => 'Gerente',
    'user' => 'Usuário',
    'guest' => 'Visitante',
    
    // Form labels
    'form' => [
        'name' => 'Nome da Função',
        'description' => 'Descrição da Função',
        'permissions' => 'Selecionar Permissões',
        'guard_name' => 'Guard',
    ],
    
    // Validation messages
    'validation' => [
        'name_required' => 'O nome da função é obrigatório.',
        'name_unique' => 'Já existe uma função com este nome.',
        'description_required' => 'A descrição da função é obrigatória.',
        'permissions_required' => 'Selecione pelo menos uma permissão.',
    ],
];
