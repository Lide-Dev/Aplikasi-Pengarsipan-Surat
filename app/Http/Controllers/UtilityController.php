<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllerException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use Illuminate\Support\Str;

/**
 * Utility class support for helper home component this project.
 * Important Setting:
 * 1. Setting your default props every controller that has view by $defaultProps variable.
 * 2. Set your dynamic component and props for home content component by function setDynamicComponent(), setDynamicProps()
 *
 * Note:
 * - This is for controller that was logic business with Home Component like content of Home component.
 */
class UtilityController extends Controller
{
    /**
     * Props that default will send at client-side.
     * @var array
     * Important config that must be include is:
     * - dyComponent = Config Dynamic Compenent for Home content.
     * - dyComponent[name] = Name of component for Home content.
     * - dyComponent[props] = Props for component at Home content.
     *
     */
    protected $defaultProps = ['dyComponent' => ['name' => 'Dashboard'], 'config' => []];

    /**
     * Using table view component at this page if value true.
     *
     * @var boolean
     */
    protected $useTable = false;
    /**
     * Props that will send at client-side
     *
     * @var array
     */
    private $responseProps = [];
    private $dataTable = [];
    private $configTable = [];


    /**
     * Setting name of home content component view client-side for response at props client-side.
     *
     * @param string $config
     * @param int|string $selectedContent
     * Content selected by the client side. Make sure it matches the sidebar content on the client side.
     * @return void
     */
    public function setDynamicComponent(string $config, $selectedContent)
    {
        if (empty($config)) {
            throw new ControllerException(ControllerException::NoConfigDynamicComponent, 500);
        }
        $this->responseProps['dyComponent']['name'] = $config;
        $this->responseProps['dyComponent']['selectedContent'] = $selectedContent;
    }

    /**
     * Setting props of home content component view client-side for response at props client-side.
     *
     * @param array $config
     * @return void
     */
    public function setDynamicProps(array $config)
    {
        if (empty($config)) {
            throw new ControllerException(ControllerException::NoConfigDynamicComponent, 500);
        }
        if (!empty($this->responseProps['dyComponent']['props'])) {
            $this->responseProps['dyComponent']['props'] = collect($this->responseProps['dyComponent']['props'])->merge($config)->toArray();
        } else
            $this->responseProps['dyComponent']['props'] = $config;
    }

    /**
     * Set table config to props if client side has Table View component.
     *
     * @param array $request Handle request of sorting data and search table. Must provided query:
     * - sortColumn => Column that was being sorted.
     * - sortBy => 'asc'/'desc'.
     * @param Illuminate\Pagination\LengthAwarePaginator $paginate Handle paginate data.
     * - totalData => Total of all data that was being output.
     * - currentPage => Current page data.
     * - lastPage => Last page of paginate.
     * - itemPerPage => Item per page of paginate.
     * @return void
     */
    public function setTableConfig(Request $request, \Illuminate\Pagination\LengthAwarePaginator $paginate)
    {
        $page = ['totalData' => $paginate->total(), 'currentPage' => $paginate->currentPage(), 'lastPage' => $paginate->lastPage(), 'itemPerPage' => $paginate->perPage()];
        $this->configTable['paginate'] = $page;
        $this->configTable['sort'] = ['column' => $request->query('sortColumn'), 'by' => $request->query('sortBy')];
        $this->configTable['search'] = $request->query('search');
    }

    /**
     * Set data table to props if client side has Table View component. Automatically convert key of array data to camel case.
     *
     * @param array|Illuminate\Support\Collection $data
     * @return void
     */
    public function setTableData($data)
    {
        $data = $this->camelCaseConverter($data);
        $this->dataTable = $data;
    }

    /**
     * Get all response props that was been setting at controller.
     *
     * @return void
     */
    public function getResponseProps()
    {
        return $this->responseProps;
    }

    /**
     * Send rendering component to process at Client-side. It's good at returning response.
     * If you $useTable value true, it will share your data table at $page variable like https://inertiajs.com/shared-data#accessing-shared-data.
     *
     * @param string $component Path of component at client-side. It's from resources/js/Pages
     * @param boolean $isEmptyProps If you don't want any props on that component, just give 'True' value.
     * @return void
     */
    public function render(string $component, bool $isEmptyProps = false)
    {
        if ($this->useTable && (!empty($this->dataTable) || !empty($this->configTable))) {
            // Inertia::share('tableData', $this->dataTable);
            // Inertia::share('tableConfig', $this->configTable);
            $this->responseProps['dyComponent']['props']['tableData'] = $this->dataTable;
            $this->responseProps['dyComponent']['props']['tableConfig'] = $this->configTable;
        }
        if ($isEmptyProps) {
            return Inertia::render($component);
        } else {
            // dd($this->defaultProps);
            return Inertia::render($component, $this->configurePropsState());
        }
    }

    public function camelCaseConverter($data)
    {
        if (!$data instanceof \Illuminate\Support\Collection) {
            $collection = collect($data);
        }
        $new = $collection->map(function ($item, $key) {
            $arr = [];
            foreach ($item->toArray() as $key => $value) {
                $key = Str::camel($key);
                $arr += [$key => $value];
            }
            return $arr;
        });
        // dd($new);
        return $new->toArray();
    }

    public function errorHandler(Request $request, $error = 'notfound')
    {
        // dd($error);
        $is_server_error = ($error === 'server' || $error === 'maintenance');
        $message = Lang::get('errors.notfound');
        if (in_array($error, array_keys(Lang::get('errors')))) {
            $message = Lang::get('errors.' . $error);
        }
        // dd($message, in_array($error, Lang::get('errors')), Lang::get('errors'));
        return Inertia::render('Error/Index', ['errors' => ['message' => $message, 'isServer' => $is_server_error]]);
    }

    protected function configurePropsState()
    {
        $default = $this->defaultProps;
        if (empty($this->responseProps)) {
            return $default;
        } else {
            if (isset($this->responseProps['dyComponent'])) {
                $dyComponent = &$this->responseProps['dyComponent'];
                if (empty($dyComponent['name'])) {
                    $dyComponent['name'] = $default['dyComponent']['name'];
                } else if (empty($dyComponent['selectedContent'])) {
                    $dyComponent['selectedContent'] = $default['dyComponent']['selectedContent'];
                }
                if (!empty($dyComponent['props'])) {
                    // dd($dyComponent['props']);
                    $this->filterNullProps($dyComponent['props']);
                    // dd($dyComponent['props']);
                }
            } else {
                $this->responseProps['dyComponent'] = $default['dyComponent'];
            }
            return $this->responseProps;
        }
    }

    protected function filterNullProps(&$props)
    {
        foreach ($props as $key => $value) {
            if (is_array($value) && $key !== "tableData") {
                $this->filterNullProps($props[$key]);
            }
            if (empty($props[$key])) {
                unset($props[$key]);
            }
        }
    }
}
