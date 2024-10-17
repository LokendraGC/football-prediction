<form action="update-avatar" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="avatar" placeholder="choose file">
    <button>submit</button>
</form>