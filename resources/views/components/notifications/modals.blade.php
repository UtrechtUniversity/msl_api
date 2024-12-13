
@php
    $notificationType = 'modals';
    $componentPath    = 'components.notifications.components.modal';
@endphp

@if(session()->has($notificationType) && is_array(session($notificationType)))

  @php
  $t = session($notificationType)['type'];
  $m = session($notificationType)['message'];
  @endphp


  <dialog 
  id='modalPopUp' 
  class="modal">
    <div class="modal-box
      p-10

      @if ($t == 'success')
        bg-success-200 text-success-900
      @endif
    
      ">

      <h3 class="pb-6">{{ ucfirst($t) }}!</h3>

      <p class="text-center">
        {{ $m }}
      </p>

      <div class="modal-action flex place-content-center">
        <form method="dialog ">

          <!-- if there is a button in form, it will close the modal -->
          <button class="btn 

              @if ($t == 'success')
                btn-success
              @endif

            ">
            Close
          </button>

        </form>
      </div>
    </div>
  </dialog>

  <script>
      modalPopUp.showModal()
  </script>
 
@endif
