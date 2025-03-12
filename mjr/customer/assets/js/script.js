document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('otpForm');

    form.addEventListener('submit', function (e) {
        const otpInput = document.getElementById('otp');
        if (otpInput.value.length !== 6) {
            alert('Please enter a valid 6-digit OTP.');
            e.preventDefault(); // Prevent form submission
        }
    });
});