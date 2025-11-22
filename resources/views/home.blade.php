<x-app-layout>
    <section class="hero-section">
        <!-- Carrusel -->
        <div class="hero-carousel">
            <div class="carousel-slide active"
                style="background-image: url('https://media.prensa.jujuy.gob.ar/p/b08c8a2291afdd7d4887fa7cf6d1b6f8/adjuntos/301/imagenes/000/339/0000339221/1200x675/smart/hospital-abra-pampa.jpg')">
            </div>
            <div class="carousel-slide"
                style="background-image: url('https://media.prensa.jujuy.gob.ar/adjuntos/301/migration/wp-content/uploads/sites/37/2019/04/00026.03_14_32_10.Imagen-fija001-1024x576.jpg')">
            </div>
            <div class="carousel-slide"
                style="background-image: url('https://wallpapers.com/images/hd/old-female-physician-0a6ue06f739oy19p.jpg')">
            </div>
            <div class="carousel-slide"
                style="background-image: url('https://www.unitecoprofesional.es/blog/wp-content/uploads/2012/05/requerimiento-historia-clinica.png')">
            </div>
            <div class="carousel-slide"
                style="background-image: url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2940&auto=format&fit=crop')">
            </div>
            <div class="carousel-slide"
                style="background-image: url('https://media.jujuyalmomento.com/p/f29efc4217737a3c6be9ebfdc268a8d5/adjuntos/260/imagenes/002/153/0002153671/1200x0/smart/nuestra-senora-del-rosariopng.png')">
            </div>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="box-content">
                <x-form.titles :value="config('app.institution_name')" size="show" />
                <p>
                    <strong>{{ config('app.welcome_message', 'Turnos médicos online en segundos.<br>Sin filas, sin demoras.') }}
                    </strong>
                </p>

                @auth
                    <a href="{{ route('reservations.create') }}" class="btn-primary mt-10">
                        Solicitar Turno Ahora
                    </a>
                </div>
            @else
                <p style="font-size: 1.3rem; margin-bottom: 2.5rem;">
                    Comienza en menos de 3 minutos
                </p>
                <div>
                    <a href="{{ route('login') }}" class="btn-secondary">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-secondary btn-secondary-color">Registrarse</a>
                </div>
            @endauth
        </div>
    </section>
    <section class="specialties-section">
        <h2 class="section-title">Especialidades disponibles</h2>
        <div class="specialties-carousel">
            <div class="carousel-track" id="specialtiesTrack">
                @forelse ($specialties as $item)
                    <div class="specialty-card">
                        <div class="card-accent"></div>
                        <h3>{{ $item->name }}</h3>
                        <p>{{ $item->description }}</p>
                    </div>
                @empty
                    <p class="empty-message">No hay especialidades disponibles en este momento.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-app-layout>
