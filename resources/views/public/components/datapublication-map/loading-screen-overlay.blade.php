 <div id="loading-screen-overlay"
     class="
     absolute 
      {{-- We want this element to be above map-controls (i,e. z-index=1000) and menu-on-map (i.e. z-index=1100) --}}
      z-1150 
      sub-content-wide 
      h-full 
      w-full
      bg-primary-200/40
      flex 
      flex-col 
      items-center  
      pointer-events-auto 
      bg-clip-content 
      pb-10
      pt-10
      "
     hidden>
     <div class="pt-20 pl-70">
         <div class="border-gray-300 h-20 w-20 animate-spin rounded-full border-8 border-t-blue-600"></div>
     </div>
 </div>
