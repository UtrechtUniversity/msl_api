
<div class="w-full bg-base-200 text-base-content flex justify-around max-h-80">

  <footer class="footer 
  p-10 
  w-full max-w-screen-xl
  flex flex-row 
  gap-20
  place-items-center">

  
      <aside class="flex flex-row place-items-start gap-6">
        <img 
          src={{ asset( 'images/logos/MSL.png')}} 
          alt="MSL-logo"
          class="w-48">
          <div class="flex flex-col place-items-center">
            <h6 class="footer-title">A Thematic Core Service of</h6>
            <a class="link link-hover "
            href="https://www.epos-eu.org/">
              
              <img 
              src={{ asset( 'images/logos/eposLarge.jpg')}} 
              alt="MSL-logo"
              class="w-48">
            </a>
          </div>
      </aside>
  
      <nav class="
      {{-- grid grid-flow-col grid-rows-2  --}}
      flex flex-col flex-wrap max-h-64
      gap-8
      max-w-screen-sm">
        <nav class="link-list-item">
          <h6 class="footer-title">Data publications</h6>
          <a class="link link-hover"
          href="{{ route("data-access") }}"
          >Data Access</a>
        </nav>

        <nav class="link-list-item">
          <h6 class="footer-title">Laboratories and equipment</h6>
          <a class="link link-hover"
          href="{{ route("labs-map") }}">Laboratories</a>
          
          <a class="link link-hover"
          href="{{ route("equipment-map") }}">Equipment</a>
        </nav>
        
        <nav class="link-list-item">
          <h6 class="footer-title">Data sources</h6>
          <a class="link link-hover"
          href="{{ route("data-repositories") }}">Data repositories</a>
        </nav>

        <nav class="link-list-item">
          <h6 class="footer-title">How to contribute</h6>
          <a class="link link-hover"
          href="{{ route("contribute-researcher") }}">As a researcher</a>
          <a class="link link-hover"
          href="{{ route("contribute-repository") }}">As a repository</a>
          <a class="link link-hover"
          href="{{ route("contribute-laboratory") }}">As a laboratory</a>
          <a class="link link-hover"
          href="{{ route("laboratory-intake") }}">
            <x-ri-corner-down-right-fill class="size-4 fill-primary-700 inline"/>
            Laboratory intake form
          </a>
        </nav>

        <nav class="link-list-item">
          <h6 class="footer-title">Vocabularies</h6>
          <a class="link link-hover"
          href="{{ route("keyword-selector") }}">Keyword selector</a>
        </nav>
  
        <nav class="link-list-item">
          <h6 class="footer-title">About us</h6>
          <a class="link link-hover"
          href="{{ route("about") }}">About</a>
          <a class="link link-hover"
          href="{{ route("contact-us") }}">Contact</a>
        </nav>

      </nav>
      

  
  
  </footer>

</div>

