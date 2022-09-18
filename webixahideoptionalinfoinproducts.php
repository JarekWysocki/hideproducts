<?php
/**
 * 2007-2021 PrestaShop
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
 * @copyright 2007-2021 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class_exists('SwapCustomFormInput') or require_once _PS_MODULE_DIR_ . 'webixahideoptionalinfoinproducts/classes/SwapCustomFormInput.php';

class WebixaHideOptionalInfoInProducts extends Module
{
    protected $config_form = true;

    public const SELECTED_PRODUCTS_CONFIGURATION_KEY = 'WEBIXA_HOIIP_SELETEC_PRODUCT';
    public const SELECTED_AVAIABLE_PRODUCTS_CONFIGURATION_KEY = 'WEBIXA_HOIIP_SELETEC_AVAIABLE_PRODUCT';
    public const SELECTED_CATEGORIES2_CONFIGURATION_KEY = 'WEBIXA_HOIIP_SELETEC_CATEGORIES2';
    public const SELECTED_CATEGORIE_CONFIGURATION_KEY = 'WEBIXA_HOIIP_SELETEC_CATEGORIES';
    public const SELECTED_MANUFACTURES_CONFIGURATION_KEY = 'WEBIXA_HOIIP_SELETEC_MANUFACTURES';

    public function __construct()
    {
        $this->name = 'webixahideoptionalinfoinproducts';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Webixa';
        $this->need_instance = true;

        parent::__construct();

        $this->displayName = $this->l('Wybieranie produktów i kategorii dla różnych akcji');
        $this->description = $this->l('');

        $this->ps_versions_compliancy = [
            'min' => '1.7.3',
            'max' => _PS_VERSION_,
        ];
    }

    public function getContent()
    {
        return $this->renderForm();
    }

    public function renderForm()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue(self::SELECTED_PRODUCTS_CONFIGURATION_KEY, implode(';', Tools::getValue('products')));
            Configuration::updateValue(self::SELECTED_CATEGORIES2_CONFIGURATION_KEY, implode(';', Tools::getValue('categories2')));
            Configuration::updateValue(self::SELECTED_CATEGORIE_CONFIGURATION_KEY, implode(';', Tools::getValue('categories')));
            Configuration::updateValue(self::SELECTED_AVAIABLE_PRODUCTS_CONFIGURATION_KEY, implode(';', Tools::getValue('avaiableproducts')));
            Configuration::updateValue(self::SELECTED_MANUFACTURES_CONFIGURATION_KEY, implode(';', Tools::getValue('manufactures')));
        }

        $products = $this->getProductsLiteNotArchive();
        $manufactures = $this->getManufactures();

        $formWithManufactures = new SwapCustomFormInput();
        Configuration::get('SELECTED_MANUFACTURES_CONFIGURATION_KEY');

        $formWithManufactures
            ->setLabel($this->l('Ukryj ceny dla producenta'))
            ->setName('manufactures[]')
            ->setClass('switch_products')
            ->setMultiple(true)
            ->setSearch(true)
            ->setRequired(false)
            ->setCol(12)
            ->setOptions($manufactures, 'id_manufacturer', 'name')
            ->setDescription($this->l('Wybierz producentów dla których chcesz ukryć ceny'));

        $formWithAvaiableProducts = new SwapCustomFormInput();
        Configuration::get('SELECTED_AVAIABLE_PRODUCTS_CONFIGURATION_KEY');

        $formWithAvaiableProducts
            ->setLabel($this->l('Zmień status na dostępny'))
            ->setName('avaiableproducts[]')
            ->setClass('switch_products')
            ->setMultiple(true)
            ->setSearch(true)
            ->setRequired(false)
            ->setCol(12)
            ->setOptions($products, 'id_product', 'name')
            ->setDescription($this->l('Wybierz produkty dla których chcesz zmienić status produktu na dostępny'));

        $formWithProducts = new SwapCustomFormInput();
        Configuration::get('SELECTED_PRODUCTS_CONFIGURATION_KEY');

        $formWithProducts
            ->setLabel($this->l('Zmień status na "Zapytaj o dostępność"'))
            ->setName('products[]')
            ->setClass('switch_products')
            ->setMultiple(true)
            ->setSearch(true)
            ->setRequired(false)
            ->setCol(12)
            ->setOptions($products, 'id_product', 'name')
            ->setDescription($this->l('Wybierz produkty dla których chcesz zmienić status produktu'));

        $categories = $this->getCategories();
        $formWithCategories = new SwapCustomFormInput();
        $formWithCategories
            ->setLabel($this->l('Wybierz kategorie2'))
            ->setName('categories2[]')
            ->setClass('switch_products')
            ->setMultiple(true)
            ->setSearch(true)
            ->setRequired(false)
            ->setCol(12)
            ->setOptions($categories, 'id_category', 'name')
            ->setDescription($this->l('Wybierz kategorie dla których...'));

        $categories2 = $this->getCategories();
        $inputCategory = new SwapCustomFormInput();

        $inputCategory
            ->setLabel($this->l('Dodanie opisu z gwiazdką - certyfikaty'))
            ->setName('categories[]')
            ->setClass('switch_products')
            ->setMultiple(true)
            ->setSearch(true)
            ->setRequired(false)
            ->setCol(12)
            ->setOptions($categories2, 'id_category', 'name')
            ->setDescription($this->l('Wybierz kategorie dla których...'));

        $fields_form = [
            'form' => [
                'input' => [
                    $formWithManufactures->getConfiguration(),
                    $formWithAvaiableProducts->getConfiguration(),
                    $formWithProducts->getConfiguration(),
                    $formWithCategories->getConfiguration(),
                    $inputCategory->getConfiguration()
                ],
                'submit' => [
                    'title' => $this->trans('Zapisz', [], 'Admin.Actions'),
                ],
            ]];

        $fields_value['products[]'] = explode(';', Configuration::get(self::SELECTED_PRODUCTS_CONFIGURATION_KEY));
        $fields_value['categories2[]'] = explode(';', Configuration::get(self::SELECTED_CATEGORIES2_CONFIGURATION_KEY));
        $fields_value['categories[]'] = explode(';', Configuration::get(self::SELECTED_CATEGORIE_CONFIGURATION_KEY));
        $fields_value['avaiableproducts[]'] = explode(';', Configuration::get(self::SELECTED_AVAIABLE_PRODUCTS_CONFIGURATION_KEY));
        $fields_value['manufactures[]'] = explode(';', Configuration::get(self::SELECTED_MANUFACTURES_CONFIGURATION_KEY));

        $helper = $this->createProductMultiselectForm($fields_form, $fields_value);

        return $helper->generateForm(array($fields_form));
    }

    /**
     * Builds form for configuration page
     * @param array $fields_form
     * @param array $fields_value
     * @return HelperForm
     */
    private function createProductMultiselectForm(array $fields_form, array $fields_value)
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?: 0;
        $this->fields_form = [];
        $helper->id = 1;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure='
            . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];
        $helper->module = $this;

        return $helper;
    }

    private function getProductsLite($only_active = false, $front = false)
    {
        $sql = 'SELECT p.`id_product`, CONCAT(p.`reference`, " - ", pl.`name`) as name FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
                WHERE pl.`id_lang` = ' . (int)$this->context->language->id .
            ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') .
            ($only_active ? ' AND product_shop.`active` = 1' : '');

        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return ($rq);
    }

    private function getProductsLiteNotArchive($only_active = false, $front = false)
    {
        $sql = 'SELECT p.`id_product`, CONCAT(p.`reference`, " - ", pl.`name`) as name FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'category_product` cp ON (p.`id_product` = cp.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
                WHERE pl.`id_lang` = ' . (int)$this->context->language->id . ' AND cp.id_category <> 98' .
            ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') .
            ($only_active ? ' AND product_shop.`active` = 1' : '') . '
            GROUP BY pl.id_product';

        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return ($rq);
    }

    /**
     * Gets manufactures list as object array
     * @param $only_active
     * @param $front
     * @return array|bool|mysqli_result|PDOStatement|resource|null
     */
    private function getManufactures($only_active = false, $front = false)
    {
        $sql = 'SELECT id_manufacturer, name FROM ' . _DB_PREFIX_ . 'manufacturer';

        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return ($rq);
    }

    /**
     * Gets list of categories as array of objects
     * @param $idLang
     * @param $active
     * @param $order
     * @param $sqlFilter
     * @param $orderBy
     * @param $limit
     * @return array|bool|mysqli_result|PDOStatement|resource|void|null
     */
    public static function getCategories($idLang = false, $active = true, $order = true, $sqlFilter = '', $orderBy = '', $limit = '')
    {
        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            '
			SELECT c.`id_category`, cl.`name` as name
			FROM `' . _DB_PREFIX_ . 'category` c
			' . Shop::addSqlAssociation('category', 'c') . '
			LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON c.`id_category` = cl.`id_category`' . Shop::addSqlRestrictionOnLang('cl') . '
			WHERE 1 ' . $sqlFilter . ' ' . ($idLang ? 'AND `id_lang` = ' . (int)$idLang : '') . '
			' . ($active ? 'AND `active` = 1' : '') . '
			' . (!$idLang ? 'GROUP BY c.id_category' : '') . '
			' . ($orderBy != '' ? $orderBy : 'ORDER BY c.`level_depth` ASC, category_shop.`position` ASC') . '
			' . ($limit != '' ? $limit : '')
        );
    }


    public function displayForCategorie($categoryDefaultID)
    {
        $listOfCategoriesId = explode(';', Configuration::get(self::SELECTED_CATEGORIE_CONFIGURATION_KEY));
        return in_array($categoryDefaultID, $listOfCategoriesId);
    }

    public function displayEnquireAboutTheProduct($productId)
    {
        $listOfProductsId = explode(';', Configuration::get(self::SELECTED_PRODUCTS_CONFIGURATION_KEY));
        return in_array($productId, $listOfProductsId);
    }

    public function displayAvaiableAboutTheProduct($productId)
    {
        $listOfProductsId = explode(';', Configuration::get(self::SELECTED_AVAIABLE_PRODUCTS_CONFIGURATION_KEY));
        return in_array($productId, $listOfProductsId);
    }

    public function hidePriceForManufacturer($idManufacturer)
    {
        $listOfManufacturers = explode(';', Configuration::get(self::SELECTED_MANUFACTURES_CONFIGURATION_KEY));
        return in_array($idManufacturer, $listOfManufacturers);
    }
}
//just put in template for example this:
// Module::getInstanceByName('webixahideoptionalinfoinproducts')->hidePriceForManufacturer($product->id_manufacturer)
//and it should return bool variable


