@extends($activeTemplate.'layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 d-flex align-items-center py-5">
        <div class="col">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="success-icon mx-auto mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                    </div>

                    <!-- Main Heading -->
                    <h2 class="text-success mb-3 fw-bold">
                        <i class="fas fa-calendar-check me-2"></i>
                        Appointment Confirmed!
                    </h2>

                    <!-- Success Message -->
                    <p class="lead text-muted mb-4">
                        Great news! Your appointment has been successfully confirmed. We look forward to seeing you.
                    </p>

                    <!-- Appointment Details Card -->
                    <div class="appointment-details bg-light rounded p-4 mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Appointment Details
                        </h5>
                        
                        <div class="row text-start">
                            <div class="col-sm-6 mb-3">
                                <div class="detail-item">
                                    <i class="fas fa-hashtag text-primary me-2"></i>
                                    <strong>Appointment ID:</strong>
                                    <span class="text-muted">#{{ $appointment->id }}</span>
                                </div>
                            </div>
                            
                            @if($appointment->doctor)
                            <div class="col-sm-6 mb-3">
                                <div class="detail-item">
                                    <i class="fas fa-user-md text-primary me-2"></i>
                                    <strong>Doctor:</strong>
                                    <span class="text-muted">Dr. {{ $appointment->doctor->name }}</span>
                                </div>
                            </div>
                            @endif

                            @if($appointment->appointment_date)
                            <div class="col-sm-6 mb-3">
                                <div class="detail-item">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong>Date:</strong>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</span>
                                </div>
                            </div>
                            @endif

                            @if($appointment->serial_or_slot)
                            <div class="col-sm-6 mb-3">
                                <div class="detail-item">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <strong>Time:</strong>
                                    <span class="text-muted">{{ $appointment->serial_or_slot }}</span>
                                </div>
                            </div>
                            @endif

                            @if($appointment->name)
                            <div class="col-sm-6 mb-3">
                                <div class="detail-item">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <strong>Patient:</strong>
                                    <span class="text-muted">{{ $appointment->name }}</span>
                                </div>
                            </div>
                            @endif

                            @if($appointment->email)
                            <div class="col-sm-6 mb-3">
                                <div class="detail-item">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <strong>Email:</strong>
                                    <span class="text-muted">{{ $appointment->email }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="alert alert-info border-0 mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Important Reminders
                        </h6>
                        <ul class="mb-0 text-start">
                            <li>Please arrive 15 minutes before your scheduled time</li>
                            <li>Bring a valid ID and your insurance card</li>
                            <li>If you need to reschedule, please contact us at least 24 hours in advance</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <button onclick="window.print()" class="btn btn-outline-primary btn-lg me-md-2">
                            <i class="fas fa-print me-2"></i>
                            Print Confirmation
                        </button>
                        <a href="{{ url('/') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-home me-2"></i>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

@media print {
    .btn, .border-top {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection