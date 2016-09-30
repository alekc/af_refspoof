<?php
class af_refspoof extends Plugin {
    /** @var PluginHost **/
    protected $host;

    /** @var Db **/
    protected $dbh;

    function about() {
        return array(
            "1.0.4",
            "Fakes Referral on Images",
            "Alexander Chernov"
            );
    }
    /**
    * Init
    *
    * @param PluginHost $host
    */
    function init($host) {
        if (!class_exists('PhCURL')) {
            require_once ("PhCURL.php");
        }

        $this->host = $host;
        $this->dbh = Db::get();
        $host->add_hook($host::HOOK_RENDER_ARTICLE_CDM, $this);
        $host->add_hook($host::HOOK_PREFS_TAB, $this);
    }

    /**
    * Preference tab hook
    *
    * @param mixed $args
    */
    function hook_prefs_tab($args)
    {
        if ($args != "" && $args != "prefPrefs"){
            return;
        }
        $configFeeds = $this->host->get($this,"feeds");
        $feeds = $this->getFeeds();

        print <<<EOF
        <div id="refSpoofConfigTab" dojoType="dijit.layout.ContentPane" title="{$this->translate('Plugin RefSpoof')}" style="overflow:auto">
EOF;
        if (count($feeds)){
            //table header
            print <<<EOF
            <form dojoType="dijit.form.Form" style="width:95%">
            <input dojoType="dijit.form.TextBox" style="display : none" name="op" value="pluginhandler">
            <input dojoType="dijit.form.TextBox" style="display : none" name="method" value="saveConfig">
            <input dojoType="dijit.form.TextBox" style="display : none" name="plugin" value="af_refspoof">

            <script type="dojo/method" event="onSubmit" args="evt">
                evt.preventDefault();
                if (this.validate()) {
                    new Ajax.Request('backend.php', {
                        parameters: dojo.objectToQuery(this.getValues()),
                        onComplete: function(transport) {
                            if (transport.responseText.indexOf('error')>=0)
                                notify_error(transport.responseText);
                            else notify_info(transport.responseText);
                        }
                    });
                }
                </script>
            <table>
                <tr>
                    <th>{$this->translate("Feed Name")}</th>
                    <th></th>
                </tr>
EOF;
            foreach ($feeds as $feed){
                $checked = "";
                if (isset($configFeeds[$feed->id])){
                    $checked = "checked='checked'";
                }
                print <<<EOF
                <tr>
                    <td colspan="2">
                        <input dojoType="dijit.form.CheckBox" type="checkbox" name="refSpoofFeed[{$feed->id}]" id="refSpoofFeed_{$feed->id}" value="1" {$checked}>{$feed->title}
                    </td>
                </tr>
EOF;
            }
            print <<<EOF
            <tr>
                <td></td>
                <td><button dojoType="dijit.form.Button" type="submit">{$this->translate("Save")}</button></td>
            </tr>
EOF;
            print "</table>";
            print "</form>";
        }
        print "</div>";
    }

    function hook_render_article_cdm($article)
    {
        $feedId = $article['feed_id'];
        $feeds  = $this->host->get($this, 'feeds');

        if (is_array($feeds) && in_array($feedId,array_keys($feeds))){
            $doc = new DOMDocument();
            @$doc->loadHTML($article['content']);
            if ($doc) {
                $xpath = new DOMXPath($doc);
                $entries = $xpath->query('(//img[@src])');
                /** @var $entry DOMElement **/
                $entry = null;
                $backendURL = 'backend.php?op=pluginhandler&method=redirect&plugin=af_refspoof';
                foreach ($entries as $entry){
                    $origSrc = $entry->getAttribute("src");
                    if ($origSrcSet = $entry->getAttribute("srcset")) {
                        $srcSet = preg_replace_callback('#([^\s]+://[^\s]+)#', function ($m) use ($backendURL, $article) {
                            return $backendURL . '&url=' . urlencode($m[0]) . '&ref=' . urlencode($article['link']);
                        }, $origSrcSet);

                        $entry->setAttribute("srcset", $srcSet);
                    }
                    $url = $backendURL . '&url=' . urlencode($origSrc) . '&ref=' . urlencode($article['link']);
                    $entry->setAttribute("src",$url);
                }
                $article["content"] = $doc->saveXML();
            }
        }
        return $article;
    }
    function redirect()
    {
        $client = new PhCURL($_REQUEST["url"]);
        $client->loadCommonSettings();
        $client->enableHeaderInOutput(false);
        $client->setReferer($_REQUEST["ref"]);
        $client->setUserAgent();

        $client->GET();
        ob_end_clean();
        //header_remove("Content-Type: text/json; charset=utf-8");
        header("Content-Type: ". $client->getContentType());
        echo $client->getData();
        exit(1);
    }
    function saveConfig()
    {
        $config = (array) $_POST['refSpoofFeed'];
        $this->host->set($this, 'feeds', $config);
        echo __("Configuration saved.");
    }
    protected function translate($msg){
        return __($msg);
    }
    /**
    * Find feeds from db
    *
    * @return array feeds
    */
    protected function getFeeds()
    {
        $feeds = array();
        $result = $this->dbh->query("SELECT id, title
                FROM ttrss_feeds
                WHERE owner_uid = ".$_SESSION["uid"].
                " ORDER BY order_id, title");
        while ($line = $this->dbh->fetch_assoc($result)) {
            $feeds[] = (object) $line;
        }
        return $feeds;
    }
    function api_version() {
        return 2;
    }

}
