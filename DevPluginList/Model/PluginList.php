<?php
namespace Opengento\DevPluginList\Model;

use Laminas\Code\Reflection\MethodReflection;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\App\Utility\ReflectionClassFactory;
use Magento\Framework\Filesystem\DirectoryList;
use ReflectionException;

class PluginList
{
    /**
     * @param ModuleListInterface $moduleList
     * @param Dir $moduleDir
     * @param ReflectionClassFactory $reflexionClassFactory
     * @param DirectoryList $directoryList
     */
    public function __construct(
        protected ModuleListInterface $moduleList,
        protected Dir $moduleDir,
        protected ReflectionClassFactory $reflexionClassFactory,
        protected DirectoryList $directoryList
    ){}

    /**
     * @return array
     * @throws FileSystemException
     * @throws ReflectionException
     */
    public function getPluginList(): array
    {
        $moduleConfig = include$this->directoryList->getPath('app').'/etc/config.php';
        $moduleConfigValues = array_keys($moduleConfig['modules']);
        $pluginList= [];
        $moduleList = $this->moduleList->getNames();
        $countPlugins = $countPluginsEnable = 0;
        foreach ($moduleList as $moduleName) {
            $modulePath = $this->moduleDir->getDir($moduleName);
            foreach (['','adminhtml/','frontend/','webapi_rest/','webapi_soap/'] as $path){
                $diXmlPath = $modulePath . '/etc/'.$path.'di.xml';
                if (file_exists($diXmlPath)) {
                    $xml = simplexml_load_file($diXmlPath);
                    $plugins = $xml->xpath('//type/plugin');
                    foreach ($plugins as $plugin) {
                        if($className = (string) $plugin['type'])
                        $reflexionPlugin = $this->reflexionClassFactory->create($className);
                        $methodList = $reflexionPlugin?->getMethods();

                        $breakTheLoop = false;
                        foreach ($methodList as $method) {
                            $balance = 0;
                            if(preg_match('~^before|^after|^around~',$method->getName())){

                                if(str_contains($method->getName(), 'around')){
                                    $breakTheLoop = true;
                                    $method = new MethodReflection((string) $plugin['type'].'::'.$method->getName());
                                    $param2nd = (string)$method->getParameters()[1]->getName();
                                    $methodBody = $method->getBody();
                                        if (str_contains($methodBody, '$'.$param2nd)) {
                                            $breakTheLoop = false;
                                        }
                                }
                                $scope = str_ireplace('/','',$path);
                                $scope = $scope?:'global';
                                $key = (string) $plugin->xpath('parent::*')[0]['name'].'::'.lcfirst(str_ireplace(array('after','before','around'),'',$method->getName()));
                                if(!isset($pluginList[$key]))
                                    $pluginList[$key] = [];

                                foreach ([0=>'before', 20000=>'after', 10000=>'around'] as $pound=>$prefix) {
                                    if (str_starts_with($method->name, $prefix)) {
                                        $balance = $pound;
                                    }
                                }
                                $balance += array_search($moduleName,$moduleConfigValues)/1000;
                                $pluginList[$key][] = [
                                    'name' => (string) $plugin['name'],
                                    'scope' => $scope,
                                    'class' => (string) $plugin['type'],
                                    'parent' => (string) $plugin->xpath('parent::*')[0]['name'],
                                    'sortOrder' => (string) $plugin['sortOrder']?:0,
                                    'pound' => (string) $plugin['sortOrder']?$plugin['sortOrder']+$balance:$balance,
                                    'method' => $method->getName(),
                                    'disable' => (string) $plugin['disabled'] == 'true',
                                    'breakTheLoop' => $breakTheLoop?'Yes':'',
                                ];
                                if($plugin['disabled'] != 'true') $countPluginsEnable++;
                                $countPlugins++;
                                $breakTheLoop = false;
                            }
                        }
                    }
                }
            }
        }
        return ['plugins'=>$pluginList,'count'=>['total'=>$countPlugins,'enable'=>$countPluginsEnable]];
    }
}
