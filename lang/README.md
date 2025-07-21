# Arquivo de Tradução para Roles

## Arquivo criado:

1. `lang/pt_BR/roles.php` - Traduções em Português Brasileiro

## Como usar as traduções:

### No Blade:
```blade
<!-- Título simples -->
<h1>{{ __('roles.title') }}</h1>

<!-- Com parâmetros -->
<p>{{ __('roles.users_count') }}: {{ $usersCount }}</p>

<!-- Em atributos -->
<input type="text" placeholder="{{ __('roles.search_placeholder') }}">

<!-- Em arrays -->
<x-breadcrumb :items="[
    ['label' => __('roles.title'), 'active' => true]
]" />
```

### No PHP/Livewire:
```php
// Mensagens flash
session()->flash('message', __('roles.created_successfully'));

// Em arrays/retornos
return [
    'message' => __('roles.updated_successfully'),
    'title' => __('roles.title')
];

// Com parâmetros
__('roles.validation.name_required', ['attribute' => 'nome']);
```

### Principais chaves disponíveis:

- `roles.title` - "Funções"
- `roles.create` - "Criar Função"
- `roles.edit` - "Editar Função"
- `roles.delete` - "Excluir Função"
- `roles.created_successfully` - "Função criada com sucesso!"
- `roles.search_placeholder` - "Buscar funções..."

### Configuração do idioma:

Para usar as traduções em português, adicione no `.env`:
```
APP_LOCALE=pt_BR
```

Ou configure diretamente no `config/app.php`:
```php
'locale' => 'pt_BR',
```

Ou altere programaticamente:
```php
App::setLocale('pt_BR');
```
