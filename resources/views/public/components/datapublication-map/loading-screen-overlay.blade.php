 <div id="loading-screen-overlay" {{-- TODO fix placing --}}
     class="absolute inset-0
      {{-- We want this element to be above map-controls (i,e. z-index=1000) and menu-on-map (i.e. z-index=1100) --}}
      z-1150 sub-content-wide place-content-center h-full w-full
      bg-primary-200/75 flex flex-col justify-center items-center pointer-events-auto"
     hidden>
     <div class="h-1/3 w-1/2 bg-info-300 p-10 ">

         <p class='text-xl text-center'>Apply spatial or keyword filter to
             get results.
         </p>
         <br />
         <p class='text-xl text-center'>Click on map to start navigation.</p>
     </div>
 </div>
