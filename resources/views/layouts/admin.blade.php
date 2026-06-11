<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Boxicons -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>
<body>

<div id="overlay"></div>

@include('layouts.partials.sidebar')

<div class="content">

    @include('layouts.partials.navbar')

    <div class="mt-4">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const toggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar-wrapper');
const overlay = document.getElementById('overlay');

toggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});

// CLOCK
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const clockElement = document.getElementById('navClock');
    if (clockElement) {
        clockElement.textContent = hours + ':' + minutes;
    }
}
setInterval(updateClock, 1000);
updateClock();
</script>

@yield('scripts')

</body>
</html>
