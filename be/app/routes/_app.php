<?php

app()->get('/', function() {
    response()->json(['message' => 'Congrats!! You\'re on Leaf API']);
});

app()->group('/api/v1', function() {
    app()->get('/hello/{name}', function ($name) {
        response()->json(['message' => 'Hello, ' . ucfirst($name)]);
    });

    app()->post('/reset_db', function() {
        try {
            db()->autoConnect();
            db()->drop('dwp_smkn4_malang')->execute();
            db()->create('dwp_smkn4_malang')->execute();
            response()->json(['message' => 'DB restarted, please migrate the database again']);
        } catch (Exception $err) {
            app()->logger()->error($err);
            response()->json(['message' => 'Error, please check the log for more info'], 500);
        }
    });

});
