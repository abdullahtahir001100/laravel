<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <form action="/simplecontroller_make" method="POST">
    @csrf

      <input type="text" name="name" placeholder="Enter your name">
      <button type="submit">Submit</button>
   </form>
   <div class="summary">
        <h2>Simple List</h2>
        <ul>
             @foreach($simples as $simple)
                <li>{{ $simple->name }}</li>
                <li><a href="/simplecontroller_edit/{{ $simple->id }}">Edit</a></li>
                <li><a href="/simplecontroller_destroy/{{ $simple->id }}">Delete</a></li>
             @endforeach
        </ul>
   </div>
</body>
</html>