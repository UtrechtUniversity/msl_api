   <div class="w-80 flex flex-col place-items-center justify-self-center">

       <h2 class="py-4">Filters</h2>

       <div class="pb-10">

           <div id="jstree-interpreted" class="text-wrap pt-4"></div>
           <div id="jstree-original" class="text-wrap pt-4" style="display: none;"></div>
           <script>
               var dataInterpreted = @php echo File::get(base_path($filterDataPath)) @endphp;
               var dataOriginal = @php echo File::get(base_path('public/original.json')) @endphp;
               var facets = @php echo json_encode(['lala']); @endphp;
               var activeFilters = @php echo json_encode(['$activeFilters']); @endphp;
               var activeNodes = [];
           </script>
           @push('vite')
               @vite(['resources/ts/jstree.ts', 'resources/ts/dataPublication/filters-menu.ts'])
           @endpush

       </div>

   </div>
