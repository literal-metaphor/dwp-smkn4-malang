<?php

app()->get('/', function () {
    response()->json(['message' => 'Congrats!! You\'re on Leaf API']);
});

app()->group('/api/v1', function () {
    app()->get('/hello/{name}', function ($name) {
        response()->json(['message' => 'Hello, ' . ucfirst($name)]);
    });
});
