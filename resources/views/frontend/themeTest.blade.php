<x-layout_main>
 <div class="mainContentDiv">
     <div class="p-10">
          <button class="btn">Button</button>
          <button class="btn btn-neutral">Neutral</button>
          <button class="btn btn-primary">Primary</button>
          <button class="btn btn-secondary">Secondary</button>
          <button class="btn btn-accent">Accent</button>
          <button class="btn btn-ghost">Ghost</button>
          <button class="btn btn-link">Link</button>
     </div>
  
     <div class="p-10">
          <button class="btn btn-info">Info-content</button>
          <button class="btn btn-success">Success-content</button>
          <button class="btn btn-warning">Warning-content</button>
          <button class="btn btn-error">Error-content</button>
      </div>
  
  
       <div class="hidden size-20 gap-0"></div>
  
      @php
          $bgTest = 
            array(
              "gap"=>"0", 
              "size"=>"20", 
              "bg-c"=>array(
                  "bg-base-100",
                  "bg-neutral",
                  "bg-primary",
                  "bg-secondary",
              )
            )
        
        @endphp
  <div class="flex flex-wrap w-full">
       @foreach ($bgTest["bg-c"] as $bgc)
       <div class="flex flex-row p-20 {{ $bgc }}">
            <div class="flex flex-col gap-{{ $bgTest["gap"] }} ">
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">scale</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">100</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">200</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">300</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">400</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">500</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">600</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">700</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">800</p>
                 <p class="place-content-center text-center size-{{ $bgTest["size"] }}">900</p>
            </div>
       
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }}">
                 <p   class="bg-base     size-{{ $bgTest["size"] }} place-content-center text-center">base</p>
                 <div class="bg-base-100 size-{{ $bgTest["size"] }}"> <p>bg-colour</p></div>
                 <div class="bg-base-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-base-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-base-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-base-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-base-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-base-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-base-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-base-900 size-{{ $bgTest["size"] }}"></div>
            </div>
       
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }} ">
                 <p   class="bg-primary     size-{{ $bgTest["size"] }} place-content-center text-center">primary</p>
                 <div class="bg-primary-100 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-primary-900 size-{{ $bgTest["size"] }}"></div>
            </div>
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }} ">
                 <p   class="bg-secondary     size-{{ $bgTest["size"] }} place-content-center text-center">secondary</p>
                 <div class="bg-secondary-100 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-secondary-900 size-{{ $bgTest["size"] }}"></div>
            </div>
       
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }} ">
                 <p   class="bg-neutral     size-{{ $bgTest["size"] }} place-content-center text-center">neutral</p>
                 <div class="bg-neutral-100 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-neutral-900 size-{{ $bgTest["size"] }}"></div>
            </div>
       
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }} ">
                 <p   class="bg-info     size-{{ $bgTest["size"] }} place-content-center text-center">info</p>
                 <div class="bg-info-100 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-info-900 size-{{ $bgTest["size"] }}"></div>
            </div>
       
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }} ">
                 <p   class="bg-success     size-{{ $bgTest["size"] }} place-content-center text-center">success</p>
                 <div class="bg-success-100 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-success-900 size-{{ $bgTest["size"] }}"></div>
            </div>
       
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }} ">
                 <p   class="bg-warning     size-{{ $bgTest["size"] }} place-content-center text-center ">warning</p>
                 <div class="bg-warning-100 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-warning-900 size-{{ $bgTest["size"] }}"></div>
            </div>
       
            <div class="flex flex-col pl-6 pr-6 gap-{{ $bgTest["gap"] }} ">
                 <p   class="bg-error     size-{{ $bgTest["size"] }} place-content-center text-center">error</p>
                 <div class="bg-error-100 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-200 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-300 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-400 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-500 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-600 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-700 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-800 size-{{ $bgTest["size"] }}"></div>
                 <div class="bg-error-900 size-{{ $bgTest["size"] }}"></div>
            </div>
       </div>
       @endforeach
  </div>
 </div>
  


    
</x-layout_main>