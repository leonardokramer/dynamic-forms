<?php

return [
    'labels' => [
        'title' => 'Título',
        'description' => 'Descrição',
        'active' => 'Ativo',
        'status' => 'Status',
        'created_at' => 'Criado em',
        'questions' => 'Perguntas',
        'answer' => 'Resposta',
        'form' => 'Formulário',
        'submitted_at' => 'Enviado em',
        'submitted_by' => 'Enviado por',
        'total_responses' => 'Total de Respostas',
        'submission_info' => 'Informações da Submissão',
        'user' => 'Usuário',
        'user_email' => 'Email',
    ],
    
    'placeholders' => [
        'form_title' => 'Digite o título do formulário',
        'form_description' => 'Descreva o formulário (opcional)',
        'text_answer' => 'Digite sua resposta...',
        'integer_answer' => 'Digite um número inteiro...',
        'decimal_answer' => 'Digite um número decimal...',
        'multiple_choice_answer' => 'Selecione uma opção...',
        'no_answer' => 'Nenhuma resposta fornecida',
        'no_alternatives' => 'Nenhuma alternativa disponível',
    ],
    
    'buttons' => [
        'back' => 'Voltar',
        'submit_answers' => 'Enviar Respostas',
        'create_answer' => 'Responder',
        'answer_form' => 'Responder Formulário',
        'view_response' => 'Ver Resposta',
    ],
    
    'messages' => [
        'form_not_found' => 'Formulário não encontrado',
        'form_not_active' => 'Formulário não está ativo',
        'form_submitted_successfully' => 'Formulário enviado com sucesso!',
        'form_submission_error' => 'Erro ao enviar formulário',
        'submission_not_found' => 'Resposta não encontrada',
        'unauthorized_access' => 'Acesso não autorizado',
        'no_submissions' => 'Nenhuma resposta enviada',
        'no_submissions_description' => 'Você ainda não respondeu nenhum formulário.',
    ],
    
    'filters' => [
        'all' => 'Todos',
        'active_only' => 'Apenas ativos',
        'inactive_only' => 'Apenas inativos',
    ],
    
    'help' => [
        'form_active' => 'Formulário visível para os usuários',
    ],
    
    'pages' => [
        'list_active_forms' => [
            'navigation_label' => 'Formulários Disponíveis',
            'title' => 'Formulários Disponíveis',
            'slug' => 'formularios-disponiveis',
        ],
        'answer_form' => [
            'title' => 'Responder Formulário',
            'slug' => 'responder-formulario',
        ],
        'my_submissions' => [
            'navigation_label' => 'Minhas Respostas',
            'title' => 'Minhas Respostas',
            'slug' => 'minhas-respostas',
        ],
        'view_submission' => [
            'title' => 'Visualizar Resposta',
            'slug' => 'visualizar-resposta',
        ],
        'admin_submissions' => [
            'navigation_label' => 'Respostas dos Formulários',
            'title' => 'Respostas dos Formulários',
            'slug' => 'respostas-dos-formularios',
        ],
        'admin_view_submission' => [
            'title' => 'Visualizar Resposta (Admin)',
            'slug' => 'admin-view-submission',
        ],
    ],
    
    'resources' => [
        'form' => [
            'navigation_label' => 'Formulários',
            'model_label' => 'Formulário',
            'plural_model_label' => 'Formulários',
        ],
    ],
];
