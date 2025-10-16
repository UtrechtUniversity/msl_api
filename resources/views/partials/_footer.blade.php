




<footer class="footer sm:footer-horizontal bg-base-200 text-base-content p-10 justify-center gap-10">



  @php
    $allLinks = [
      'Services' => [
        'Data Access' => route('data-access'),
        'Labs' =>  route('labs-map'),
        'Data Repositories' => route('data-repositories'),
        'Vocabularies' => 'divider',
        'Keyword Selector' => route('keyword-selector')
      ],
      'How to contribute' => [
        'As a Researcher' => route('contribute-researcher'),
        'As a Repository' => route('contribute-repository'),
        'As a Laboratory' => route('contribute-laboratory'),
        'Surveys' => 'divider',
        'Data Tooling - Survey' => route('contribute-select-scenario'),
        'Forms' => 'divider',
        'Laboratory intake form' => route("laboratory-intake")
      ],
      'About' => [
        'About MSL' => route('about'),
        'EPOS Portal' => "https://www.epos-eu.org/dataportal"
      ],

    ]
  @endphp

  @foreach ( $allLinks as $name => $linkOrArray)
    <nav>
      @if (is_array($linkOrArray))
        <h6 class="footer-title">{{ $name }}</h6>
          @foreach ($linkOrArray as $name => $link)

            @if ($link == 'divider')
              <div class="border border-primary-100 w-30 "></div>
              <h6 class="footer-title text-base mb-0">{{ $name }}</h6>
            @else
                <a class="no-underline hover-interactive" href="{{ $link }}">{{ $name }}</a>
            @endif

          @endforeach
      @else
        <h6 class="footer-title">{{ $name }}</h6>
        <a class="no-underline hover-interactive" href="{{ $linkOrArray }}">{{ $name }}</a>
      @endif
    </nav>
  @endforeach    

  <aside class="justify-center content-center">
    <aside class="w-48">
      <img 
      src={{ asset( 'images/logos/MSL.png')}} 
      alt="MSL-logo"
      class="h-fit">
    </aside>
    <aside class="w-48 hover-interactive" >
      <a class=" "
      href="https://www.epos-eu.org/">
        
        <img 
        src={{ asset( 'images/logos/EPOScolour.png')}} 
        alt="MSL-logo"
        class="w-48">
      </a>
      <p class="text-center">
        MSL is a Thematic Core Service of EPOS 
      </p>
    </aside>

  </aside>

</footer>
