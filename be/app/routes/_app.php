<?php

app()->group('/api', function() {
    app()->get('/hello/{name}', function ($name) {
        return response()->json(['meesage' => 'Hello, ' . ucfirst($name)]);
    });
});
