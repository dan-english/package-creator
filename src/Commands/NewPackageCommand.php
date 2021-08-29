<?php
namespace DanEnglish\PackageCreator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewPackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:new {packagename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package folder. Use plural model name';

    protected $folders = [
        'Assets',
        'Assets/js',
        'Assets/js/components',
        'Events',
        'Http',
        'Http/Controllers',
        'Listeners',
        'Models',
        'Models/Logic',
        'Observers',
        'Providers',
        'Tests',
        'Views',
    ];

    protected $packageName = '';
    protected $modelName   = '';
    protected $packagePath = '';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $packageName = $this->argument('packagename');
        $this->packageName = ucfirst($packageName);
        //drop the 's' off the end @todo check that the name is plural before dropping the last character
        $this->modelName = (mb_substr($this->packageName, 0, -1));

        $this->info($this->packageName);
        $this->info($this->modelName);

        $base_path    = base_path();
        $package_base_path =  $base_path .'/packages';

        if (!File::exists($package_base_path)){
            $result = File::makeDirectory($package_base_path);
        }

        $this->package_path = $package_base_path.'/'.$this->packageName;
        $this->info($this->package_path);

        $this->checkForPackageServiceProviderInProject();
        $this->checkForAnnotationsServiceProviderInProject();
        $this->checkForLogicPassThrough();
        //@todo update composer PS4 mapping

        if (!File::exists($this->package_path)){
            $result = File::makeDirectory($this->package_path);
            foreach($this->folders as $folder) {
                $f_path = $this->package_path."/".$folder;
                $result = File::makeDirectory($f_path);
            }

        }

        $this->makeContoller();
        $this->makeServiceProvider();
        $this->makeModel();
        $this->makeModelLogic();
        $this->makeObserver();
        $this->makeView();
        $this->makeSeeder();

        $configLine = 'Packages\\'.$this->packageName.'\Providers\\'.$this->packageName.'ServiceProvider::class,';

        $appConfig = base_path() . '/config/app.php';
        $contents               = file_get_contents( $appConfig);
        $replace_with =  "'providers' => [". "\n\t" .$configLine;
        $updated_contents       = preg_replace('/\'providers\' => \[/i', $replace_with, $contents);
        file_put_contents($appConfig, $updated_contents);



        Artisan::call('make:migration create_'.Str::lower($this->packageName).'_table');
    }

    /**
     * Check that this laravel project has the package service provider which will process our custom packages
     **/
    private function checkForPackageServiceProviderInProject()
    {
        $packagesServiceProvider = app_path() . '/Providers/PackageServiceProvider.php';

        if (!file_exists($packagesServiceProvider)) {
            $this->warn('Service Provider Manager does not exist, copying from base package');
            $base_packageServiceProvider  =  __DIR__.'../../../resources/base_files/PackageServiceProvider.php';

            $success = \File::copy($base_packageServiceProvider, $packagesServiceProvider);

            $appConfig = base_path() . '/config/app.php';
            $contents               = file_get_contents( $appConfig);
            $replace_with =  "'providers' => [". "\n\t" . 'App\Providers\PackageServiceProvider::class,';
            $updated_contents       = preg_replace('/\'providers\' => \[/i', $replace_with, $contents);
            file_put_contents($appConfig, $updated_contents);

        }





    }
    /**
     * Check that this laravel project has the package service provider which will process our custom packages
     **/
    private function checkForAnnotationsServiceProviderInProject()
    {
        $annotationsServiceProvider = app_path() . '/Providers/AnnotationsServiceProvider.php';
        if (!file_exists($annotationsServiceProvider)) {
            $this->warn('Annotations Provider does not exist, copying from base package');
            $base_annotationsServiceProvider  =  __DIR__.'../../../resources/base_files/AnnotationsServiceProvider.php';

            $success = \File::copy($base_annotationsServiceProvider, $annotationsServiceProvider);
            $result = File::makeDirectory(app_path() . '/Http/Annotations');

            $appConfig = base_path() . '/config/app.php';
            $contents               = file_get_contents( $appConfig);
            $replace_with =  "'providers' => [". "\n\t" . 'App\Providers\AnnotationsServiceProvider::class,';
            $updated_contents       = preg_replace('/\'providers\' => \[/i', $replace_with, $contents);
            file_put_contents($appConfig, $updated_contents);


        }
    }

    private function checkForLogicPassThrough()
    {

        $logicpassthroughfolder = base_path() . '/packages/Logic';
        $logicPassThroughFile = $logicpassthroughfolder . '/HasLogicPassThrough.php';
        $base_logicPassThroughFile  =  __DIR__.'../../../resources/base_files/HasLogicPassThrough.php';

        if (!file_exists($logicPassThroughFile)) {
            $this->warn('Logic Pass Through does not exist, copying from base package');

            // create the logic folder if it doesnt exist
            if(!file_exists($logicpassthroughfolder)) {
                $result = File::makeDirectory($logicpassthroughfolder);
            }

            $success = \File::copy($base_logicPassThroughFile, $logicPassThroughFile);
        }

    }

    /**
     * Replace tags within string with package relevant names
     *
     * @param string $contents
     * @return string
     */
    private function updateFileContents(string $contents): string
    {
        $package_name       = preg_replace('/\{PACKAGENAME\}/i', $this->packageName, $contents);
        $lower_package_name = preg_replace('/\{LOWER_PACKAGENAME\}/i', STR::lower($this->packageName), $package_name);

        $model_name         = preg_replace('/\{MODELNAME\}/i', $this->modelName, $lower_package_name);
        $lower_model_name   = preg_replace('/\{LOWER_MODELNAME\}/i', STR::lower($this->modelName), $model_name);

        return $lower_model_name;
    }

    /**
     * Copy & update the base Controller into the new package
     */
    private function makeContoller()
    {
        $this->warn("Creating Controller");

        $contents         = file_get_contents( __DIR__.'../../../resources/base_files/controller.php');
        $updated_contents = $this->updateFileContents($contents);
        $path             = base_path() .'/packages/'.$this->packageName.'/Http/Controllers/'.$this->packageName.'Controller.php';

        file_put_contents($path, $updated_contents);
    }

    /**
     * Copy & update the base Service Provider into the new package
     */
    private function makeServiceProvider()
    {
        $this->warn("Creating Service Provider");

        $contents         = file_get_contents( __DIR__.'../../../resources/base_files/serviceprovider.php');
        $updated_contents = $this->updateFileContents($contents);
        $path             = base_path() .'/packages/'.$this->packageName.'/Providers/'.$this->packageName.'ServiceProvider.php';

        file_put_contents($path, $updated_contents);
    }

    /**
     * Copy & update the index blade view into the new package
     */
    private function makeView()
    {
        $this->warn("Creating View");

        $contents         = file_get_contents( __DIR__.'../../../resources/views/index.blade.php');
        $updated_contents = $this->updateFileContents($contents);
        $path             =  base_path() .'/packages/'.$this->packageName.'/Views/index.blade.php';

        file_put_contents($path, $updated_contents);
    }

    /**
     * Copy & update the Model into the new package
     */
    private function makeModel()
    {
        $this->warn("Creating Model");

        $contents         = file_get_contents( __DIR__.'../../../resources/base_files/model.php');
        $updated_contents = $this->updateFileContents($contents);
        $path             = base_path() .'/packages/'.$this->packageName.'/Models/'.$this->modelName.'.php';

        file_put_contents($path, $updated_contents);
    }

    /**
     * Copy & update the Model Logic file into the new package
     */
    private function makeModelLogic()
    {
        $this->warn("Creating Model Logic");

        $contents         = file_get_contents( __DIR__.'../../../resources/base_files/modellogic.php');
        $updated_contents = $this->updateFileContents($contents);
        $path             = base_path() .'/packages/'.$this->packageName.'/Models/Logic/'.$this->modelName.'Logic.php';

        file_put_contents($path, $updated_contents);
    }

    /**
     * Copy & update the Observer into the new package
     */
    private function makeObserver()
    {
        $this->warn("Creating Observer");

        $contents         = file_get_contents( __DIR__.'../../../resources/base_files/observer.php');
        $updated_contents = $this->updateFileContents($contents);
        $path             = base_path() .'/packages/'.$this->packageName.'/Observers/'.$this->modelName.'Observer.php';

        file_put_contents($path, $updated_contents);
    }

    /**
     * Copy & update a basic seeder file
     */
    private function makeSeeder()
    {

        $contents         = file_get_contents( __DIR__.'../../../resources/base_files/seeder.php');
        $updated_contents = $this->updateFileContents($contents);
        $path             = database_path() .'/seeders/'.$this->packageName.'Seeder.php';

        file_put_contents($path, $updated_contents);

    }

}
