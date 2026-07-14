   <div class="w-80 flex flex-col place-items-center justify-self-center">

       <h2 class="py-4">Filters</h2>

       <div class="pb-10">

           <div id="jstree-interpreted" class="text-wrap pt-4"></div>
           <div id="jstree-original" class="text-wrap pt-4" style="display: none;"></div>
           @push('vite')
               @vite(['resources/ts/dataPublication/filters-menu.ts'])
           @endpush

       </div>

   </div>
