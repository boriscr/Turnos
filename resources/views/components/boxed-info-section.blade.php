@if (isset($reminder) || isset($all))
    <section class="boxed-info-section full-center mt-3">
        <div class="alert-card">
            <div class="reminder-box full-center">
                <i class="bi bi-bell-fill"></i>
                <div class="text-start">
                    <p>
                        {{ config('app.patient_message') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endif
@if (isset($info) || isset($all))
    <section class="boxed-info-section full-center  {{ isset($info) ? 'p-4' : '' }}">
        <div class="alert-card">
            <div class="reminder-box full-center">
                <i class="bi bi-cone-striped"></i>
                <div class="text-start">
                    <h6 class="fw-bold mb-3">Normas importantes para reservas de turnos</h6>

                    <ul class="list-unstyled text-muted small">
                        <li class="mb-3">
                            <strong>Cancelación:</strong> Debe realizarse con al menos
                            <b>{{ config('appointments.cancellation_hours') }} horas</b> de antelación.
                        </li>
                        <li class="mb-3">
                            <strong>Inasistencias:</strong> Se permiten máximo
                            <b>{{ config('appointments.maximum_faults') }}</b>. Al superar este número,
                            se bloqueará su
                            cuenta y no podrá reservar nuevos turnos.
                        </li>
                        <li>
                            <strong>Turnos simultáneos:</strong> Solo puede tener
                            <b>{{ config('appointments.daily_limit') }}
                                turno{{ config('appointments.daily_limit') > 1 ? 's' : '' }}</b>
                            pendiente{{ config('appointments.daily_limit') > 1 ? 's' : '' }}. Deberá
                            asistir antes de
                            poder
                            reservar más.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endif
