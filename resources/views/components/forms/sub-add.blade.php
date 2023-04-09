<x-layout>
    <form id="add-sub-form">
        <h2>Add new subscriber</h2>
         <div class="form-group">
            <label for="subscriber-email">Email</label>
            <input type="email" class="form-control" id="subscriber-email" name="subscriber-email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="subscriber-name">Name</label>
            <input type="text" class="form-control" id="subscriber-name" name="subscriber-name" placeholder="Enter name" required>
        </div>
        <div class="form-group">
            <label for="subscriber-country">Country</label>
            <input type="text" class="form-control" id="subscriber-country" name="subscriber-country" placeholder="Enter country" required>
        </div>
        <button type="submit" class="btn btn-success" id="sub-add-save">Add subscriber</button>
        <a href="/" class="btn btn-warning">Cancel</a>
        </form>
    </form>
</x-layout>