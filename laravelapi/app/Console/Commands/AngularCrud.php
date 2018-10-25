<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AngularCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'angular:crud 
    {component_name? : component_name dir}
    {--t|template=templateOne : template dir} 
    {--c|component_name= : component_name dir} 
    {--r|route_prefix=admin : component route prefix}
    {--a|api_path= : API path} 
    {--k|keyword=* : keyword need to replace}        
    {--p|replace=* : replace to what}
    {--clone_template : clone a template to code dir}
    {--reset_angular_app : delete all directories in angular app folder unless .no_auto_delete file exists}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $angular_template_dir = '/angular-crud-templates';

    private $angular_code_dir = '/angular-crud-code';

    private $angular_app_dir = '/angularcrud/src/app';

    private $testing_api_resources = [
        'Local API: 1000 members'=>'http://localhost:4624/api/members',
        'Local API: 1000 locations'=>'http://localhost:4624/api/locations',
        'Local API: 1000 stocks'=>'http://localhost:4624/api/stocks',
        'Remote API: 100 posts'=>'https://jsonplaceholder.typicode.com/posts',
        'Remote API: 500 comments'=>'https://jsonplaceholder.typicode.com/comments',
        'Remote API: 100 albums'=>'https://jsonplaceholder.typicode.com/albums',
        'Remote API: 5000 photos'=>'https://jsonplaceholder.typicode.com/photos',
        'Remote API: 200 todos'=>'https://jsonplaceholder.typicode.com/todos',
        'Remote API: 10 users'=>'https://jsonplaceholder.typicode.com/users',
    ];

    private $api_result_has_data = false;

    private $options = [];

    private $api_fields = '';

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
        $this->angular_template_dir = storage_path() . $this->angular_template_dir;
        $this->angular_code_dir = storage_path() . $this->angular_code_dir;
        $this->angular_app_dir = dirname(dirname(storage_path())) . $this->angular_app_dir;
        //$this->getOutput()->setDecorated( true );

        $arguments = $this->arguments();
        $this->options = $this->options();

        if ( $this->options['reset_angular_app'] == true){
            $this->resetAngularApp();
        }

        $exists_templates = glob($this->angular_template_dir.'/*');
        foreach ($exists_templates as $file) {
            $template_names[] = basename($file);
        }

        //$this->comment(json_encode($template_names));

       $this->options['template'] = $this->choice('Please select an Angular CRUD template from 0-'.count($exists_templates).' below, default is ',$template_names, 0);

       while(!$this->options['component_name'] || strlen($this->options['component_name'])<3 || is_numeric($this->options['component_name']) || file_exists($this->angular_app_dir . '/'.$this->options['component_name']) ){
            if( $this->options['component_name'] != false ){
                if (  file_exists($this->angular_app_dir . '/'.$this->options['component_name']) ){
                    $this->error('Angular component directory "' . $this->options['component_name'] . '" already exists');
                } else {
                    $this->error('Invalid Angular component name');
                }
               
            }
            $this->options['component_name'] = $this->ask('Please enter your Angular component name[singular], eg. post,comment,location,uploadImage,housePrice,ngCrudTest', 'test'.rand(10,99));
       }
       
        $this->options['route_prefix'] = $this->ask('Please enter your Angular component route prefix, default is ','admin');


       //first time ask api_path
        if ( $this->options['api_path'] == null ){
            $this->options['api_path'] = $this->ask('RESTful API path for this CRUD component, eg. http://localhost:4520/backend/api/members  Press Enter to select a testing API');
        }
        
        // show test api_path 
        if( $this->options['api_path'] == null){
            $testing_api = $this->choice('Please select a testing API resource', array_keys($this->testing_api_resources),0);
            $this->options['api_path'] = $this->testing_api_resources[$testing_api];
        } else {
            while ( $this->options['api_path'] != null && stripos($this->options['api_path'], 'http') === false ){
                $this->options['api_path'] = $this->ask('RESTful API path for this CRUD component, eg. http://localhost:4520/backend/api/uploadFiles  Press Enter to select a testing API');
            }

            if( $this->options['api_path'] == null){
                $testing_api = $this->choice('Please select a testing API resource', array_keys($this->testing_api_resources),0);
                $this->options['api_path'] = $this->testing_api_resources[$testing_api];
            }

        }


        $this->api_fields = $this->getApiDataFields($this->options['api_path']);
        if ( $this->api_fields == false) {
            $this->error('You must set a valid RESTful API url !');
            return false;
        }


        //$this->line('############# options: ' . json_encode($this->options));

        //$this->line('arguments: ' . json_encode($arguments));


        //exit();

        if (!file_exists($this->angular_template_dir . '/' . $this->options['template'])) {
            $this->error('Template ' . $this->options['template'] . ' not exists!');
            return false;
        }

        if ($arguments['component_name'] != null && $this->options['component_name'] == null) {
            $this->options['component_name'] = $arguments['component_name'];
        }

        if ($this->options['component_name'] == null) {
            $this->error('You must set a component_name dir!');
            return false;
        }

        if (!file_exists($this->angular_code_dir . '/' . $this->options['component_name'])) {
            mkdir($this->angular_code_dir . '/' . $this->options['component_name'], 0755);
        }


        $files = glob($this->angular_template_dir . '/' . $this->options['template'] . "/*.*");
        foreach ($files as $file) {
            $new_file = $this->angular_code_dir . '/' . $this->options['component_name'] . '/' . str_replace($this->options['template'], $this->options['component_name'], basename($file));

            copy($file, $new_file);

            $this->replaceFileStr($new_file);
        }

        $this->setComponentFileCode($this->options['component_name']);

        if ( $this->options['clone_template'] == false ){
            rename ($this->angular_code_dir . '/' . $this->options['component_name'], $this->angular_app_dir . '/' . $this->options['component_name']);
        }

        $this->setAngularAppFileCode($this->options['component_name']);
        
    }


    function codeSnippets($all_code, $snippet)    {
        $this->line($snippet);
        return $all_code . "\n" . $snippet;
    }

    function setAngularAppFileCode($key){
        $capital = ucfirst($key);
        $lcfirst = lcfirst($key);
        $this->line('');
        $this->line('');
        $this->line('');
        $this->error('########## Angular 2+ component '  . $capital .  ' CRUD code generated successfully ########## ');
        $this->line('');
        $this->error('########## Follow below instructions if you want use it in your real angular project ########## ');        
        $this->comment('===== Please test this component first by visit below url =====');
        $this->line('');
        $this->line('');
        $this->line('');
        $this->line('===== Step 1: Test by visit: http://localhost:4520/');
        $this->line('');

        $this->line('');
        $this->comment('===== Step 2: Copy below folder to your real angular src/app folder =====');
        $this->line('');
        $this->line($this->angular_app_dir . '/'. $lcfirst);
        $this->line('');

        // Below code generate for Angular file xxx.routes.ts
        $route_ts_code='';
        $route_path_code='';
        $module_ts_code='';


        $this->line('');
        $this->comment('===== Step 3: Add below line to your angular app.module.ts =====');
        $this->line('');

        $module_ts_code = $this->codeSnippets($module_ts_code, 'import { ' . $capital . 'Module } from "./' . $lcfirst . '/' . $lcfirst . '.module";');

        $this->line('');
        $this->line('And add ' . $capital . 'Module to the @NgModule imports[]');
        $this->line('');

        $angular_app_file = 'app.module.ts';
        $import_flag ='//AngularCRUD-IMPORT-MODULE'; 
        $body_flag ='//AngularCRUD-NG-MODULE';
        $this->modifyAngularAppFile(
            [
                $import_flag, 
                $body_flag
            ], 
            [
                $module_ts_code . "\n\n". $import_flag, 
                $body_flag. "\n\t" . $capital . 'Module,' . ""
            ]
            , $angular_app_file);


        $this->line('');
        $this->comment('===== Step 4: Add below lines to your angular app-routes.module.ts =====');
        $this->line('');
        $route_ts_code = $this->codeSnippets($route_ts_code, '//' . $capital . ' component import files');
        $route_ts_code = $this->codeSnippets($route_ts_code, 'import { ' . $capital . 'Route } from "./' . $lcfirst . '/' . $lcfirst . '.route";');
        $route_ts_code = $this->codeSnippets($route_ts_code, 'import { ' . $capital . 'IndexComponent } from "./' . $lcfirst . '/' . $lcfirst . '-index.component";');
        $route_ts_code = $this->codeSnippets($route_ts_code, 'import { ' . $capital . 'EditComponent } from "./' . $lcfirst . '/' . $lcfirst . '-edit.component";');
        $route_ts_code = $this->codeSnippets($route_ts_code, 'import { ' . $capital . 'ShowComponent } from "./' . $lcfirst . '/' . $lcfirst . '-show.component";');
        $route_ts_code = $this->codeSnippets($route_ts_code, 'import { ' . $capital . 'Resolver } from "./' . $lcfirst . '/' . $lcfirst . '.resolver";');


        $this->line('');
        $this->line('');

        $route_path_code = $this->codeSnippets($route_path_code, '//' . $capital . ' route paths');
        $route_path_code = $this->codeSnippets($route_path_code, '{ path: ' . $capital . 'Route.index, component: ' . $capital . 'IndexComponent},');

        $route_path_code = $this->codeSnippets($route_path_code, '{ path: ' . $capital . 'Route.create, component: ' . $capital . 'EditComponent },');
        $route_path_code = $this->codeSnippets($route_path_code, '{ path: ' . $capital . 'Route.show, component: ' . $capital . 'ShowComponent},');
        $route_path_code = $this->codeSnippets($route_path_code, '{ path: ' . $capital . 'Route.edit, component: ' . $capital . 'EditComponent, resolve: {' . $lcfirst . 'Resolver: ' . $capital . 'Resolver}},');
        $route_path_code = $this->codeSnippets($route_path_code, '{ path: ' . $capital . 'Route.delete, component: ' . $capital . 'EditComponent},');


        $angular_app_file = 'app-routing.module.ts';
        $import_flag ='//AngularCRUD-IMPORT-ROUTE-COMPONENT'; 
        $body_flag ='//AngularCRUD-ROUTE-PATH';
        $this->modifyAngularAppFile(
            [
                $import_flag, 
                $body_flag
            ], 
            [
                $route_ts_code . "\n". $import_flag, 
                $route_path_code . "\n". $body_flag
            ]
            , $angular_app_file);


        $this->line('');
        $this->comment('===== Step 5: Add a link to your angular app.component.html =====');
        $this->line('');
        $this->line('<a class="nav-item nav-link" routerLink="/' . $this->options['route_prefix']. '/' . $lcfirst . 's">' . $capital . 's</a>');
        $this->line('');

        $angular_app_file = 'app.component.html';
        $body_flag ='<!-- AngularCRUD INSERT ROUTE LINK -->';
        $this->modifyAngularAppFile(
            [
                $body_flag
            ], 
            [
                $body_flag . "\n" . '<a class="nav-item nav-link" routerLink="/' . $this->options['route_prefix']. '/' . $lcfirst . 's">' . $capital . 's</a>' . "\n"
            ]
            , $angular_app_file);

        $this->line('');
        $this->comment('##### Done ######');
        $this->line('');            
            
    }

    function setComponentFileCode($key)
    {
        $capital = ucfirst($key);
        $lcfirst = lcfirst($key);
        
        // get api fields
        $this->line('===== Fetch fields from API ' . $this->options['api_path']);
        if ( stripos($this->options['api_path'], 'http') === 0 ){
            
            if ( is_array($this->api_fields) ){
                $ng_model_interface = '';
                $ng_form_fields = '';
                $ng_list_datatable = ['settings'=>'','fields'=>''];
                $ng_details_html = '';
                $ng_form_html = '';
                $i=1;
                foreach($this->api_fields as $k=>$v) {
                    if ( !in_array($k, ['id','name']) ) {
                        $ng_model_interface .= "\n\t". $k . '?: ' . $this->api_fields[$k]['type'] . ';';
                    }
                    if ( !in_array($k, ['id','created_at','updated_at','deleted_at']) ) {
                        $ng_form_fields .= "\n\t". $k . ': new FormControl(undefined),';

                        $ng_form_html .= "\n\t" . '<div class="form-group"><label for="' . $k . '">' . ucfirst($k) . '</label>' . "\n\t\t"; 

                        if ( $this->api_fields[$k]['length'] > 100 ){
                            $ng_form_html .= '<textarea class="form-control" id="' . $k . '" placeholder="" name="' . $k . '" formControlName="' . $k . '"></textarea>';
                        } else if ( $this->api_fields[$k]['type'] == 'boolean'){
                            $ng_form_html .= '<input type="radio" formControlName="' . $k . '" value="1" class="ml-2"> Yes'. "\n\t". 
                            '<input type="radio" formControlName="' . $k . '" value="0"> No';
                        } else {
                            $input_type = ( $this->api_fields[$k]['type'] == 'number') ? 'number' : 'text';
                            $ng_form_html .= '<input type="' . $input_type . '" class="form-control" id="' . $k . '" placeholder="" min=0 name="' . $k . '" formControlName="' . $k . '">';
                        }
                        

                        $ng_form_html .= "\n\t" . '</div>' . "\n\t";

                    }
                    if ( !in_array($k, ['id']) && $this->api_fields[$k]['length'] < 100 && $i<8) {
                        $ng_list_datatable['settings'] .= "\n\t\t". '{objectKey: "' . $k . '", sort: "enable", columnOrder: ' . ($i*5) .'},';
                        $ng_list_datatable['fields'] .= "\n\t\t". '{objectKey: "' . $k . '", name: "' . ucfirst($k) . '"},';
                    }
                    $ng_details_html .= "\n\t\t". '<tr><th scope="row">' . ucfirst($k) . '</th><td>{{item.' . $k . '}}</td></tr>';
                    $i++;
                }

                //$this->line($ng_model_interface . $ng_form_fields . $ng_list_datatable['settings'] . $ng_list_datatable['fields']);
            }

            $angular_app_file = '' . $lcfirst . '/' . $lcfirst . '.model.ts';
            if ( $this->api_result_has_data == true){
                
                $this->modifyAngularAppFile(
                    [
                        '//AngularCrud-API-LIST-MODEL', 
                        'ApiModel<T> extends ' . $capital . 'Model',
                    ], 
                    [
                        "\n\t\t" . 'data: T;'.
                        "\n\t\t" . 'success?: boolean;'.
                        "\n\t\t" . 'message?: string;'.
                        "\n\t\t", 

                        'ApiModel<T>',
                    ]
                    , $angular_app_file);
            }            
            $this->modifyAngularAppFile(
                [
                    '//AngularCrud-API-MODEL', 
                    '//AngularCrud-FORM-FIELDS',
                ], 
                [
                    $ng_model_interface . "\n", 
                    $ng_form_fields . "\n",
                ]
                , $angular_app_file);


            $angular_app_file = '' . $lcfirst . '/' . $lcfirst . '-index.component.ts';
            $this->modifyAngularAppFile(
                [
                    '//AngularCrud-DATATABLE-SETTINGS', 
                    '//AngularCrud-DATATABLE-FIELDS',
                    'AngularCrud_API_MAIN_RESULT',
                ], 
                [
                    $ng_list_datatable['settings'] . "\n", 
                    $ng_list_datatable['fields'] . "\n",
                    ( $this->api_result_has_data == true) ? 'result.data' : 'result',
                ]
                , $angular_app_file);

            $angular_app_file = '' . $lcfirst . '/' . $lcfirst . '-edit.component.ts';
            $this->modifyAngularAppFile(
                [
                    'AngularCrud_API_MAIN_RESULT',
                ], 
                [
                    ( $this->api_result_has_data == true) ? 'result.data' : 'result',
                ]
                , $angular_app_file);

            $angular_app_file = '' . $lcfirst . '/' . $lcfirst . '-show.component.ts';
            $this->modifyAngularAppFile(
                [
                    'AngularCrud_API_MAIN_RESULT',
                ], 
                [
                    ( $this->api_result_has_data == true) ? 'result.data' : 'result',
                ]
                , $angular_app_file);


            $angular_app_file = '' . $lcfirst . '/' . $lcfirst . '-show.component.html';
            $this->modifyAngularAppFile(
                [
                    '<!-- AngularCrud-DETAIL-HTML -->', 
                ], 
                [
                    $ng_details_html . "\n", 
                ]
                , $angular_app_file);      
                
            $angular_app_file = '' . $lcfirst . '/' . $lcfirst . '-edit.component.html';
            $this->modifyAngularAppFile(
                [
                    '<!-- AngularCrud-FORM-HTML -->', 
                ], 
                [
                    $ng_form_html . "\n", 
                ]
                , $angular_app_file);                  
        }
    }

    private function modifyAngularAppFile($search_ary, $replace_ary, $angular_app_file){
        if ( strpos($angular_app_file, '/') === false ) {
            $dir = $this->angular_app_dir;
        } else {
            $dir = $this->angular_code_dir;
        }
        $str = file_get_contents($dir . '/'.$angular_app_file);
        $str = str_replace($search_ary, $replace_ary, $str);
        file_put_contents($dir . '/'.$angular_app_file, $str);
    }


    public function replaceFileStr($f)
    {
        $str = file_get_contents($f);
        $search_ary = $this->options['keyword'];
        $replace_ary = $this->options['replace'];

        $search_ary[] = $this->options['template'];
        $replace_ary[] = $this->options['component_name'];

        $search_ary[] = 'AngularCrudRoutePrefix';
        $replace_ary[] = $this->options['route_prefix'];
        $search_ary[] = 'AngularCrudApiPath';
        $replace_ary[] = $this->options['api_path'];


        $str = str_replace($search_ary, $replace_ary, $str);
        //$this->comment('options: ' . json_encode($this->options));

        $search_ary = array_map('ucfirst', $search_ary);
        $replace_ary = array_map('ucfirst', $replace_ary);

        $str = str_replace($search_ary, $replace_ary, $str);
        //$this->question('options: ' . json_encode($this->options));

        file_put_contents($f, $str);
    }

    private function getApiDataFields($api_path){

        try {
            $str = trim(file_get_contents($api_path .'?limit=2'));
        }
        catch (\Exception $e) {
            $this->line('Cannot get API result: ' . $e->getMessage());
            return false;
        }

        
        if ( strpos($str, '[') !== 0 && strpos($str, '{') !== 0 ){
            $this->line('API result is not json format. The result should start with { or [');
            return false;
        }
        $json_data = json_decode($str, false); // return json object
        if ( $json_data === false ){
            $this->line('API result json_decode failed');
            return false;
        }

        //var_dump($json_data);exit('message=' . $json_data->message);

        if ( isset($json_data->data) && is_array($json_data->data) ){
            $detail_data = $json_data->data[0];
            $this->api_result_has_data = true;
        } else if ( is_array($json_data) && isset($json_data[0]) ) {
            $detail_data = $json_data[0];
        } else {
            $this->line('API result should be an array');
            return false;
        }

        $fields = [];
        $field_type = 'any';
        foreach($detail_data as $k=>$v) {
            if ( is_bool($v) ) $field_type = 'boolean';
            if ( is_numeric($v) ) $field_type = 'number';
            if ( is_string($v) ) $field_type = 'string';
            $fields[$k]['type'] = $field_type;
            $fields[$k]['length'] = strlen($v);
        }

        //$this->line(json_encode($fields));

        return $fields;
    }


    function resetAngularApp()  {

        $exists_file = glob($this->angular_app_dir.'/*');
        foreach ($exists_file as $file) {
            if ( is_dir($file) && !in_array($file,['.','..']) && !file_exists($file.'/.no_auto_delete') ){
                $this->rmdirRecursive($file);
            }
        }

        copy($this->angular_code_dir .'/app.module.ts', $this->angular_app_dir .'/app.module.ts');
        copy($this->angular_code_dir .'/app-routing.module.ts', $this->angular_app_dir .'/app-routing.module.ts');
        copy($this->angular_code_dir .'/app.component.html', $this->angular_app_dir .'/app.component.html');
    }

    function rmdirRecursive($dir) {
        foreach(scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir("$dir/$file")) rmdirRecursive("$dir/$file");
            else unlink("$dir/$file");
        }
        rmdir($dir);
    }
}
