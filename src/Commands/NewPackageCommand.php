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
    protected $description = 'Create a new package folder';

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
        //try and drop the 's' off the end
        $this->modelName = (mb_substr($this->packageName, 0, -1));

        $this->info($this->packageName);
        $this->info($this->modelName);

        $base_path    = base_path();
        $package_path = $base_path .'/packages/'.$this->packageName;

        $this->info($package_path);

        if (!File::exists($package_path)){
            $result = File::makeDirectory($package_path);
            foreach($this->folders as $folder) {
                $f_path = $package_path."/".$folder;
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
        $this->vueComponent();

        $configLine = 'Packages\\'.$this->packageName.'\Providers\\'.$this->packageName.'ServiceProvider::class,';
        $this->warn($configLine);

        Artisan::call('make:migration create_'.Str::lower($this->packageName).'_table');
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
        $path             = database_path() .'/seeds/'.$this->packageName.'Seeder.php';

        file_put_contents($path, $updated_contents);

    }

}
