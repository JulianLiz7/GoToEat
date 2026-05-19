@include('errors.layout', [
    'code'    => '403',
    'icon'    => 'lock',
    'title'   => 'Acceso denegado',
    'message' => 'No tienes permiso para acceder a esta página. Si crees que esto es un error, contacta al administrador.',
])
