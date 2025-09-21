<?php
return [
    'host' => 'database',               // Database host. 💬 This USED to be `localhost`, but since the Docker service in our case is called `database`, that's what needs to go here.
    'dbname' => getenv('DB_NAME'),      // In globals.env.
    'username' => 'root',               // User "running" any DB queries.
    'password' => getenv('DB_PASSWORD') // In globals.env.
];
