<?php

namespace App\Providers;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Exception
     */
    public function boot()
    {
        try {
            Storage::extend('google', function ($app, $config) {
                $client = new \Google_Client();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);
                $client->setApplicationName('Student Fita');
                $service = new \Google_Service_Drive($client);
                $adapter = new GoogleDriveAdapter($service, $config['folderId']);
                $driver = new Filesystem($adapter);
                return new FilesystemAdapter($driver, $adapter);
            });
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
