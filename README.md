VaultPhp - PHP API Client for HashiCorp Vault
==================================================


Example Usage
-------------

    $client = new VaultPhp\Client(new \GuzzleHttp\Client(), [
        'endpoint' => 'http://localhost:8200',
        'token'    => '<AUTH TOKEN>',
    ]);

    // Write data to Vault
    $client->write('secret/my-key', ['hello' => 'world']);

    // Read data from Vault
    $response = $client->read('secret/my-key');

    echo $response->getData('hello'); // world

    // Write complex data types to Vault
    $client->write('secret/my-complex-key', ['array' => [1,2,3,4,5]]);

    // Read complex data types from Vault
    echo $client->read('secret/my-complex-key')->getData('array')[2]; // 3

    // Delete data from Vault
    $client->delete('secret/my-complex-key');

License
-------

MIT License
