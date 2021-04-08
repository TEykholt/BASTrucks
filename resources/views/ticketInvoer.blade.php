<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Dashboard</title>
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <form method="POST" action="/">
            <label for="">subject</label><input type="text" name="subject">
            <label for="">message</label><input type="text" name="message">
            <label for="">person_id</label><input type="text" name="person_id">
            <label for="">department_id</label><input type="text" name="department_id">
            <input type="submit">
        </form>
    </div>

    </body>
</html>
