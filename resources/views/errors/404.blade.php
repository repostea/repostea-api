@extends('layouts.app')

@section('title', 'Página no encontrada')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="alert alert-info mb-4 p-4">
                            <h2 class="h3 mb-3"><i class="fas fa-info-circle me-2"></i>Repostea está en fase de demostración</h2>
                            <p class="fs-5 mb-0">
                                Estamos trabajando en la implementación completa de todas las funcionalidades. Algunas secciones aún están en desarrollo y no están disponibles en este momento. Te agradecemos tu comprensión y paciencia mientras seguimos mejorando la plataforma. ¡Vuelve pronto para ver nuestras actualizaciones!
                            </p>
                        </div>

                        <h3 class="text-muted mb-3">Error 404</h3>
                        <p class="lead mb-4">Lo sentimos, pero la página que estás buscando no existe o no está disponible actualmente.</p>

                        <a href="/" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Volver a la página principal
                        </a>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted">
                    <p>Si crees que esto es un error, por favor contacta con nosotros.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
