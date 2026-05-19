@include('errors.layout', [
    'code'    => '500',
    'icon'    => 'error',
    'title'   => 'Error del servidor',
    'message' => 'Algo salió mal en nuestro servidor. Nuestro equipo ya fue notificado. Por favor intenta de nuevo en unos minutos.',
])
