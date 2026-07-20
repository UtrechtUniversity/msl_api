   <div class=" flex flex-col place-items-center justify-self-center">

       <h2 class="py-4">Filters</h2>

       <div class="flex flex-col gap-3 pt-6">
           <div class="search-bar-container form-field-text p-0 m-0 ">
               <div class="search-bar-container-icon">
                   <x-ri-search-line class="search-icon" />
               </div>
               <input class="search-bar" type="text" id="search-filters" placeholder="Search Filters..." />
           </div>
           <div class="bg-primary-100 w-full">
               <div class="flex flex-col items-center w-full">
                   <div class="flex flex-col w-full">
                       <div class="flex-col space-y-2 place-content-center h-full w-full">
                           @foreach (['Hide empty terms'] as $key => $option)
                               <div class="form-control w-full">
                                   <label
                                       class="
                                                w-full
                                                label p-2 
                                                text-secondary-900
                                                hover-interactive">
                                       <span class="pr-4 text-sm w-full" value={{ $key }}
                                           name={{ 'EmptyTerms' . '[]' }}>
                                           {{ $option }}
                                       </span>

                                       <input type="checkbox" value={{ $key }} name={{ 'EmptyTerms' . '[]' }}
                                           id='hide_empty_terms'
                                           class="checkbox checkbox-secondary checkbox-md rounded-sm border"
                                           @if (is_array(old('EmptyTerms')) && in_array($key, old('EmptyTerms'))) checked="checked" @endif />

                                   </label>
                               </div>
                           @endforeach
                       </div>
                   </div>
               </div>
           </div>

           <div class="px-2 py-3 w-full">
               <div class="w-full flex place-content-evenly">
                   <a href="#" id="expand_all" title="expand all nodes">
                       <button class="btn btn-sm w-20">
                           expand all
                       </button>
                   </a>
                   <a href="#" id="close_all" title="close all nodes">
                       <button class="btn btn-sm w-20">
                           close all
                       </button>
                   </a>
               </div>
           </div>
       </div>

       <div class="pb-10">

           <div id="jstree-interpreted" class="text-wrap pt-4"></div>
           <div id="jstree-original" class="text-wrap pt-4" style="display: none;"></div>
           @push('vite')
               @vite(['resources/ts/dataPublication/filters-menu.ts'])
           @endpush

       </div>

   </div>
