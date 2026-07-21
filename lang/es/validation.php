<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email'    => 'El campo :attribute debe ser una dirección de correo válida.',
    'unique'   => 'El campo :attribute ya está en uso.',
    'current_password' =>'La contraseña es incorrecta.',
    'validation.confirmed' => 'las contraseñas no coinciden',
    
    'min'      => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'max'      => [
        'string' => 'El campo :attribute no debe superar :max caracteres.',
    ],

    'attributes' => [
        'username'        => 'usuario',
        'nombre_completo' => 'nombre completo',
        'email'           => 'correo electrónico',
        'telefono'        => 'teléfono',
        'direccion'       => 'dirección',
        'password_radius' => 'contraseña',
        'estado'          => 'estado',
        'observaciones'   => 'observaciones',
        'password_confirmation' => 'confirmar contraseña',
        'password' => 'contraseña',
        'current_password' => 'contraseña actual',
        
    ],
];
