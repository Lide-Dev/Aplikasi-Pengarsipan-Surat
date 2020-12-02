<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllerException;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
     * Props that will send at client-side
     *
     * @var array
     */
    private $responseProps = [];

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
        $this->responseProps['dyComponent']['props'] = $config;

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
     *
     * @param string $component Path of component at client-side. It's from resources/js/Pages
     * @param boolean $isEmptyProps If you don't want any props on that component, just give 'True' value.
     * @return void
     */
    public function render(string $component, bool $isEmptyProps = false)
    {
        if ($isEmptyProps) {
            return Inertia::render($component);
        } else {
            // dd($this->defaultProps);
            return Inertia::render($component, $this->configurePropsState());
        }
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
               // dd($dyComponent);
            } else {
                $this->responseProps['dyComponent'] = $default['dyComponent'];
            }
            return $this->responseProps;
        }
    }
}
