<x-layout>
    <form id="edit-sub-form">
        <h2>Edit subscriber</h2>
        <input type="hidden" id="sub_id" value="{{ $subscriber->id }}">
        <div class="form-group">
            <label for="subscriber-name">Name</label>
            <input type="text" class="form-control" id="subscriber-name" name="subscriber-name" value="{{ $subscriber->fields->name }}" required>
        </div>
        <div class="form-group">
            <label for="subscriber-country">Country</label>
            <input type="text" class="form-control" id="subscriber-country" name="subscriber-country" value="{{ $subscriber->fields->country }}" required>
        </div>
        <button type="submit" class="btn btn-primary" id="sub-edit-save">Update</button>
        <a href="/" class="btn btn-warning">Cancel</a>
        </form>
    </form>
</x-layout>