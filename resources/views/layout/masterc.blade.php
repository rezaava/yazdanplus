<!DOCTYPE html>
<html lang="en">

<head>
  @include('layout/meta')
  <!-- The above tags *must* come first in the head, any other head content must come *after* these tags -->
  <!-- Title -->
  <title>
    یزدان
    @yield('title')
  </title>
  <!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>-->

  <!--<link rel="preconnect" href="https://fonts.googleapis.com/">-->
  <!--<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>-->
  <!--<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&amp;display=swap"-->
  <!--  rel="stylesheet">-->
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
  <!-- Apple Touch Icon -->
  <link rel="apple-touch-icon" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
  <link rel="apple-touch-icon" sizes="352x352" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
  <link rel="apple-touch-icon" sizes="367x367" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
  <link rel="apple-touch-icon" sizes="480x480" href="{{ asset('/img/icons/anetoLogoRed.png') }}">

  <!-- CSS Libraries -->
    @include('layout/csslibraries')
  <!-- Stylesheet -->
  <link rel="stylesheet" href="{{ asset('/style.css') }}">


  <!-- Web App Manifest -->
  <link rel="manifest" href="{{ asset('/manifest.json') }}">

@yield('style')

</head>

<body>
  <!-- Preloader-->
  <!--@include('layout/preloader')-->
  <!-- Header Area -->

  @yield('content')



  <!-- Internet Connection Status -->
{{--  @include('layout/connection')--}}

  <!-- Footer Nav-->
  <!-- All JavaScript Files-->
  @include('layout.script')
</body>

</html>
