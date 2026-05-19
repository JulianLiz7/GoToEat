@include('errors.layout', [
    'code'    => '429',
    'icon'    => 'hourglass_top',
    'title'   => 'Demasiadas solicitudes',
    'message' => 'Has realizado demasiadas solicitudes en poco tiempo. Por favor espera unos momentos antes de intentarlo de nuevo.',
])
