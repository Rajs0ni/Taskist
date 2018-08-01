@extends('todo.app')

@section('content')

<span id="mainHeading">Todo App</span>
<div class="container helpContainer">
    <span>Help Desk</span>
    <br><br>
    <h4>&#x25cf; <b>Introduction</b></h4>
    <hr>
    <p><big><strong>T</strong></big>odo App, a small web application built in Laravel, HTML, CSS  &amp; Jquery with complete validations, animations &amp;
     having the functionality as following:</p>
     <ul>
         <li>To create a new task.</li>
         <li>To get the detailed description of the task.</li>
         <li>To delete the task.</li>
         <li>To edit the task.</li>
         <li>To search the task (by title &amp; date).</li>
         <li>To modify the task.</li>
         <li>To sort the tasks (by title &amp; date).</li>
         <li>To view all the tasks in grid or list style</li>
     </ul>
     <p>All the records will be stored at PHP database.
     The App can be maintained by editors, writers, reviewers or readers like you as a way to focus your collaborative efforts.</p>
     <br>
     <h4>&#x25cf; <b>Prerequisite</b></h4>
     <hr>
     <p>Things you need before playing around the application is:</p>
     <ul>
         <li>Proper internet connectivity (for loading the CDN documents).</li>
         <li>PHP Database created at phpmyadmin.</li>
         <li>WAMP/LAMP/MAMP/XAMPP (according to your <mark title="Operating System">OS</mark>) installed system.</li>
     </ul>
     <br>
     <h4>&#x25cf; <b>Getting Started</b></h4>
     <hr>
     <ul>
         <li>Since, app is built in Laravel. Before its use, open terminal and access the directory 
             where the app folder is placed and trigger the command "$ php artisan serve".
         </li>
         <li>Now,reach any browser and type in URL "/todo" following with your local server name (e.g. localhost/todo).</li>
        <li>Homepage of the app would be displayed.</li>
        <br>
        <li>To <b>Create a new task</b></li>
            <ul>
                <li>Click on the menu button positioned at top left cornerc &amp; find "CREATE" button
                    or you can click on "Quick Add" 
                    button at right sidebar.</li>
                <li>Give a Title to the task.</li>
                <li>Feed the description of the task in Task input field.</li>
                <li>Assign a completion date to the task.</li>
                <li>Click on "ADD TASK" button.</li>
            </ul>
        <br>
        <li>To <b>view a task</b></li>
            <ul>
                <li>Go to the Homepage.</li>
                <li>Click on whatever task you want to know about.</li>
            </ul>
        <br>
        <li>To <b>edit a task</b></li>
            <ul>
                <li>Go to the Homepage.</li>
                <li>Click on whatever task you want to edit.</li>
                <li>you would find edit button at bottom of the task, click on it.</li>
                <li>After modification, click on "UPDATE TASK" button.</li>
            </ul>
        <br>
        <li>To <b>delete a task</b></li>
            <ul>
                <li>Go to the Homepage.</li>
                <li>Click on whatever task you want to delete.</li>
                <li>you would find delete button at bottom of the task, click on it.</li>
            </ul>
        <br>
        <li>To <b>find a task</b></li>
            <ul>
                <li>Go to the Homepage.</li>
                <li>Click on the menu button positioned at top left corner &amp; find "SEARCH" button
                    or you can click on "Quick Find" 
                    button at right sidebar.</li>
                <li>You can search the task by it's title, completion date or creation date, just type any tag in search box &amp; 
                    click on "Task Search" button.
                </li>
            </ul>
        <br>
        <li>To <b>delete all the tasks</b></li>
            <ul>
                <li>Go to the Homepage.</li>
                <li>Click on Quick Clear at sidebar.</li>
            </ul>
        </ul>
</div>
@endsection