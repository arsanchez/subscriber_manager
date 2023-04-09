<x-layout>
@if ($errors->any())
<h4 class="text-danger">Error {{$errors->first()}}</h4>
@endif
<div class="row">
    <div class="col-12">
      <a href="/subscribers/create" class="btn btn-success">Add new subscriber</a>
    </div>
    @if ($settings == true)
        <x-table>
        </x-table>
    @else 
    <x-forms.api>
    </x-forms.api>
    @endif
  </div>
</x-layout>

