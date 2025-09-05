<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests;">

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="{{ asset('assets/images/ra_list_logo.svg') }}" sizes="any">
<link rel="icon" href="{{ asset('assets/images/ra_list_logo.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('assets/images/ra_list_logo.svg') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<style>
  [x-cloak] {
    display: none !important;
  }

  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  @media (prefers-reduced-motion: reduce) {
    * {
      transition-duration: 0.01ms !important;
      animation-duration: 0.01ms !important;
    }
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@fluxAppearance