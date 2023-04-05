<x-layout>
<div class="row">
    @if ($settings == true)
        <x-table>
        </x-table>
    @else 
    <x-forms.api>
    </x-forms.api>
    @endif
  </div>
</x-layout>

