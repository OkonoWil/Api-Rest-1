<form action="api/user/register" method="post">
    @csrf
    <input type="text" name="name">
    @error('name')
    <span>{{$message}}</span>
    @enderror
    <br>
    <input type="email" name="email" id="email">
    @error('email')
    <p>{{$message}}</p>
    @enderror
    <br>
    <input type="password" name="password">
    <br>
    <input type="submit" value="Send">
</form>