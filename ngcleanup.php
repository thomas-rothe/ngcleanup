<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Ngcleanup extends Module
{
    public function __construct()
    {
        $this->name = 'ngcleanup';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => '1.7.8.9'];
        $this->author = 'Thomas Rothe';
        $this->is_configurable = true;
        $this->need_instance = true;
        parent::__construct();

        $this->displayName = $this->l('Clean Up Module ');
        $this->description = $this->l('Regularly clears guest and connection tables, triggers faceted search crons, and clears frontend cache.');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionCronJob');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionCronJob()
    {
        $this->clearTables();
        $this->triggerFacetedSearchCrons();
        $this->clearFrontendCache();
    }

    private function clearTables()
    {
        $db = Db::getInstance();
        $db->execute('DELETE FROM ' . _DB_PREFIX_ . 'cart WHERE id_guest > 0');
        $db->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'guest');
        $db->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'connections');
        $db->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'connections_source');
    }

    private function triggerFacetedSearchCrons()
    {
        $baseUrl = Tools::getShopDomainSsl(true, true);

        $urls = [
            $baseUrl . '/module/ps_facetedsearch/cron?ajax=1&action=indexPrices&token=54a2f9a518',
            $baseUrl . '/module/ps_facetedsearch/cron?ajax=1&action=indexPrices&full=1&token=54a2f9a518',
            $baseUrl . '/module/ps_facetedsearch/cron?ajax=1&action=indexAttributes&token=54a2f9a518',
            $baseUrl . '/module/ps_facetedsearch/cron?ajax=1&action=clearCache&token=54a2f9a518'
        ];

        foreach ($urls as $url) {
            $this->callUrl($url);
        }
    }

    private function clearFrontendCache()
    {
        Media::clearCache();
        Tools::clearSmartyCache();
        Tools::clearXMLCache();
        PrestaShop\PrestaShop\Adapter\Cache\Clearer::clearAllCaches();
    }

    private function callUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}
