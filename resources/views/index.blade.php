<x-layout>
<div class="row">
    @if ($settings == true)
        <div class="col-sm">
        One of three columns
        </div>
        <div class="col-sm">
        One of three columns
        </div>
        <div class="col-sm">
        One of three columns
        </div>
    @else 
    <x-forms.api>
    </x-forms.api>
    @endif
  </div>
</x-layout>

