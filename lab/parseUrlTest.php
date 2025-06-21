<?php


function parseUrlTest()
{


    $testUrl = [
        'http://www.exampe.com/home',
        'http://www.exampe.com/users/123',
        'http://www.exampe.com/search?q=php&page=2',
        'http://www.exampe.com/api/v1/users?limit=10&offset=20',
        'http://www.exampe.com/products/123?color=red&size=large#reviews',
        'http://www.exampe.com/',
        'http://www.example.com/path?googleguy=googley'
    ];


    foreach ($testUrl as $uri) {
        $path = parse_url($uri, PHP_URL_PATH);
        $query = parse_url($uri, PHP_URL_QUERY);
        echo "Uri: '$uri', Path: '$path', Query: '$query'\n";
    }

    /*
    Uri: '/home', Path: '/home', Query: ''
    Uri: '/users/123', Path: '/users/123', Query: ''
    Uri: '/search?q=php&page=2', Path: '/search', Query: 'q=php&page=2'
    Uri: '/api/v1/users?limit=10&offset=20', Path: '/api/v1/users', Query: 'limit=10&offset=20'
    Uri: '/products/123?color=red&size=large#reviews', Path: '/products/123', Query: 'color=red&size=large'
    Uri: '/', Path: '/', Query: ''
    Uri: '', Path: '', Query: '' 
    */
}




var_dump(parseUrlTest());
