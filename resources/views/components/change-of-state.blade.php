@switch($status)
    @case('pending')
        <i class="bi bi-hourglass-split btn-default full-center">{{ __('button.search.pending') }}</i>
    @break

    @case('assisted')
        <i class="bi bi-check-circle-fill btn-success full-center">{{ __('button.search.assisted') }}</i>
    @break

    @case('not_attendance')
        <i class="bi bi-x-circle-fill btn-danger full-center">{{ __('button.search.not_attendance') }}</i>
    @break

    @case('cancelled_by_user')
        <i class="bi bi-x-circle-fill btn-danger full-center">{{ __('medical.status.canceled') }}</i>
    @break

    @case('cancelled_by_admin')
        <i class="bi bi-x-circle-fill btn-danger full-center">{{ __('medical.status.cancelled_by_admin') }}</i>
    @break

    @case('deleted_by_admin')
        <i class="bi bi-x-circle-fill btn-danger full-center">{{ __('medical.status.deleted_by_admin') }}</i>
    @break

    @default
        {{ __('button.search.inactive_appointment full-center') }}
    @break
@endswitch
