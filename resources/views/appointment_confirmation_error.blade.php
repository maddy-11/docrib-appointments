@extends($activeTemplate.'layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 d-flex align-items-center py-5">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <!-- Error Icon -->
                    <div class="mb-4">
                        <div class="error-icon mx-auto mb-3">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                        </div>
                    </div>

                    <!-- Main Heading -->
                    <h2 class="text-warning mb-3 fw-bold">
                        <i class="fas fa-calendar-times me-2"></i>
                        Appointment Not Found
                    </h2>

                    <!-- Error Message -->
                    <p class="lead text-muted mb-4">
                        {{ $error ?? 'We could not locate the appointment you are trying to confirm. This may happen if the appointment has already been processed or the link has expired.' }}
                    </p>

                    <!-- Help Information -->
                    <div class="alert alert-light border mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i>
                            What can you do?
                        </h6>
                        <ul class="mb-0 text-start">
                            <li>Check if you've already confirmed this appointment</li>
                            <li>Contact our support team if you believe this is an error</li>
                            <li>Book a new appointment if needed</li>
                            <li>Verify the confirmation link in your email</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-4">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg me-md-2">
                            <i class="fas fa-home me-2"></i>
                            Back to Home
                        </a>
                        <a href="mailto:support@example.com" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-envelope me-2"></i>
                            Contact Support
                        </a>
                    </div>

                    <!-- Contact Information -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-headset me-2"></i>
                            Need Immediate Help?
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="contact-item">
                                    <i class="fas fa-phone text-success me-2"></i>
                                    <strong>Phone:</strong>
                                    <br>
                                    <a href="tel:+1234567890" class="text-primary">(123) 456-7890</a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="contact-item">
                                    <i class="fas fa-envelope text-info me-2"></i>
                                    <strong>Email:</strong>
                                    <br>
                                    <a href="mailto:support@example.com" class="text-primary">support@example.com</a>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <small>
                                <i class="fas fa-clock me-1"></i>
                                Our support team is available Monday - Friday, 9:00 AM - 6:00 PM
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.contact-item {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
@endsection