<?php

return [
    // Mensajes genéricos
    'required'              => 'El campo {field} es requerido.',
    'isset'                 => 'El campo {field} debe tener un valor.',
    'valid_email'           => 'El campo {field} debe contener una dirección de correo electrónico válida.',
    'valid_emails'          => 'El campo {field} debe contener todas las direcciones de correo electrónico válidas.',
    'valid_url'             => 'El campo {field} debe contener una URL válida.',
    'valid_ip'              => 'El campo {field} debe contener una IP válida.',
    'min_length'            => 'El campo {field} debe tener al menos {param} caracteres.',
    'max_length'            => 'El campo {field} no puede exceder {param} caracteres.',
    'exact_length'          => 'El campo {field} debe tener exactamente {param} caracteres.',
    'alpha'                 => 'El campo {field} solo puede contener letras.',
    'alpha_numeric'         => 'El campo {field} solo puede contener letras y números.',
    'alpha_numeric_spaces'  => 'El campo {field} solo puede contener letras, números y espacios.',
    'alpha_dash'            => 'El campo {field} solo puede contener letras, números, guiones bajos y guiones.',
    'numeric'               => 'El campo {field} debe contener solo números.',
    'integer'               => 'El campo {field} debe contener un entero.',
    'decimal'               => 'El campo {field} debe contener un número decimal.',
    'is_natural'            => 'El campo {field} debe contener solo números positivos.',
    'is_natural_no_zero'    => 'El campo {field} debe contener un número mayor a cero.',
    'valid_base64'          => 'El campo {field} debe contener una cadena Base64 válida.',
    'matches'               => 'El campo {field} no coincide con el campo {param}.',
    'is_unique'             => 'El campo {field} ya existe en el sistema.',
    'is_not_unique'         => 'El campo {field} debe ser único en el sistema.',
    'in_list'               => 'El campo {field} debe ser uno de: {param}',
    'valid_date'            => 'El campo {field} debe ser una fecha válida.',
    'valid_username'        => 'El campo {field} debe contener solo letras, números, guiones bajos y tener al menos 3 caracteres.',

    // Mensajes personalizados para el sistema
    'cedula_required'       => 'La cédula de identidad es requerida.',
    'cedula_is_unique'      => 'Ya existe una persona registrada con esta cédula de identidad.',
    'primer_nombre_required'=> 'El primer nombre es requerido.',
    'primer_apellido_required' => 'El primer apellido es requerido.',
    'username_required'     => 'El nombre de usuario es requerido.',
    'password_required'     => 'La contraseña es requerida.',
];
