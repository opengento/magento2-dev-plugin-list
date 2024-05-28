<?php
namespace Opengento\DevPluginList\Block\Adminhtml;

class Index extends \Magento\Backend\Block\Template
{

    const array COLOR = [
            'global'      =>'#C1EFA3FF',
            'webapi_rest' =>'#D3B38DFF',
            'webapi_soap' =>'#C7B17EFF',
            'frontend'    =>'#819A34FF',
            'adminhtml'   =>'#ADAC62FF',
            'disable'     =>'#E7B19BFF',
        ];

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        protected \Opengento\DevPluginList\Model\PluginList $_pluginList,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \ReflectionException
     */
    public function getPluginList(): array
    {
        return $this->_pluginList->getPluginList();
    }


    /**
     * @param $scope
     * @return string
     */
    function getBgColorList($scope): string
    {
        return self::COLOR[$scope];
    }
    function displayPluginList(mixed $datas): string
    {
        $str = '';
        foreach ($datas as $key=>$plugins) {
            $str .= '<div class="plugin-key header_plugin">' . $key . '</div>';
            foreach ($plugins as $plugin) {
                $str .= '<div class="plugin-value ' . $plugin['scope'] . '">';
                foreach ($plugin as $field => $value) {
                    $str .= '<span class="plugin-field ' . $plugin['scope'] . '">' . $field . ': </span>';
                    $str .= '<span class="plugin-value ' . $plugin['scope'] . '">' . $value . '</span>';
                }
                $str .= '</div>';
            }
        }
        return $str;
    }
    /**
     * @param mixed $datas
     * @return string
     */
    function extracted(mixed $datas): string
    {
        $str = '<div><table style="border:1px solid #0a6c9f;"><tr style="border:1px solid #0a6c9f;"><th>Plugin Scope</th><th>Plugin name</th><th>Plugin is Final</th><th>Method overridden</th><th>Sort order</th><th>Pound</th></tr>';
        foreach ($datas as $k=>$plugins) {
            $str.= '<tr style="border-top:1px solid #0a6c9f;border-bottom:1px solid #0a6c9f;">
                        <td colspan="6" style="background-color:#afa4a4;">' . $k . '</td>
                    </tr>';
            uasort($plugins, function($a, $b) {
                return $a['pound'] <=> $b['pound'];
            });
            foreach ($plugins as $plugin){

                $str.= '<tr style="border-left:1px solid #0a6c9f;border-right:1px solid #0a6c9f;">';
                $bg_color = $this->getBgColorList($plugin['scope']);
                $bg_color = $plugin['disable']?$this->getBgColorList('disable'):$bg_color;
                $str.= '<td style="background-color:'.$bg_color.'">' . strtoupper($plugin['scope']) . '</td>';
                $str.= '<td style="background-color:'.$bg_color.'">' . $plugin['name'] . '</td>';
                $str.= '<td style="background-color:'.$bg_color.'">' . $plugin['breakTheLoop'] . '</td>';
                $str.= '<td style="background-color:'.$bg_color.'">' . $plugin['class'] . '::' . $plugin['method'] . '</td>';
                $str.= '<td style="background-color:'.$bg_color.'">' . $plugin['sortOrder'] . '</td>';
                $str.= '<td style="background-color:'.$bg_color.'">' . $plugin['pound'] . '</td>';
                $str.= '</tr>';
            }
        }
        return $str . '</table></div>';
    }
}
