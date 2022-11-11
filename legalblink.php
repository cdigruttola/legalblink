<?php
/**
 * 2007-2022 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2022 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Legalblink extends Module implements WidgetInterface
{
    protected const LEGALBLINK_COOKIE_CODE = 'LEGALBLINK_COOKIE_CODE';
    protected const LEGALBLINK_OTHER_PAGES = 'LEGALBLINK_OTHER_PAGES';
    protected const LEGALBLINK_OTHER_PAGES_ARRAY = 'LEGALBLINK_OTHER_PAGES[]';
    protected const LINKS = [
        'LEGALBLINK_COOKIE_POLICY' => 'Cookie policy',
        'LEGALBLINK_PRIVACY_POLICY' => 'Privacy policy',
        'LEGALBLINK_TERMS_SERVICE' => 'Terms of Service',
    ];
    protected const PAGE = '_PAGE';

    protected $templateFile;
    protected $linkPresenter;

    public function __construct()
    {
        $this->name = 'legalblink';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'cdigruttola';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('LegalBlink Module', [], 'Modules.Legalblink.Main');
        $this->description = $this->trans('LegalBlink Module to add links in footer', [], 'Modules.Legalblink.Main');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall this module?', [], 'Modules.Legalblink.Main');

        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];

        $this->templateFile = 'module:legalblink/views/templates/hook/legalblink_links.tpl';
        $this->linkPresenter = new Link();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook('displayBeforeBodyClosingTag')
            && $this->registerHook('displayFooterAfter')
            && $this->registerHook('filterCmsContent');
    }

    public function uninstall()
    {
        //Configuration::deleteByName(self::LEGALBLINK_COOKIE_CODE);
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $output = '';
        if ((Tools::isSubmit('submitLegalblinkModule'))) {
            if ($this->postProcess()) {
                $output .= $this->displayConfirmation($this->trans('Settings updated succesfully', [], 'Modules.Legalblink.Main'));
                $this->_clearCache('*');
            } else {
                $output .= $this->displayError($this->trans('Error occurred during settings update', [], 'Modules.Legalblink.Main'));
            }
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('alert_iframe_disable', !Configuration::get('PS_ALLOW_HTML_IFRAME'));
        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();

    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitLegalblinkModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Settings', [], 'Modules.Legalblink.Main'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'textarea',
                        'desc' => $this->trans('Enter the cookie code you find', [], 'Modules.Legalblink.Main') . ' <a href="https://app.legalblink.it/cookie" target="_blank">' . $this->trans('here', [], 'Modules.Legalblink.Main') . '</a>',
                        'name' => self::LEGALBLINK_COOKIE_CODE,
                        'label' => $this->trans('Cookie code', [], 'Modules.Legalblink.Main'),
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'desc' => $this->trans('Enter the cookie policy link you find', [], 'Modules.Legalblink.Main') . ' <a href="https://app.legalblink.it/cookie" target="_blank">' . $this->trans('here', [], 'Modules.Legalblink.Main') . '</a>',
                        'name' => 'LEGALBLINK_COOKIE_POLICY',
                        'label' => $this->trans('Cookie policy', [], 'Modules.Legalblink.Main'),
                        'required' => true,
                        'lang' => true,
                    ],
                    [
                        'type' => 'text',
                        'desc' => $this->trans('Enter the privacy policy link you find', [], 'Modules.Legalblink.Main') . ' <a href="https://app.legalblink.it/documenti" target="_blank">' . $this->trans('here', [], 'Modules.Legalblink.Main') . '</a>',
                        'name' => 'LEGALBLINK_PRIVACY_POLICY',
                        'label' => $this->trans('Privacy policy', [], 'Modules.Legalblink.Main'),
                        'required' => true,
                        'lang' => true,
                    ],
                    [
                        'type' => 'text',
                        'desc' => $this->trans('Enter the terms of service link you find', [], 'Modules.Legalblink.Main') . ' <a href="https://app.legalblink.it/documenti" target="_blank">' . $this->trans('here', [], 'Modules.Legalblink.Main') . '</a>',
                        'name' => 'LEGALBLINK_TERMS_SERVICE',
                        'label' => $this->trans('Terms of Service', [], 'Modules.Legalblink.Main'),
                        'required' => true,
                        'lang' => true,
                    ],
                    $this->getCookiePolicyPage(),
                    $this->getPrivacyPolicyPage(),
                    $this->getTermsServicePage(),
                    $this->getOtherPages(),
                ],
                'submit' => [
                    'title' => $this->trans('Save', [], 'Modules.Legalblink.Main'),
                ],
            ],
        ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $id_shop = (int)$this->context->shop->id;

        $result = [];
        foreach (static::LINKS as $link => $val) {
            foreach (Language::getIDs() as $id_lang) {
                $result[$link][$id_lang] = Configuration::get($link, $id_lang, null, $id_shop);
            }
            if ($link === 'LEGALBLINK_TERMS_SERVICE') {
                $id_lang = $this->context->language->id;
                $tos = Configuration::get('PS_CONDITIONS_CMS_ID', null, null, $id_shop);
                $cms = new CMS($tos, $id_lang);
                $result[$link . self::PAGE] = $cms->meta_title;
            } else {
                $result[$link . self::PAGE] = Configuration::get($link . self::PAGE, null, null, $id_shop);
            }
        }
        $result[self::LEGALBLINK_COOKIE_CODE] = Configuration::get(self::LEGALBLINK_COOKIE_CODE, null, null, $id_shop);
        $result[self::LEGALBLINK_OTHER_PAGES_ARRAY] = json_decode(Configuration::get(self::LEGALBLINK_OTHER_PAGES, null, null, $id_shop), true);
        return $result;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $id_shop = (int)$this->context->shop->id;

        // get current HTML purifier setting
        $use_html_purifier = (bool)Configuration::get('PS_USE_HTMLPURIFIER');

        // is it turned on? then turn it off
        if ($use_html_purifier) {
            Configuration::updateValue('PS_USE_HTMLPURIFIER', false);
        }

        $res = true;
        $res &= Configuration::updateValue(self::LEGALBLINK_COOKIE_CODE, Tools::getValue(self::LEGALBLINK_COOKIE_CODE), true, null, $id_shop);

        // turn on purifier if it was turned off
        if ($use_html_purifier) {
            Configuration::updateValue('PS_USE_HTMLPURIFIER', true);
        }

        $values = [];
        foreach (static::LINKS as $link => $val) {
            foreach (Language::getIDs() as $id_lang) {
                $values[$link][$id_lang] = trim(Tools::getValue($link . '_' . $id_lang, ''));
            }
        }
        foreach (static::LINKS as $link => $val) {
            $res &= Configuration::updateValue($link, $values[$link], false, null, $id_shop);
            if (Configuration::get('PS_ALLOW_HTML_IFRAME') && $link !== 'LEGALBLINK_TERMS_SERVICE') {
                $res &= Configuration::updateValue($link . self::PAGE, Tools::getValue($link . self::PAGE), false, null, $id_shop);
            }
        }

        if (Configuration::get('PS_ALLOW_HTML_IFRAME')) {
            foreach (static::LINKS as $link => $val) {
                if ($link === 'LEGALBLINK_TERMS_SERVICE') {
                    $page = Configuration::get('PS_CONDITIONS_CMS_ID', null, null, $id_shop);
                } else {
                    $page = Configuration::get($link . self::PAGE, null, null, $id_shop);
                }
                foreach (Language::getIDs() as $id_lang) {
                    $cms = new CMS($page);
                    $cms->content[$id_lang] = '<iframe id="iframe-legalblink" width="100%" height="100%" style="border: none;" src="' . Configuration::get($link, $id_lang, null, $id_shop) . '"></iframe>';
                    $cms->update();
                }
            }
            Configuration::updateValue(self::LEGALBLINK_OTHER_PAGES, json_encode(Tools::getValue(self::LEGALBLINK_OTHER_PAGES)));
        }

        return $res;
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookFilterCmsContent($params)
    {
        if (!$this->active) {
            return;
        }
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
    }

    public function hookDisplayBeforeBodyClosingTag()
    {
        if ($this->active) {
            $id_shop = (int)$this->context->shop->id;
            return Configuration::get(self::LEGALBLINK_COOKIE_CODE, null, null, $id_shop);
        }
        return '';
    }

    public function renderWidget($hookName, array $configuration = [])
    {
        if (!$this->active) {
            return '';
        }
        $keyCache = 'legalblink' . $hookName;

        if (!$this->isCached($this->templateFile, $this->getCacheId($keyCache))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId($keyCache));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $id_shop = (int)$this->context->shop->id;
        $id_lang = $this->context->language->id;


        $elements = [];
        foreach (static::LINKS as $link => $val) {
            $conf = Configuration::get($link, $id_lang, null, $id_shop);
            if ($link === 'LEGALBLINK_TERMS_SERVICE') {
                $page = Configuration::get('PS_CONDITIONS_CMS_ID', null, null, $id_shop);
            } else {
                $page = Configuration::get($link . self::PAGE, null, null, $id_shop);
            }
            if (!empty($page)) {
                $cms = new CMS($page, $id_lang);
                $elements[$link]['link'] = $this->linkPresenter->getCMSLink($cms);
                $elements[$link]['text'] = $cms->meta_title;
                unset($cms);
            } else if (!empty($conf)) {
                $elements[$link]['link'] = $conf;
                $elements[$link]['text'] = $this->trans($val, [], 'Modules.Legalblink.Main');
            }
        }

        $others = json_decode(Configuration::get(self::LEGALBLINK_OTHER_PAGES, null, null, $id_shop), true);
        if (!empty($others)) {
            foreach ($others as $other) {
                $cms = new CMS($other, $id_lang);
                $elements[$cms->id_cms]['link'] = $this->linkPresenter->getCMSLink($cms);
                $elements[$cms->id_cms]['text'] = $cms->meta_title;
                unset($cms);
            }
        }

        return [
            'elements' => $elements,
        ];
    }

    /**
     * @return array
     */
    private function getCookiePolicyPage(): array
    {
        if (!Configuration::get('PS_ALLOW_HTML_IFRAME')) {
            return [];
        }

        return [
            'type' => 'select',
            'desc' => $this->trans('Enter the cookie policy cms page', [], 'Modules.Legalblink.Main'),
            'name' => 'LEGALBLINK_COOKIE_POLICY_PAGE',
            'label' => $this->trans('Cookie policy', [], 'Modules.Legalblink.Main'),
            'options' => [
                'query' => CMS::getCMSPages(Context::getContext()->language->id),
                'id' => 'id_cms',
                'name' => 'meta_title',
            ],
        ];
    }

    /**
     * @return array
     */
    private function getPrivacyPolicyPage(): array
    {
        if (!Configuration::get('PS_ALLOW_HTML_IFRAME')) {
            return [];
        }

        return [
            'type' => 'select',
            'desc' => $this->trans('Enter the privacy policy cms page', [], 'Modules.Legalblink.Main'),
            'name' => 'LEGALBLINK_PRIVACY_POLICY_PAGE',
            'label' => $this->trans('Privacy policy', [], 'Modules.Legalblink.Main'),
            'options' => [
                'query' => CMS::getCMSPages(Context::getContext()->language->id),
                'id' => 'id_cms',
                'name' => 'meta_title',
            ],
        ];
    }

    /**
     * @return array
     */
    private function getTermsServicePage(): array
    {
        if (!Configuration::get('PS_ALLOW_HTML_IFRAME')) {
            return [];
        }

        return [
            'type' => 'text',
            'desc' => $this->trans('Terms of service cms page', [], 'Modules.Legalblink.Main'),
            'name' => 'LEGALBLINK_TERMS_SERVICE_PAGE',
            'label' => $this->trans('Terms of Service', [], 'Modules.Legalblink.Main'),
            'readonly' => true,
        ];
    }

    /**
     * @return array
     */
    private function getOtherPages(): array
    {
        return [
            'type' => 'select',
            'desc' => $this->trans('Please select other pages you want to visualise in footer', [], 'Modules.Legalblink.Main'),
            'name' => self::LEGALBLINK_OTHER_PAGES,
            'class' => 'chosen',
            'multiple' => true,
            'label' => $this->trans('Other Pages you want to visualise', [], 'Modules.Legalblink.Main'),
            'options' => [
                'query' => CMS::getCMSPages(Context::getContext()->language->id),
                'id' => 'id_cms',
                'name' => 'meta_title',
            ],
        ];
    }

}
