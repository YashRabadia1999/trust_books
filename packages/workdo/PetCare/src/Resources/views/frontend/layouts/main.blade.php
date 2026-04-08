<!DOCTYPE html>
<html lang="en">
@include('pet-care::frontend.layouts.head')
<body class="bg-gray-50 index">
    @include('pet-care::frontend.layouts.header')
    <main>
        @yield('content')
    </main>
</body>
@include('pet-care::frontend.layouts.footer')
</html>
